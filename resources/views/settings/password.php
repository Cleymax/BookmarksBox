<?php

use App\Services\CsrfService;

?>
<div class="settings-container">
    <h3>Changez votre mot de passe</h3>
    <?php
    if ($user->password == 'CAS') {
        ?>
            <div class="alert alert-danger">
                <svg x="0px" y="0px" viewBox="0 0 507.2 507.2">
                    <circle style="fill:#F15249;" cx="253.6" cy="253.6" r="253.6"/>
                    <path style="fill:#AD0E0E;" d="M147.2,368L284,504.8c115.2-13.6,206.4-104,220.8-219.2L367.2,148L147.2,368z"/>
                    <path style="fill:#FFFFFF;" d="M373.6,309.6c11.2,11.2,11.2,30.4,0,41.6l-22.4,22.4c-11.2,11.2-30.4,11.2-41.6,0l-176-176c-11.2-11.2-11.2-30.4,0-41.6l23.2-23.2c11.2-11.2,30.4-11.2,41.6,0L373.6,309.6z"/>
                    <path style="fill:#D6D6D6;" d="M280.8,216L216,280.8l93.6,92.8c11.2,11.2,30.4,11.2,41.6,0l23.2-23.2c11.2-11.2,11.2-30.4,0-41.6L280.8,216z"/>
                    <path style="fill:#FFFFFF;" d="M309.6,133.6c11.2-11.2,30.4-11.2,41.6,0l23.2,23.2c11.2,11.2,11.2,30.4,0,41.6L197.6,373.6c-11.2,11.2-30.4,11.2-41.6,0l-22.4-22.4c-11.2-11.2-11.2-30.4,0-41.6L309.6,133.6z"/>
                </svg>
                Vous vous êtes connecté en CAS. Vous ne pouvez pas modifier votre mot de passe.
            </div>
        <?php

    } else {
        ?>
        <form method="post">
            <?= CsrfService::html() ?>
            <label for="password" class="textfield" data-children-count="1">
                <input type="password" id="password" spellcheck="false" required aria-required="true" tabindex="1"
                       aria-label="Mot de passe" autocapitalize="off" dir="ltr" minlength="6"
                       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"
                       title="Le mot de passe doit faire 6  carractères avec une majuscule et un chiffre"
                       name="password" data-kwimpalastatus="alive" data-kwimpalaid="1622245737254-1">
                <span aria-hidden="true">Nouveau mot de passe</span>
            </label>
            <label for="confirm" class="textfield" data-children-count="1">
                <input type="password" id="confirm" required aria-required="true" tabindex="2" spellcheck="false"
                       name="confirm" aria-label="Confirmer votre mot de passe" autocapitalize="off" dir="ltr"
                       minlength="6" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                       title="Les 2 mots de passe doivent être identiques" data-kwimpalastatus="alive"
                       data-kwimpalaid="1622245737254-2">
                <span aria-hidden="true">Confirmer votre mot de passe</span>
            </label>
            <label for="current" class="textfield" data-children-count="1">
                <input type="password" id="current" name="current" required aria-required="true" tabindex="3"
                       spellcheck="false" autocomplete="current-password" aria-label="Confirmer votre mot de passe"
                       autocapitalize="off" dir="ltr" minlength="6" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                       title="Les 2 mots de passe doivent être identiques" data-kwimpalastatus="alive"
                       data-kwimpalaid="1622245737254-0">
                <span aria-hidden="true">Mot de passe actuelle</span>
            </label>
            <button class="btn" aria-label="Enregistrer" title="Enregistrer" style="
    			width: 250px;
		">
                <span class="material-icons">save</span>Enregistrer
            </button>
        </form>
        <?php
    }
    ?>
</div>
