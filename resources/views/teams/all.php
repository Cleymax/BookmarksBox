<h1>Mes équipes:</h1>

<div class="flex">
    <?php
    foreach ($data as $team) {
        ?>
        <div class="team teams-card" style="padding: 1rem;">
            <a href="<?= get_query_url('/teams/' . $team->id) ?>">
                <img width="150px" height="150px" src="<?= $team->icon ?>" alt="Avatar de l'équpe <?= $team->name ?>">
                <h3>
                    <?= $team->name ?>
                </h3>
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

<div>
    <h2>Rejoindre une équipe</h2>
    <div class="team teams-card teams-search" style="padding: 1rem;">
        <div class="teams-search">
            <span class="material-icons">search</span>
            <form>
                <label for="search" class="textfield margin-10">
                    <input type="text" id="search" autocomplete="search" name="search" spellcheck="false"
                           tabindex="2" aria-label="Recherche" required aria-required="true"
                           title="Liens"
                           autocapitalize="none" dir="ltr">
                    <span aria-hidden="true">Search</span>
                </label>
                <button class="btn margin-10 center-btn" aria-label="Recherche-Bouton" title="search-btn" name="search-btn" value="rechercher">Rechercher</button>
            </form>
        </div>
    </div>
</div>
