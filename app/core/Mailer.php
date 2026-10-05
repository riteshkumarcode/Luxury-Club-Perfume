<?php
declare(strict_types=1);

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailerException;

class Mailer
{
    private static function getMailerInstance(): ?PHPMailer
    {
        if (!class_exists(PHPMailer::class)) {
            return null;
        }

        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';

        $host = config('mail.host', '');
        $username = config('mail.username', '');
        $password = config('mail.password', '');

        if (!empty($host) && !empty($username)) {
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = true;
            $mail->Username = $username;
            $mail->Password = $password;
            $mail->SMTPSecure = config('mail.encryption', 'tls') === 'ssl' 
                ? PHPMailer::ENCRYPTION_SMTPS 
                : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = (int)config('mail.port', 2525);
        } else {
            // Local fallback / mail() or log mode
            $mail->isMail();
        }

        $fromAddress = config('mail.from_address', 'concierge@luxuryclub.com');
        $fromName = config('mail.from_name', 'Luxury Club Concierge');
        $mail->setFrom($fromAddress, $fromName);

        return $mail;
    }

    public static function send(string $toEmail, string $toName, string $subject, string $htmlBody, string $altBody = ''): bool
    {
        try {
            $mail = self::getMailerInstance();
            if (!$mail) {
                self::logEmail($toEmail, $subject, $htmlBody);
                return true;
            }

            $mail->addAddress($toEmail, $toName);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlBody;
            $mail->AltBody = $altBody ?: strip_tags($htmlBody);

            // If no real credentials in development, log and simulate success
            if (empty(config('mail.username'))) {
                self::logEmail($toEmail, $subject, $htmlBody);
                return true;
            }

            return $mail->send();
        } catch (MailerException $e) {
            error_log("PHPMailer Error: " . $e->getMessage());
            self::logEmail($toEmail, $subject, $htmlBody . " [FAILED: {$e->getMessage()}]");
            return false;
        }
    }

    public static function sendOrderConfirmation(array $order, array $items): bool
    {
        $orderNo = $order['order_no'];
        $subject = "Your Luxury Club Order Confirmation [{$orderNo}]";
        
        $itemRows = '';
        foreach ($items as $item) {
            $name = e($item['name_snapshot']);
            $qty = (int)$item['qty'];
            $price = formatInr($item['price_snapshot']);
            $total = formatInr($item['line_total']);
            $itemRows .= "
                <tr>
                    <td style='padding: 12px; border-bottom: 1px solid #E2D9C9;'>$name</td>
                    <td style='padding: 12px; border-bottom: 1px solid #E2D9C9; text-align: center;'>$qty</td>
                    <td style='padding: 12px; border-bottom: 1px solid #E2D9C9; text-align: right;'>$price</td>
                    <td style='padding: 12px; border-bottom: 1px solid #E2D9C9; text-align: right; font-weight: bold;'>$total</td>
                </tr>";
        }

        $subtotal = formatInr($order['subtotal']);
        $shipping = $order['shipping'] > 0 ? formatInr($order['shipping']) : 'Free';
        $total = formatInr($order['total']);
        $customerName = e($order['customer_name']);
        $address = e("{$order['address_line1']}, " . ($order['address_line2'] ? "{$order['address_line2']}, " : '') . "{$order['city']}, {$order['state']} - {$order['pincode']}");
        $phone = e($order['phone']);

        $html = "
        <div style='background-color: #FBF8F2; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; padding: 40px 20px; color: #17130E;'>
            <div style='max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #E2D9C9; box-shadow: 0 4px 20px rgba(0,0,0,0.04);'>
                <div style='background-color: #0D0B08; padding: 32px 24px; text-align: center; color: #F4EEE2;'>
                    <div style='color: #D8B25C; font-size: 20px; letter-spacing: 0.16em; font-weight: 700; text-transform: uppercase; margin-bottom: 4px;'>LUXURY CLUB</div>
                    <div style='color: #D8B25C; font-size: 12px; font-style: italic; letter-spacing: 0.05em;'>The Magic of Luxury Fragrances</div>
                </div>
                
                <div style='padding: 32px 24px;'>
                    <h2 style='font-size: 22px; margin-top: 0; color: #0D0B08;'>Thank You for Your Order, $customerName</h2>
                    <p style='color: #5C5246; line-height: 1.6; font-size: 15px;'>
                        Your order <strong>#$orderNo</strong> has been received and is being prepared with artisanal care at our atelier.
                    </p>

                    <table style='width: 100%; border-collapse: collapse; margin: 24px 0; font-size: 14px;'>
                        <thead>
                            <tr style='background-color: #F3EDE2; color: #0D0B08; text-transform: uppercase; font-size: 11px; letter-spacing: 0.1em;'>
                                <th style='padding: 10px 12px; text-align: left;'>Fragrance</th>
                                <th style='padding: 10px 12px; text-align: center;'>Qty</th>
                                <th style='padding: 10px 12px; text-align: right;'>Price</th>
                                <th style='padding: 10px 12px; text-align: right;'>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            $itemRows
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan='3' style='padding: 10px 12px; text-align: right; color: #5C5246;'>Subtotal:</td>
                                <td style='padding: 10px 12px; text-align: right;'>$subtotal</td>
                            </tr>
                            <tr>
                                <td colspan='3' style='padding: 10px 12px; text-align: right; color: #5C5246;'>Express Shipping:</td>
                                <td style='padding: 10px 12px; text-align: right;'>$shipping</td>
                            </tr>
                            <tr style='font-size: 16px; font-weight: bold; color: #0D0B08;'>
                                <td colspan='3' style='padding: 12px; text-align: right; border-top: 2px solid #0D0B08;'>Grand Total:</td>
                                <td style='padding: 12px; text-align: right; border-top: 2px solid #0D0B08; color: #8A6A22;'>$total</td>
                            </tr>
                        </tfoot>
                    </table>

                    <div style='background-color: #F8F5EE; border-radius: 12px; padding: 20px; margin-top: 24px;'>
                        <h4 style='margin-top: 0; margin-bottom: 8px; font-size: 13px; text-transform: uppercase; letter-spacing: 0.1em; color: #0D0B08;'>Delivery Address</h4>
                        <p style='margin: 0; font-size: 14px; color: #5C5246; line-height: 1.5;'>
                            <strong>$customerName</strong><br>
                            $address<br>
                            Phone: $phone
                        </p>
                    </div>
                </div>

                <div style='background-color: #0D0B08; padding: 20px; text-align: center; color: #CFC4B2; font-size: 12px;'>
                    <p style='margin: 0;'>Have questions? Contact our Concierge at <a href='mailto:concierge@luxuryclub.com' style='color: #D8B25C;'>concierge@luxuryclub.com</a></p>
                    <p style='margin: 8px 0 0; opacity: 0.7;'>© " . date('Y') . " Luxury Club. All rights reserved.</p>
                </div>
            </div>
        </div>";

        return self::send($order['email'], $order['customer_name'], $subject, $html);
    }

