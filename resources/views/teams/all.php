<h1>Mes équipes:</h1>

<div class="flex">
    <?php
    foreach ($data as $team) {
        ?>
        <div class="team" style="padding: 1rem;">
            <a href="<?= \App\Router\Router::get_url('/teams/' . $team->id) ?>">
                <img width="150px" height="150px" src="<?= $team->icon ?>" alt="Avatar de l'équpe <?= $team->name ?>">
                <h3>
                    <?= $team->name ?>

                </h3>
                <p>
                    Id: <?= $team->id ?>
                </p>
                <?php
                if ($team->public) {
                    ?>
                    <p style="color: var(--green)">Public</p>
                    <?php
                }else {
                    ?>
                    <p style="color: var(--red)">Non Public</p>
                    <?php
                }
                ?>
            </a>
        </div>
        <?php
    }
    ?>
</div>
