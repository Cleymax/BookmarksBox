<?php

namespace App\Helper;

class BookmarkHelper
{
    public static function translateDifficulty(string $key): string
    {
        $diffilculty = [
            'EASY' => 'facile',
            'MEDIUM' => 'moyens',
            'PRO' => 'difficile'
        ];

        return $diffilculty[$key];
    }
}
