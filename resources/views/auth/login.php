    <div class="container card">
        <h1>Se connecter</h1>
        <form method="post">
            <div class="form-group">
                <div class="input-auth">
                    <label for="username" class="textfield">
                        <input type="text" id="username" autocomplete="username" name="username" spellcheck="false"
                               aria-label="Nom d'utilisateur ou adresse e-mail" required aria-required="true"
                               title="Nom d'utilisateur ou adresse e-mail"
                               autocapitalize="none" dir="ltr" autofocus>
                        <span aria-hidden="true">Nom d'utilisateur ou adresse e-mail</span>
                    </label>
                </div>
                <div class="input-auth">
                    <label for="password" class="textfield">
                        <input type="password" id="password" required aria-required="true" spellcheck="false"
                               autocomplete="current-password" aria-label="Mot de passe" autocapitalize="off" dir="ltr"
                               minlength="6" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"
                               title="Le mot de passe doit faire 6  carractères avec une majuscule et un chiffre"
                               name="password">
                        <span aria-hidden="true">Mot de passe</span>
                    </label>
                    <div style="margin-top: 10px">
                        <label for="show-password" class="switch" style="margin-right: 10px">
                            <input type="checkbox" id="show-password" aria-labelledby="afficher-passwd">
                            <span class="slider round"></span>
                        </label>
                        <span id="afficher-passwd" aria-hidden="true">Afficher le mot de passe</span>
                    </div>
                </div>
                <div class="btn-container">
                    <div class="button">
                        <button class="btn" aria-label="Connecion" title="Connexion"><span class="material-icons">login</span>Connexion</button>
                    </div>
                    <div class="line-separator"></div>
                    <div class="button">
                        <button class="btn btn-yellow" aria-label="IUT d'Annecy" title="Connexion avec votre compte de l'IUT d'Annecy"><span class="material-icons">school</span>IUT d'Annecy
                        </button>
                    </div>
                    <div class="line-separator"></div>
                    <a href="<?= $_ENV['BASE_URL'] .'/auth/password-forgot'?>">
                        <span data-content="Mot de passe oublié">Mot de passe oublié</span>
                    </a>
                </div>
            </div>
        </form>
    </div>