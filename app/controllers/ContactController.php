<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Core\Validator;
use App\Core\Mailer;
use App\Core\Session;
use App\Models\Message;

class ContactController
{
    public function show(): void
    {
        View::setMeta([
            'title' => 'Concierge & Atelier Contact | Luxury Club',
            'description' => 'Contact the Luxury Club concierge for bespoke orders, gifting curations, and fragrance consultation.'
        ]);
        View::render('pages/contact');
    }

    public function submit(): void
    {
        $input = $_POST;
        if (empty($input)) {
            $raw = file_get_contents('php://input');
            $input = json_decode($raw, true) ?: [];
        }

        // Honeypot check
        if (!empty($input['website_hp'])) {
            json_response(['success' => true, 'message' => 'Thank you for your message.']);
        }

        // Rate limiting: max 3 submissions per 10 mins per IP
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $rateKey = "rate_contact_{$clientIp}";
        $attempts = Session::get($rateKey, []);
        $now = time();
        $attempts = array_filter($attempts, fn($t) => $t > ($now - 600));

        if (count($attempts) >= 3) {
            json_response(['success' => false, 'message' => 'Too many submissions. Please wait a few minutes before sending another inquiry.'], 429);
        }

        $validator = new Validator();
        $isValid = $validator->validate($input, [
            'name' => 'required|min:2|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'max:30',
            'topic' => 'required|in:Order support,Help choosing a fragrance,Gifting & corporate orders,Wholesale,Something else',
            'message' => 'required|min:10|max:1000',
        ]);

        if (!$isValid) {
            json_response([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => $validator->firstError()
            ], 422);
        }

        // Save to messages table
        $msgId = Message::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'] ?? null,
            'topic' => $input['topic'],
            'message' => $input['message'],
        ]);

        // Send notification email to admin
        Mailer::sendAdminContactNotification($input);

        // Record rate limit attempt
        $attempts[] = $now;
        Session::set($rateKey, $attempts);

        json_response([
            'success' => true,
            'message' => 'Thank you. Your message has been received by our concierge.',
            'id' => $msgId,
        ]);
    }
}
