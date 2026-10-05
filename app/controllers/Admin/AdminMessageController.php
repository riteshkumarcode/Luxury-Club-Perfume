<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Models\Message;

class AdminMessageController
{
    public function index(): void
    {
        $messages = Message::all(50, 0);
        View::render('admin/messages/index', [
            'messages' => $messages,
            'pageTitle' => 'Client Inquiries & Messages',
        ], 'layouts/admin');
    }

    public function markRead(string $id): void
    {
        Message::markAsRead((int)$id);
        flash('success', 'Message marked as read.');
        redirect('/admin/messages');
    }

    public function delete(string $id): void
    {
        Message::delete((int)$id);
        flash('success', 'Message deleted.');
        redirect('/admin/messages');
    }
}
