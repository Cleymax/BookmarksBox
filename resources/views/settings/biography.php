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
        <button class="btn" aria-label="Enregistrer" title="Enregistrer" style="
    			width: 250px;
		">
            <span class="material-icons">save</span>Enregistrer
        </button>
    </form>
</div>
