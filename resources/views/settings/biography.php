<div class="settings-container">
    <h3>Changez votre biographie</h3>
    <form method="post">
        <?= \App\Services\CsrfService::html() ?>
        <label for="bio" class="textareafield">
            <textarea type="text" id="bio" name="bio" spellcheck="false"
                      tabindex="1" aria-label="bio" required aria-required="true"
                      title="biographie" autocapitalize="none" dir="ltr" autofocus></textarea>
            <span>Biographie</span>
        </label>
        <label for="current" class="textfield" data-children-count="1">
            <input type="password" id="current" name="current" tabindex="2" spellcheck="false" autocomplete="current-password" aria-label="Confirmer votre mot de passe" autocapitalize="off" dir="ltr" minlength="6" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Les 2 mots de passe doivent être identiques" data-kwimpalastatus="alive" data-kwimpalaid="1622245737254-0">
            <span aria-hidden="true">Mot de passe</span>
        </label>
        <button class="btn" aria-label="Enregistrer" title="Enregistrer" style="
    			width: 250px;
		">
            <span class="material-icons">save</span>Enregistrer
        </button>
    </form>
</div>
