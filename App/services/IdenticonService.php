<?php

namespace App\Services;

use App\Tools\Str;
use Bitverse\Identicon\Color\Color;
use Bitverse\Identicon\Generator\RingsGenerator;
use Bitverse\Identicon\Identicon;
use Bitverse\Identicon\Preprocessor\MD5Preprocessor;

class IdenticonService
{
    /**
     * @throws \Bitverse\Identicon\Color\WrongColorFormatException
     */
    public static function generate(string $name = 'hello world')
    {
        $generator = new RingsGenerator();
        $generator->setBackgroundColor(Color::parseHex(self::rand_color()));

        $identicon = new Identicon(new MD5Preprocessor(), $generator);

        $icon = $identicon->getIcon($name);

        $new_name = Str::random(30) . ".svg";
        $target_file = $_ENV['UPLOAD_FOLDER'] . $new_name;
        file_put_contents($target_file, $icon);

        return [
            'uri' => $_ENV['UPLOAD_FOLDER_URI'] . '/' . $new_name,
            'name' => $new_name,
            'path' => $target_file
        ];
    }

    public static function rand_color(): string
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }
}
