<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Models\Setting;

class AdminSettingController
{
    public function index(): void
    {
        $settings = Setting::getAll();
        View::render('admin/settings', [
            'settings' => $settings,
            'pageTitle' => 'Store & Concierge Settings',
        ], 'layouts/admin');
    }

    public function save(): void
    {
        $input = $_POST;
        unset($input['_csrf_token']);

        // Format announcement messages into JSON if array of strings or textarea
        if (isset($input['announcement_messages_raw'])) {
            $lines = array_filter(array_map('trim', explode("\n", $input['announcement_messages_raw'])));
            $input['announcement_messages'] = json_encode(array_values($lines), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            unset($input['announcement_messages_raw']);
        }

        Setting::setMultiple($input);

        flash('success', 'Store settings updated successfully.');
        redirect('/admin/settings');
    }
}
