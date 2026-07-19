<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageUploadService
{
    public function uploadImage(UploadedFile $file, string $directory): string
    {
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '.' . $extension;
        $file->move($directory, $fileName);
        return $directory . $fileName;
    }
}
