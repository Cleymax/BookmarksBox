<h2>Rejoindre une équipe:</h2>
<h3>Code s'invitation</h3>
<div class="flex flex-gap-20">
    <div class="team cards">
        <a>
            <label for="code" class="textfield">
                <input type="text" id="code" autocomplete="code" name="code" spellcheck="false" maxlength="6"
                       aria-label="Nom d'utilisateur ou adresse e-mail" required aria-required="true"
                       title="Code d'invitation"
                       autocapitalize="none" dir="ltr" autofocus>
                <span aria-hidden="true">Code d'invitation</span>
            </label>
            <button style="margin-top: 10px" id="join-team" class="btn">Rejoindre</button>
        </a>
    </div>
</div>
<h3>Equipe(s) public:</h3>
<div class="flex flex-gap-20">
    <?php

    use App\Helper\TeamHelper;

    foreach ($equipes_public as $team) {
        ?>
        <div class="team cards">
            <a href="<?= get_query_url('/teams/invite/' . $team->invite_code) ?>">
                <img src="<?= $team->icon ?>" alt="Avatar de l'équpe <?= $team->name ?>">
                <h3>
                    <?= $team->name ?>
                </h3>
            </a>
        </div>
        <?php
    }
    ?>
</div>

<h2 style="margin-top: 20px">Mes équipes:</h2>

<div class="flex flex-gap-20">
    <?php
    foreach ($equipes as $team) {
        ?>
        <div class="team cards">
            <a href="<?= get_query_url('/teams/' . $team->id) ?>">
                <img src="<?= $team->icon ?>" alt="Avatar de l'équpe <?= $team->name ?>">
                <h3>
                    <?= $team->name ?>
                </h3>
            </a>
            <?php
            if ($team->favorite) {
                ?>
                <div class="cards__abs">
                    <span class="material-icons" style="color: var(--yellow);">star</span>
                </div>
                <?php
            }
            ?>
            <div class="cards__hover">
                <?php
                if (TeamHelper::canManageWithRole($team->role)) {
                    ?>
                    <a href="<?= get_query_url('/teams/' . $team->id . '/manager') ?>"
                       class="material-icons">settings</a>
                    <?php
                }
                ?>
                <a href="#link" data-copy="<?= $team->invite_code ?? '' ?>" class="material-icons">link</a>
            </div>
        </div>
        <?php
    }
    ?>

</div>
