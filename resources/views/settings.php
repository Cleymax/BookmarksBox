<?php

use App\Security\Auth;

?>
<div class="settings">
    <form method="post">
        <label for="mail" class="textfield">
            <input type="email" id="mail" autocomplete="email" name="email" spellcheck="false"
                   tabindex="1" aria-label="E-mail" required aria-required="true"
                   title="E-mail" autocapitalize="none" dir="ltr" autofocus value="<?= $data->email; ?>">
            <span>E-mail</span>
        </label>
        <label for="username" class="textfield">
            <input type="text" id="username" autocomplete="username" name="username" spellcheck="false"
                   tabindex="2" aria-label="Nom d'utilisateur" required aria-required="true"
                   title="Nom d'utilisateur"
                   autocapitalize="none" dir="ltr" value="<?= $data->username; ?>">
            <span aria-hidden="true">Nom d'utilisateur</span>
        </label>
        <label for="password" class="textfield">
            <input type="password" id="password" required aria-required="true" spellcheck="false"
                   autocomplete="current-password" aria-label="Mot de passe" autocapitalize="off" dir="ltr"
                   minlength="6" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"
                   title="Le mot de passe doit faire 6  carractères avec une majuscule et un chiffre"
                   name="password">
            <span aria-hidden="true">Mot de passe</span>
        </label>
        <label for="confirm" class="textfield">
            <input type="password" id="confirm"  required aria-required="true" tabindex="4" spellcheck="false"
                   autocomplete="current-password" aria-label="Confirmer votre mot de passe" autocapitalize="off" dir="ltr"
                   minlength="6" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Les 2 mots de passe doivent être identiques" >
            <span aria-hidden="true">Confirmer</span>
        </label>
        <label for="confirm" class="textfield">
            <input type="password" id="current"  required aria-required="true" tabindex="4" spellcheck="false"
                   autocomplete="current-password" aria-label="Confirmer votre mot de passe" autocapitalize="off" dir="ltr"
                   minlength="6" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Les 2 mots de passe doivent être identiques" >
            <span aria-hidden="true">Mot de passe Actuelle</span>
        </label>
        <label for="bio" class="textfield">
            <input type="text" id="bio" name="bio" spellcheck="false"
                   tabindex="7" aria-label="Bbio" required aria-required="true"
                   title="biographie" autocapitalize="none" dir="ltr" autofocus value="<?= $data->bio;?>">
            <span>Biographie</span>
        </label>
        <button class="btn" aria-label="Connecion" name="login" value="standard" title="Connexion"><span class="material-icons">login</span>Connexion</button>
    </form>
</div>
