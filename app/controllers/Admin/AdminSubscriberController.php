<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Models\Subscriber;

class AdminSubscriberController
{
    public function index(): void
    {
        $subscribers = Subscriber::all(200, 0);
        View::render('admin/subscribers', [
            'subscribers' => $subscribers,
            'pageTitle' => 'Newsletter Concierge Subscribers',
        ], 'layouts/admin');
    }

    public function exportCsv(): void
    {
        $subscribers = Subscriber::all(10000, 0);
        $filename = 'luxury_club_subscribers_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $out = fopen('php://output', 'w');
        fputcsv($out, ['ID', 'Email Address', 'Subscribed At']);

        foreach ($subscribers as $s) {
            fputcsv($out, [$s['id'], $s['email'], $s['created_at']]);
        }

        fclose($out);
        exit;
    }
}
