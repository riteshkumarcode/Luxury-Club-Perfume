<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Validator;
use App\Core\Session;
use App\Models\Subscriber;

class NewsletterController
{
    public function subscribe(): void
    {
        $input = $_POST;
        if (empty($input)) {
            $raw = file_get_contents('php://input');
            $input = json_decode($raw, true) ?: [];
        }

        // Honeypot check
        if (!empty($input['website_hp'])) {
            json_response(['success' => true, 'message' => 'Welcome to the Club.']);
        }

        // Rate limiting
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $rateKey = "rate_news_{$clientIp}";
        $attempts = Session::get($rateKey, []);
        $now = time();
        $attempts = array_filter($attempts, fn($t) => $t > ($now - 600));

        if (count($attempts) >= 5) {
            json_response(['success' => false, 'message' => 'Too many attempts. Please try again later.'], 429);
        }

        $validator = new Validator();
        $isValid = $validator->validate($input, [
            'email' => 'required|email|max:150',
        ]);

        if (!$isValid) {
            json_response(['success' => false, 'message' => $validator->firstError()], 422);
        }

        Subscriber::subscribe($input['email']);

        $attempts[] = $now;
        Session::set($rateKey, $attempts);

        json_response([
            'success' => true,
            'message' => 'Welcome to the Club — check your inbox.'
        ]);
    }
}
