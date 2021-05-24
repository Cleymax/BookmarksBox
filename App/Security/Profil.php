<?php


namespace App\Security;


use App\Database\Query;
use App\Services\FlashService;

class Profil
{
    public static function edit(string $mail, string $username, string $password, string $confirm, string $bio): bool
    {
        echo "Profil";

        if ($_POST['password'] != $_POST['confirm']) {
            FlashService::error("Votre mot de passe n'est pas identique");
        }

        preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/', $password, $matches, PREG_OFFSET_CAPTURE);
        if (empty($matches)) {
            throw new AuthException('Votre mot de passe doit faire 6 caractères avec un chiffre et une majuscule !');
        }else {
            $query = (new Query())
                ->select("id", "email", "username", "bio", "password")
                ->from("users")
                ->where("email = ? or username = ?")
                ->limit(1)
                ->params([$mail, $username]);

            $response = $query->first();
            $count = $query->rowCount();

            $password_encrypt = password_hash($_ENV['SALT'] . $password, PASSWORD_BCRYPT);

            if ($count == 1) {
                throw new AuthException('Ce mail ou ce nom d\' utilisateur est déjà utilisé');
            }

            if($password_encrypt == $response->password){
                throw new AuthException('Vous ne pouvez pas modifie votre mot de passe car celui-ci est identique au mot de passe actuelle');
            }

            if($bio == $response->bio){
                throw new AuthException('Vous ne pouvez pas modifie votre biographie car elle est identique à celle actuelle');
            }

            $query = (new Query())
                ->insert("email", "username", "password", "bio")
                ->into("users")
                ->values(["?", "?", "?", "?"])
                ->params([$mail, $username, $password_encrypt, $bio])
                ->returning('id');

            $response = $query->first();

            return true;

        }

    }
}
