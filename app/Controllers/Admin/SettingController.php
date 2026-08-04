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
        $aboutRu = trim((string) ($_POST['about_ru'] ?? ''));
        $aboutEn = trim((string) ($_POST['about_en'] ?? ''));
        $heritageStory = trim((string) ($_POST['heritage_story'] ?? ''));
        $heritageStoryRu = trim((string) ($_POST['heritage_story_ru'] ?? ''));
        $heritageStoryEn = trim((string) ($_POST['heritage_story_en'] ?? ''));
        $tagline = trim((string) ($_POST['tagline'] ?? ''));
        $taglineRu = trim((string) ($_POST['tagline_ru'] ?? ''));
        $taglineEn = trim((string) ($_POST['tagline_en'] ?? ''));

        store_old([
            'restaurant_name' => $name,
            'phone' => $phone,
            'address' => $address,
            'working_hours' => $hours,
            'about' => $about,
            'about_ru' => $aboutRu,
            'about_en' => $aboutEn,
            'heritage_story' => $heritageStory,
            'heritage_story_ru' => $heritageStoryRu,
            'heritage_story_en' => $heritageStoryEn,
            'tagline' => $tagline,
            'tagline_ru' => $taglineRu,
            'tagline_en' => $taglineEn,
        ]);

        if ($name === '') {
            flash('error', 'Restoran adı tələb olunur.');
            $this->redirect('/admin/settings');
        }

        try {
            $logo = Setting::get('logo', '') ?? '';
            if (!empty($_FILES['logo']['name'])) {
                $newLogo = Uploader::store($_FILES['logo'], 'settings');
                Uploader::delete($logo !== '' ? $logo : null);
                $logo = $newLogo;
            }
            if (!empty($_POST['remove_logo'])) {
                Uploader::delete($logo !== '' ? $logo : null);
                $logo = '';
            }

            $heroVideo = Setting::get('hero_video', '') ?? '';
            if (!empty($_FILES['hero_video']['name'])) {
                $newVideo = Uploader::storeVideo($_FILES['hero_video'], 'settings');
                Uploader::delete($heroVideo !== '' ? $heroVideo : null);
                $heroVideo = $newVideo;
            }
            if (!empty($_POST['remove_hero_video'])) {
                Uploader::delete($heroVideo !== '' ? $heroVideo : null);
                $heroVideo = '';
            }

            $heroPoster = Setting::get('hero_poster', '') ?? '';
            if (!empty($_FILES['hero_poster']['name'])) {
                $newPoster = Uploader::store($_FILES['hero_poster'], 'settings');
                Uploader::delete($heroPoster !== '' ? $heroPoster : null);
                $heroPoster = $newPoster;
            }
            if (!empty($_POST['remove_hero_poster'])) {
                Uploader::delete($heroPoster !== '' ? $heroPoster : null);
                $heroPoster = '';
            }

            Setting::setMany([
                'restaurant_name' => $name,
                'phone' => $phone,
                'address' => $address,
                'working_hours' => $hours,
                'about' => $about,
                'about_ru' => $aboutRu,
                'about_en' => $aboutEn,
                'heritage_story' => $heritageStory,
                'heritage_story_ru' => $heritageStoryRu,
                'heritage_story_en' => $heritageStoryEn,
                'tagline' => $tagline,
                'tagline_ru' => $taglineRu,
                'tagline_en' => $taglineEn,
                'logo' => $logo,
                'hero_video' => $heroVideo,
                'hero_poster' => $heroPoster,
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
