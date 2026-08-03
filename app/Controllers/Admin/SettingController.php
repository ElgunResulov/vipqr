<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Uploader;
use App\Models\Setting;
use RuntimeException;

final class SettingController extends Controller
{
    public function edit(): void
    {
        $this->requireAuth();
        $this->render('admin/settings/form', [
            'title' => 'Parametrlər',
            'settings' => Setting::all(),
        ], 'admin/layouts/app');
    }

    public function update(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $name = trim((string) ($_POST['restaurant_name'] ?? ''));
        $phone = trim((string) ($_POST['phone'] ?? ''));
        $address = trim((string) ($_POST['address'] ?? ''));
        $hours = trim((string) ($_POST['working_hours'] ?? ''));
        $about = trim((string) ($_POST['about'] ?? ''));

        store_old([
            'restaurant_name' => $name,
            'phone' => $phone,
            'address' => $address,
            'working_hours' => $hours,
            'about' => $about,
        ]);

        if ($name === '') {
            flash('error', 'Restoran adı tələb olunur.');
            $this->redirect('/admin/settings');
        }

        try {
            $logo = Setting::get('logo', '');
            if (!empty($_FILES['logo']['name'])) {
                $newLogo = Uploader::store($_FILES['logo'], 'settings');
                Uploader::delete($logo ?: null);
                $logo = $newLogo;
            }
            if (!empty($_POST['remove_logo'])) {
                Uploader::delete($logo ?: null);
                $logo = '';
            }

            Setting::setMany([
                'restaurant_name' => $name,
                'phone' => $phone,
                'address' => $address,
                'working_hours' => $hours,
                'about' => $about,
                'logo' => $logo,
            ]);

            clear_old();
            flash('success', 'Parametrlər yadda saxlanıldı.');
            $this->redirect('/admin/settings');
        } catch (RuntimeException $e) {
            flash('error', $e->getMessage());
            $this->redirect('/admin/settings');
        }
    }
}
