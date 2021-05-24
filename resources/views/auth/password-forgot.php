<?php
$email = '';
if (isset($_POST['mail'])) {
    $email = htmlspecialchars($_POST['mail']);
} else if (isset($_GET['mail'])) {
    $email = htmlspecialchars($_GET['mail']);
}
?>
<div class="container card">
    <h1>Réinitialiser mot de passe</h1>
    <form method="post">
        <?= \App\Services\CsrfService::html() ?>
        <div class="form-group">
            <div class="input-auth">
                <label for="mail" class="textfield">
                    <input type="email" id="mail" autocomplete="mail" name="mail" spellcheck="false"
                           aria-label="Nom d'utilisateur ou adresse e-mail" required aria-required="true"
                           title="Nom d'utilisateur ou adresse e-mail"
                           autocapitalize="none" dir="ltr" <?php if ($email != '') {
                        echo 'value="' . $email . '"';
                    } ?>>
                    <span aria-hidden="true">Adresse e-mail</span>
                </label>
                <?php
                if (!empty($_POST)) {
                    ?>
                    <div style="margin-top: 10px">
                        <label for="force" class="switch" style="margin-right: 10px">
                            <input type="checkbox" name="force" id="force" aria-labelledby="force-label">
                            <span class="slider round"></span>
                        </label>
                        <span id="force-label" aria-hidden="true">Forcer le renvoie de l'email</span>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="btn-container">
                <div class="button">
                    <button class="btn" aria-label="Connecion" name="login" value="standard" title="Connexion"><span
                                class="material-icons">restart_alt</span>Réinitialiser
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