    public static function sendAdminOrderNotification(array $order, array $items): bool
    {
        $adminEmail = config('mail.admin_notify', config('admin.email'));
        $orderNo = $order['order_no'];
        $subject = "[New Order Alert] #{$orderNo} by {$order['customer_name']} (" . formatInr($order['total']) . ")";
        
        $html = "<p>A new order has been placed on Luxury Club:</p>
                 <p><strong>Order No:</strong> #$orderNo<br>
                 <strong>Customer:</strong> " . e($order['customer_name']) . " (" . e($order['email']) . ")<br>
                 <strong>Total:</strong> " . formatInr($order['total']) . "<br>
                 <strong>Payment:</strong> " . e($order['payment_method']) . " (" . e($order['payment_status']) . ")</p>
                 <p><a href='" . url('/admin/orders') . "'>View in Admin Panel &rarr;</a></p>";

        return self::send($adminEmail, 'Luxury Club Admin', $subject, $html);
    }

    public static function sendAdminContactNotification(array $msg): bool
    {
        $adminEmail = config('mail.admin_notify', config('admin.email'));
        $subject = "[Contact Inquiry] {$msg['topic']} from {$msg['name']}";
        
        $html = "<p>New contact form submission received:</p>
                 <p><strong>Name:</strong> " . e($msg['name']) . "<br>
                 <strong>Email:</strong> " . e($msg['email']) . "<br>
                 <strong>Phone:</strong> " . e($msg['phone'] ?? 'N/A') . "<br>
                 <strong>Topic:</strong> " . e($msg['topic']) . "</p>
                 <hr>
                 <p><strong>Message:</strong></p>
                 <p style='white-space: pre-wrap;'>" . e($msg['message']) . "</p>";

        return self::send($adminEmail, 'Luxury Club Admin', $subject, $html);
    }

    private static function logEmail(string $to, string $subject, string $body): void
    {
        $logDir = dirname(__DIR__, 2) . '/storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        $logFile = $logDir . '/mail.log';
        $entry = "[" . date('Y-m-d H:i:s') . "] TO: $to | SUBJECT: $subject\n" . strip_tags($body) . "\n" . str_repeat('-', 60) . "\n";
        file_put_contents($logFile, $entry, FILE_APPEND);
    }
}
