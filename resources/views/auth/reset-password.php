<div class="container card">
    <h1>Réinitialisation de mot de passe</h1>
    <form method="post">
        <?= \App\Services\CsrfService::html() ?>
        <div class="form-group">
            <div class="input-group">
                <div class="input-register">
                    <label for="password" class="textfield">
                        <input type="password" id="password" name="password" required aria-required="true"
                               spellcheck="false"
                               autocomplete="current-password" aria-label="Mot de passe" autocapitalize="off" dir="ltr"
                               minlength="6" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"
                               title="Le mot de passe doit faire 6 carractères avec une majuscule et un chiffre">
                        <span aria-hidden="true">Nouveau mot de passe</span>
                    </label>
                </div>
                <div class="input-register">
                    <label for="confirm" class="textfield">
                        <input type="password" id="confirm" name="confirm" required aria-required="true"
                               spellcheck="false"
                               autocomplete="current-password" aria-label="Confirmer votre mot de passe"
                               autocapitalize="off" dir="ltr"
                               minlength="6" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                               title="Les 2 mots de passe doivent être identiques">
                        <span aria-hidden="true">Confirmer</span>
                    </label>

                </div>
                <div class="input-show-password">
                    <label for="show-password" class="switch" style="margin-right: 10px">
                        <input type="checkbox" id="show-password" aria-labelledby="afficher-passwd">
                        <span class="slider round"></span>
                    </label>
                    <span id="afficher-passwd" aria-hidden="true">Afficher le mot de passe</span>
                </div>
            </div>
            <div class="btn-register">
                <div class="button">
                    <button class="btn"><span class="material-icons">arrow_right</span>Continuer</button>
                </div>
            </div>
        </div>
    </form>
</div>
