<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Setting;
use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\Output\QRMarkupSVG;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

final class QrController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $menuUrl = base_url();
        $options = new QROptions([
            'outputInterface' => QRMarkupSVG::class,
            'outputBase64' => false,
            'scale' => 8,
        ]);
        $qrSvg = (new QRCode($options))->render($menuUrl);

        $this->render('admin/qr/index', [
            'title' => 'QR kod',
            'menuUrl' => $menuUrl,
            'qrSvg' => $qrSvg,
            'settings' => Setting::all(),
        ], 'admin/layouts/app');
    }

    public function download(): void
    {
        $this->requireAuth();

        $menuUrl = base_url();
        $options = new QROptions([
            'outputInterface' => QRGdImagePNG::class,
            'outputBase64' => false,
            'scale' => 12,
        ]);

        $png = (new QRCode($options))->render($menuUrl);

        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="vip-karvan-menu-qr.png"');
        header('Content-Length: ' . strlen($png));
        echo $png;
        exit;
    }
}
