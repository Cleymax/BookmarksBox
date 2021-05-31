<div class="settings-container">
    <h3>Changez votre photo de profil</h3>
    <form method="post">
        <?= \App\Services\CsrfService::html() ?>
        <button class="btn" aria-label="Enregistrer" title="Enregistrer" style="
    			width: 250px;
		">
            <span class="material-icons">save</span>Enregistrer
        </button>
    </form>
</div>
