    <div class="container card">
        <h1>Se connecter</h1>
        <form method="post">
            <?php
            if(isset($_GET['redirect_to'])){
                echo '<input type="hidden" name="redirect_to" value="'.htmlspecialchars($_GET['redirect_to']).'">';
            }
            ?>
           <?= \App\Services\CsrfService::html() ?>
            <div class="form-group">
                <div class="input-auth">
                    <label for="username" class="textfield">
                        <input type="text" id="username" autocomplete="username" name="username" spellcheck="false"
                               aria-label="Nom d'utilisateur ou adresse e-mail" required aria-required="true"
                               title="Nom d'utilisateur ou adresse e-mail"
                               autocapitalize="none" dir="ltr" <?php if(isset($username) && $username != ''){ echo 'value="'. $username.'"'; }else{echo 'autofocus';} ?>>
                        <span aria-hidden="true">Nom d'utilisateur ou adresse e-mail</span>
                    </label>
                </div>
                <div class="input-auth">
                    <label for="password" class="textfield">
                        <input type="password" id="password" required aria-required="true" spellcheck="false"
                               autocomplete="current-password" aria-label="Mot de passe" autocapitalize="off" dir="ltr"
                               minlength="6" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"
                               title="Le mot de passe doit faire 6  carractères avec une majuscule et un chiffre"
                                <?php if(isset($username) && $username != ''){ echo 'autofocus'; } ?>
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
                    <div style="margin-top: 10px">
                        <label for="remember" class="switch" style="margin-right: 10px">
                            <input type="checkbox" id="remember" name="remember" aria-labelledby="remember-label">
                            <span class="slider round"></span>
                        </label>
                        <span id="remember-label" aria-hidden="true">Se souvenir de moi</span>
                    </div>
                </div>
                <div class="btn-container">
                    <div class="button">
                        <button class="btn" aria-label="Connexion" name="login" value="standard" title="Connexion"><span class="material-icons">login</span>Connexion</button>
                    </div>
                    <div class="line-separator"></div>
                    <div class="button">
                        <button class="btn btn-yellow" aria-label="IUT d'Annecy"  name="login"  value="iutannecy" title="Connexion avec votre compte de l'IUT d'Annecy"><span class="material-icons">school</span>IUT d'Annecy
                        </button>
                    </div>
                    <div class="line-separator"></div>
                    <a href="<?= get_query_url('password-forgot')?>">
                        <span data-content="Mot de passe oublié">Mot de passe oublié</span>
                    </a>
                </div>
            </div>
        </form>
    </div>
