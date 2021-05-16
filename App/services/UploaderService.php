<?php

namespace App\Services;

use App\Tools\Str;

class UploaderService
{
    /**
     * @throws \Exception
     */
    public static function upload(array $files, bool $multiple = false, array $options = []): bool
    {
        $files_count = count($files['name']);

        if ($files_count == 0 && !$multiple) {
            throw new \InvalidArgumentException("Seulement un seul fichier est autorisé !");
        }

        for ($i = 0; $i < $files_count; $i++) {
            if ($files['error'][$i] == UPLOAD_ERR_OK) {
                $name = $files['name'][$i];
                $tmp_name = $files['tmp_name'][$i];
                $new_file_name = Str::random();
                $ext = pathinfo($name)['extension'];
                $new_path = ROOT_PATH . '/../upload/' . $new_file_name . '.' . $ext;

                if (isset($options['type']) && !empty($options['type'])) {
                    if (!in_array($files['type'][$i], $options['type'])) {
                        throw new \InvalidArgumentException('Extension du fichier non autorisé');
                    }
                }

                if (isset($options['size'])) {
                    if ($files['size'][$i] > $options['size']) {
                        throw new \Exception('Exceeded file size limit.');
                    }
                }

                return move_uploaded_file($tmp_name, $new_path);
            }
        }
    }
}
