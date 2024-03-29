<?php

use App\Services\CsrfService;

?>
<div class="settings-container">
    <h3>Changez votre nom, prénom ou votre nom d'utilisateur</h3>
    <form method="post">
        <?= CsrfService::html() ?>
        <label for="last_name" class="textfield">
            <input type="text" id="last_name" name="last_name" spellcheck="false"
                   tabindex="1" aria-label="Nom"
                   title="Nom" autocapitalize="none" dir="ltr"
                   autofocus" <?php if (isset($data->last_name)){
                echo 'value="'.$data->last_name.'"';
            } ?>>
            <span>Nom</span>
        </label>
        <label for="first_name" class="textfield">
            <input type="text" id="first_name" name="first_name" spellcheck="false"
                   tabindex="1" aria-label="Prenom"
                   title="Prenom" autocapitalize="none" dir="ltr" autofocus" <?php if (isset($data->first_name)){
                echo 'value="'.$data->first_name.'"';
            } ?>>
            <span>Prénom</span>
        </label>
        <label for="username" class="textfield">
            <input type="text" id="username" name="username" spellcheck="false"
                   tabindex="1" aria-label="Pseudonyme"
                   title="Pseudonyme" autocapitalize="none" dir="ltr" autofocus" <?php if (isset($data->username)){
                echo 'value="'.$data->username.'"';
            } ?>>
            <span>Nom d'utilisateur</span>
        </label>
        <?php
        if (isset($cas) && !$cas) {
            ?>
            <label for="current" class="textfield" data-children-count="1">
                <input type="password" id="current" name="current" required aria-required="true" tabindex="2"
                       spellcheck="false" autocomplete="current-password" aria-label="Confirmer votre mot de passe"
                       autocapitalize="off" dir="ltr" minlength="6" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                       title="Les 2 mots de passe doivent être identiques" data-kwimpalastatus="alive"
                       data-kwimpalaid="1622245737254-0">
                <span aria-hidden="true">Mot de passe</span>
            </label>
            <?php
        }
        ?>
        <button class="btn" aria-label="Enregistrer" title="Enregistrer" style="
    			width: 250px;
		">
            <span class="material-icons">save</span>Enregistrer
        </button>
    </form>
</div>

