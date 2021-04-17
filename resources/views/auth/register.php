    <div class="container card">
        <h1>S'inscrire</h1>
        <form method="post">
            <div class="form-group">
                <div class="input-register">
                    <label for="mail" class="textfield">
                        <input type="email" id="mail" autocomplete="email" name="email" spellcheck="false"
                               tabindex="1" aria-label="E-mail" required aria-required="true"
                               title="E-amil" autocapitalize="none" dir="ltr" autofocus>
                        <span>E-mail</span>
                    </label>
                </div>
                <div class="input-register">
                    <label for="username" class="textfield">
                        <input type="text" id="username" autocomplete="username" name="username" spellcheck="false"
                               tabindex="2" aria-label="Nom d'utilisateur" required aria-required="true"
                               title="Nom d'utilisateur"
                               autocapitalize="none" dir="ltr">
                        <span aria-hidden="true">Nom d'utilisateur</span>
                    </label>
                </div>
                <div class="input-group">
                    <div class="input-register">
                        <label for="password" class="textfield" >
                            <input type="password" id="password"  required aria-required="true" tabindex="3" spellcheck="false"
                                   autocomplete="current-password" aria-label="Mot de passe" autocapitalize="off" dir="ltr"
                                   minlength="6" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="Le mot de passe doit faire 6 carractères avec une majuscule et un chiffre" >
                            <span aria-hidden="true">Mot de passe</span>
                        </label>
                    </div>
                    <div class="input-register">
                        <label for="confirm" class="textfield">
                            <input type="password" id="confirm"  required aria-required="true" tabindex="4" spellcheck="false"
                                   autocomplete="current-password" aria-label="Confirmer votre mot de passe" autocapitalize="off" dir="ltr"
                                   minlength="6" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Les 2 mots de passe doivent être identiques" >
                            <span aria-hidden="true">Confirmer</span>
                        </label>

                    </div>
                    <div class="input-show-password">
                        <label for="show-password" class="switch" style="margin-right: 10px">
                            <input type="checkbox" id="show-password" onclick="show_password()" tabindex="5" aria-labelledby="afficher-passwd">
                            <span class="slider round"></span>
                        </label>
                        <span id="afficher-passwd" aria-hidden="true">Afficher le mot de passe</span>
                    </div>
                </div>
                <div class="btn-register">
                    <div class="button">
                        <button tabindex="6" class="btn"><span class="material-icons">how_to_reg</span>S'inscrire</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
