<?php

namespace App\Traits;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

trait CompressesImages
{
    private function compressImage($file, int $maxWidth = 1200, int $quality = 75): string
    {
        $manager = new ImageManager(new Driver());

        $image = $manager->read($file->getRealPath());

        $image->scaleDown(width: $maxWidth);

        $extension = strtolower($file->getClientOriginalExtension());

        $tempDir = storage_path('app/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        if ($extension === 'png') {
            $tempPath = $tempDir . '/' . uniqid() . '.png';
            $image->toPng()->save($tempPath);
        } else {
            $tempPath = $tempDir . '/' . uniqid() . '.jpg';
            $image->toJpeg($quality)->save($tempPath);
        }

        return $tempPath;
    }
}
