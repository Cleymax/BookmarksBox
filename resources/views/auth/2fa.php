    <div class="container card">
        <h1>2FA</h1>
        <form method="post">
            <div class="form-group">
                <div class="input-auth">
                    <p class="label-2fa">Entrez votre code de double authentification.</p>
                    <label for="code" class="textfield">
                        <input type="tel" pattern="[0-9]{6}" minlength="6" maxlength="6" id="code" tabindex="0"
                               spellcheck="false" aria-label="Saisir le code" name="code" autocapitalize="off" autofocus
                               required aria-required="true" dir="ltr" title="Entre votre code 2FA">
                        <span>Saisir le code</span>
                    </label>
                </div>
                <div class="btn-2fa">
                    <div class="btn-font">
                        <input type="submit" value="Connexion" class="btn normal">
                    </div>
                </div>
            </div>
        </form>
    </div>
