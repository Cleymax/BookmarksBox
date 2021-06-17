<?php

use App\Services\CsrfService;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

?>
<div class="container card">
    <h1>2FA</h1>
    <form method="post">
        <?= CsrfService::html() ?>
        <?php
        if (is_null($data->totp)) {
            $url = GoogleQrUrl::generate($data->username, $secret, 'BookmarksBox');
            ?>
            <input type="hidden" name="secret" value="<?= $secret ?>">
            <p>
                Voici votre QrCode pour activer la double authentification.
            </p>
            <br>
            <img src="<?= $url ?>" width="150px" height="150px" style="max-width: 150px !important;" alt="QrCode">
            <br>
            <br>
            <span style="color: var(--secondary); font-size: small">Code: <?= $secret ?></span>
            <div class="form-group">
                <div class="input-auth">
                    <label for="code2fa" class="textfield">
                        <input type="text" id="code2fa" autocomplete="2fa" name="code2fa" spellcheck="false"
                               aria-label="Votre code 2FA" required aria-required="true"
                               title="Code 2FA" maxlength="6" pattern="\d{6}"
                               autocapitalize="none" dir="ltr" autofocus>
                        <span aria-hidden="true">Votre code 2FA</span>
                    </label>
                </div>
            </div>
            <div class="btn-container" style="justify-content: center;">
                <div class="button">
                    <button class="btn" aria-label="Activer" name="action" value="activate" title="Activer">
                        <span class="material-icons">check</span>Activer
                    </button>
                </div>
            </div>
        <?php } else { ?>
            <h3>Déjà activé sur votre compte.</h3>
            <div class="btn-container" >
                <div class="button">
                    <button class="btn" aria-label="Réinitialiser" name="action" value="reset" title="Réinitialiser">
                        <span class="material-icons">restart_alt</span>Réinitialiser
                    </button>
                </div>
            </div>
        <?php } ?>
    </form>
</div>
