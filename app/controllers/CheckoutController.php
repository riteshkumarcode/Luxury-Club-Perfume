<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Core\Validator;
use App\Core\Mailer;
use App\Models\Cart;
use App\Models\Order;

class CheckoutController
{
    public function show(): void
    {
        $items = Cart::getItemsWithDetails();
        $totals = Cart::getTotals();

        if (empty($items)) {
            redirect('/cart');
        }

        View::setMeta([
            'title' => 'Checkout | Luxury Club',
            'description' => 'Complete your purchase securely. Complimentary express shipping and gift packaging.'
        ]);

        View::render('pages/checkout', [
            'items' => $items,
            'totals' => $totals,
            'errors' => [],
            'formData' => [],
        ]);
    }

    public function process(): void
    {
        $items = Cart::getItemsWithDetails();
        $totals = Cart::getTotals();

        if (empty($items)) {
            redirect('/cart');
        }

        $input = $_POST;

        $validator = new Validator();
        $isValid = $validator->validate($input, [
            'customer_name' => 'required|min:2|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'required|phone',
            'address_line1' => 'required|min:5|max:255',
            'city' => 'required|min:2|max:100',
            'state' => 'required|min:2|max:100',
            'pincode' => 'required|pincode',
            'payment_method' => 'required|in:razorpay,cod',
        ]);

        if (!$isValid) {
            View::render('pages/checkout', [
                'items' => $items,
                'totals' => $totals,
                'errors' => $validator->errors(),
                'formData' => $input,
            ]);
            return;
        }

        $paymentMethod = $input['payment_method'];

        // Place order in DB
        $order = Order::createOrder($input, $items, $totals, $paymentMethod);

        // If online payment (Razorpay)
        if ($paymentMethod === 'razorpay') {
            $paymentId = $input['razorpay_payment_id'] ?? null;
            $signature = $input['razorpay_signature'] ?? null;
            
            // Mark payment as paid if payment ID is present
            if (!empty($paymentId)) {
                Order::updatePaymentStatus($order['id'], 'paid', null, $paymentId);
                $order['payment_status'] = 'paid';
            }
        }

        // Send Emails via PHPMailer
        Mailer::sendOrderConfirmation($order, $order['items']);
        Mailer::sendAdminOrderNotification($order, $order['items']);

        // Clear cart
        Cart::clear();

        redirect('/order/success/' . $order['order_no']);
    }

    public function success(string $orderNo): void
    {
        $order = Order::findByOrderNo($orderNo);
        if (!$order) {
            http_response_code(404);
            View::render('pages/404', ['path' => "/order/success/$orderNo"]);
            return;
        }

        View::setMeta([
            'title' => "Order Confirmation #{$order['order_no']} | Luxury Club",
            'description' => "Your Luxury Club perfume order has been confirmed."
        ]);

        View::render('pages/order-success', [
            'order' => $order,
        ]);
    }

    public function checkPincodeApi(): void
    {
        $raw = file_get_contents('php://input');
        $input = json_decode($raw, true) ?: $_POST;

        $pincode = trim((string)($input['pincode'] ?? ''));

        if (!preg_match('/^[1-9]\d{5}$/', $pincode)) {
            json_response([
                'success' => false,
                'message' => 'Please enter a valid 6-digit Indian PIN code.'
            ], 422);
        }

        $estimate = get_setting('delivery_estimate_text', '2–4 business days across major metros; 4–6 days rest of India.');

        json_response([
            'success' => true,
            'pincode' => $pincode,
            'serviceable' => true,
            'estimate' => "Estimated delivery: $estimate",
        ]);
    }
}
