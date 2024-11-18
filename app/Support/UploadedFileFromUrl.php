<?php

declare(strict_types=1);

namespace App\Support;

use http\Exception\RuntimeException;
use Illuminate\Http\UploadedFile;

final class UploadedFileFromUrl extends UploadedFile
{
    public static function fromUrl(
        string $url,
        string $originalFileName,
        ?string $mimeType = null,
        ?int $error = UPLOAD_ERR_OK,
        bool $test = false
    ): self
    {
        if (!$stream = @fopen($url, 'r')) {
            throw new RuntimeException($url);
        }

        $file = tempnam(sys_get_temp_dir(), 'uploaded-file-');

        file_put_contents($file, $stream);

        return new self($file, $originalFileName, $mimeType, $error, $test);
    }
}
