<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Session;
use App\Models\AdminUser;

class AdminAuthController
{
    public function showLogin(): void
    {
        Session::start();
        if (Session::has('admin_user_id')) {
            redirect('/admin');
        }

        View::setMeta(['title' => 'Admin Login | Luxury Club']);
        View::render('admin/login', [], '');
    }

    public function login(): void
    {
        Session::start();

        // Rate limiting: 5 attempts per 15 minutes per IP
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $rateKey = "admin_login_rate_{$clientIp}";
        $attempts = Session::get($rateKey, []);
        $now = time();
        $attempts = array_filter($attempts, fn($t) => $t > ($now - 900));

        if (count($attempts) >= 5) {
            flash('error', 'Too many failed login attempts. Please wait 15 minutes.');
            redirect('/admin/login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');

        $user = AdminUser::verify($email, $password);

        if (!$user) {
            $attempts[] = $now;
            Session::set($rateKey, $attempts);
            flash('error', 'Invalid email or password.');
            redirect('/admin/login');
        }

        // Regenerate session ID for security
        Session::regenerate(true);
        Session::set('admin_user_id', $user['id']);
        Session::set('admin_user_name', $user['name']);
        Session::set('admin_user_email', $user['email']);

        $redirect = Session::get('admin_redirect_after_login', '/admin');
        Session::remove('admin_redirect_after_login');

        flash('success', "Welcome back, {$user['name']}!");
        redirect($redirect);
    }

    public function logout(): void
    {
        Session::remove('admin_user_id');
        Session::remove('admin_user_name');
        Session::remove('admin_user_email');
        Session::regenerate(true);
        flash('success', 'You have been logged out.');
        redirect('/admin/login');
    }
}
