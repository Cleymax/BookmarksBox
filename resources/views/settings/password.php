<div class="settings-container">
    <h3>Changez votre mot de passe</h3>
    <form method="post">
        <?= \App\Services\CsrfService::html() ?>
        <label for="password" class="textfield" data-children-count="1">
            <input type="password" id="password" spellcheck="false" required aria-required="true" tabindex="1" aria-label="Mot de passe" autocapitalize="off" dir="ltr" minlength="6" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="Le mot de passe doit faire 6  carractères avec une majuscule et un chiffre" name="password" data-kwimpalastatus="alive" data-kwimpalaid="1622245737254-1">
            <span aria-hidden="true">Nouveau mot de passe</span>
        </label>
        <label for="confirm" class="textfield" data-children-count="1">
            <input type="password" id="confirm" required aria-required="true" tabindex="2" spellcheck="false" name="confirm" aria-label="Confirmer votre mot de passe" autocapitalize="off" dir="ltr" minlength="6" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Les 2 mots de passe doivent être identiques" data-kwimpalastatus="alive" data-kwimpalaid="1622245737254-2">
            <span aria-hidden="true">Confirmer votre mot de passe</span>
        </label>
        <label for="current" class="textfield" data-children-count="1">
            <input type="password" id="current" name="current" required aria-required="true" tabindex="3" spellcheck="false" autocomplete="current-password" aria-label="Confirmer votre mot de passe" autocapitalize="off" dir="ltr" minlength="6" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Les 2 mots de passe doivent être identiques" data-kwimpalastatus="alive" data-kwimpalaid="1622245737254-0">
            <span aria-hidden="true">Mot de passe actuelle</span>
        </label>
        <button class="btn" aria-label="Enregistrer" title="Enregistrer" style="
    			width: 250px;
		">
            <span class="material-icons">save</span>Enregistrer
        </button>
    </form>
</div>
