<div class="settings-container">
    <h3>Changez votre nom, prénom ou votre nom d'utilisateur</h3>
    <form method="post">
        <?= \App\Services\CsrfService::html() ?>
        <label for="lastname" class="textfield">
            <input type="text" id="lastname" name="lastname" spellcheck="false"
                   tabindex="1" aria-label="Nom"
                   title="Nom" autocapitalize="none" dir="ltr" autofocus">
            <span>Nom</span>
        </label>
        <label for="firstname" class="textfield">
            <input type="text" id="firstname" name="firstname" spellcheck="false"
                   tabindex="1" aria-label="Prenom"
                   title="Prenom" autocapitalize="none" dir="ltr" autofocus">
            <span>Prénom</span>
        </label>
        <label for="username" class="textfield">
            <input type="text" id="username" name="username" spellcheck="false"
                   tabindex="1" aria-label="Pseudonyme"
                   title="Pseudonyme" autocapitalize="none" dir="ltr" autofocus">
            <span>Nom d'utilisateur</span>
        </label>
        <label for="current" class="textfield" data-children-count="1">
            <input type="password" id="current" name="current" required aria-required="true" tabindex="2" spellcheck="false" autocomplete="current-password" aria-label="Confirmer votre mot de passe" autocapitalize="off" dir="ltr" minlength="6" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Les 2 mots de passe doivent être identiques" data-kwimpalastatus="alive" data-kwimpalaid="1622245737254-0">
            <span aria-hidden="true">Mot de passe</span>
        </label>
        <button class="btn" aria-label="Enregistrer" title="Enregistrer" style="
    			width: 250px;
		">
            <span class="material-icons">save</span>Enregistrer
        </button>
    </form>
</div>

