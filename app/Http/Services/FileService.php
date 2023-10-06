<?php

namespace App\Http\Services;

use Illuminate\Http\UploadedFile;

class FileService {

    public static function upload(UploadedFile $file, $dir = ''): string {
        $name = "$dir/" . time() . '.' . $file->extension();
        $file->move("storage/$dir/", $name);
        return $name;
    }
}
