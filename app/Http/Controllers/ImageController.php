<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\UploadedFileFromUrl;
use Illuminate\Http\JsonResponse;

final class ImageController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $url = 'https://placehold.it/300x300';
        $fileName = 'placeholder-image.jpg';

        // Créer un fichier UploadedFile à partir de l'URL
        $uploadedFile = UploadedFileFromUrl::fromUrl($url, $fileName);

        // Sauvegarder le fichier dans le système de stockage configuré
        $path = $uploadedFile->store('public/placeholders');

        return response()->json([
            'message' => 'File uploaded successfully',
            'path' => $path,
            'url' => asset("storage/placeholders/" . basename($path)),
        ]);
    }
}
