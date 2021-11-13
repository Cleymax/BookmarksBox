<?php

use App\Services\CsrfService;

?>
<div class="settings-container">
    <h3>Changez votre email</h3>
    <form method="post">
        <?= CsrfService::html() ?>
        <label for="mail" class="textfield">
            <input type="email" id="mail" autocomplete="email" name="email" spellcheck="false"
                   tabindex="1" aria-label="E-mail" required aria-required="true"
                   title="E-mail" autocapitalize="none" dir="ltr" autofocus" <?php if (isset($data->email)){
                echo 'value="'.$data->email.'"';
            } ?>>
            <span>E-mail</span>
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
