<div class="container card">
    <h3>Vous avez rejoind l'équipe <?= $equipe->name ?>!</h3>
    <br>
    <div class="btn-container">
        <div class="button">
            <a style="background-color: var(--green); border-color: var(--green)"
               href="<?= get_query_url('/teams/' . $equipe->id) ?>" class="btn">Voir
                l'équipe</a>
        </div>
    </div>
</div>
