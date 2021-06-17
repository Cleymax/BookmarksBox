<?php

namespace App\Services;

use App\Database\Query;
use App\Exceptions\FileUploadException;
use App\Security\Auth;
use App\Tools\Str;

class FileUploader
{
    /**
     * @throws \App\Exceptions\FileUploadException|
     */
    public static function getFileUpload(string $name, array $ext_accepts = ["png", "jpg", "jpeg"], int $max_size = 500000): array
    {
        $file = $_FILES[$name];

        if($file['error'] == 4){
            return [];
        }

        $name = htmlspecialchars($file['name']);
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $new_name = Str::random(30) . ".$ext";
        $target_dir = $_ENV['UPLOAD_FOLDER'];
        $target_file = $target_dir . $new_name;

        if ($file["size"] > $max_size) {
            throw new FileUploadException("Fichier trop grand !");
        }

        if (!in_array($ext, $ext_accepts)) {
            throw new FileUploadException("Mauvaise extension !");
        }

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return [
                'uri' => $_ENV['UPLOAD_FOLDER_URI'] . '/' . $new_name,
                'name' => $new_name,
                'path' => $target_file
            ];
        } else {
            throw new FileUploadException();
        }
    }

    public static function getSrc($file_name): string
    {
        if (Str::startsWith('https', $file_name)) {
            return $file_name;
        } else {
            return $_ENV['UPLOAD_FOLDER_URI'] . '/' . $file_name;
        }
    }

    public static function getSrcAvatar()
    {
        $query = (new Query())
            ->select('avatar')
            ->from('users')
            ->where('id = ?')
            ->params([Auth::user()->id]);
        return self::getSrc($query->first()->avatar);
    }
}
