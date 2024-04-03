<?php

namespace App\Traits;

use Intervention\Image\Facades\Image;

trait uploads
{
    /**
     * @param $file
     * @param $dir
     * @return string
     */
    function upload($file, $dir): string
    {
        $file_name = uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());

        $path = storage_path('app/public/uploads/' . $dir);

        if (!file_exists($path)) {
            if (!file_exists(storage_path('app/public/uploads'))) {
                mkdir(storage_path('app/public/uploads'), 0777);
            }
            mkdir($path, 0777);
        }

        Image::make($file)->save($path . '/' . $file_name, 40, 'webp');

        return $dir . '/' . $file_name;
    }
}
