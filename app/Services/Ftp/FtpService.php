<?php


namespace App\Services\Ftp;

use Illuminate\Support\Facades\Storage;

class FtpService implements FtpServiceInterface
{

    /**
     * @param $file
     * @return string
     */
    public function uploadFile($file): string
    {
        $fileName = $file->hashName();

        Storage::disk('ftp')->put($fileName, fopen($file, 'r+'));
        return 'https://' . env('FTP_HOST') . '/' . $fileName;
    }
}
