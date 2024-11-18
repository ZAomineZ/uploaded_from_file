# Laravel Upload Image from URL

Ce projet Laravel contient une fonctionnalité pour uploader une image directement à partir d'une URL en utilisant une classe personnalisée.

## Prérequis

- Laravel 10 ou supérieur
- PHP 8.1 ou supérieur
- Une configuration de stockage correctement configurée (par exemple, le stockage local ou S3)

## Installation

1. Clonez ce dépôt et installez les dépendances :
   ```bash
   git clone https://github.com/ZAomineZ/uploaded_from_file.git
   cd uploaded_from_file
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

2. Configurez votre environnement dans le fichier .env :
    ```bash
   FILESYSTEM_DRIVER=public
   ```
3. Assurez-vous que le dossier storage est lié au dossier public :
    ```bash
   php artisan storage:link
   ```
---

## Mise en œuvre

### Étape 1 : Ajouter la classe `UploadedFileFromUrl`

Ajoutez la classe suivante dans votre projet, par exemple dans le dossier `app/Support/UploadedFileFromUrl.php` :

```php
namespace App\Support;

use RuntimeException;
use Illuminate\Http\UploadedFile;

final class UploadedFileFromUrl extends UploadedFile
{
    public static function fromUrl(
        string $url,
        string $originalFileName,
        ?string $mimeType = null,
        ?int $error = UPLOAD_ERR_OK,
        bool $test = false
    ): self {
        if (!$stream = @fopen($url, 'r')) {
            throw new RuntimeException("Unable to open URL: $url");
        }

        $file = tempnam(sys_get_temp_dir(), 'uploaded-file-');

        file_put_contents($file, $stream);

        return new self($file, $originalFileName, $mimeType, $error, $test);
    }
}
```
### Étape 2 : Utiliser la classe pour uploader une image

Dans un contrôleur ou une commande, utilisez la classe comme suit :

```php
use App\Support\UploadedFileFromUrl;

class ImageController extends Controller
{
    public function uploadFromUrl()
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
```

### Étape 3 : Ajouter une route

Ajoutez une route dans routes/web.php pour appeler cette méthode :

```php
use App\Http\Controllers\ImageController;

Route::get('/upload-from-url', [ImageController::class, 'uploadFromUrl']);
```

---

## Tester la fonctionnalité

1. Démarrez votre serveur de développement Laravel :

   ```bash
   php artisan serve
   ```

2. Accédez à l'URL suivante dans votre navigateur ou un outil comme Postman :
    
    ```bash
   http://localhost:8000/upload-from-url
   ```
   Vous recevrez une réponse JSON contenant le chemin et l'URL du fichier téléchargé.

---

## Notes

- Assurez-vous que le dossier de stockage configuré est accessible en écriture.
- La classe `UploadedFileFromUrl` utilise un fichier temporaire pour stocker l'image téléchargée avant de la sauvegarder dans le système de fichiers.
