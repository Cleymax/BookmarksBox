<h2 class="margin-bottom20">Mes favoris (<?= sizeof($data) ?>):</h2>
<?php
if (empty($data)) {
    ?>
        <alert-message type="danger">Vous avez aucun favoris !</alert-message>
    <?php
} else {
    ?>
    <div class="grid-170">
        <?php
        foreach ($data as $bookmark) {
            ?>
            <div class="team  cards" data-id="<?= $bookmark->id ?>" style="width: 170px;">
                <a href="#<?= $bookmark->id ?>">
                    <img class="img-200" src="<?= $bookmark->thumbnail ?>" alt="Miniature de  <?= $bookmark->title ?>">
                    <h3 style="font-size: 16px;">
                        <?= $bookmark->title ?>
                    </h3>
                </a>
                <div class="cards__hover">
                    <a id="remove-favorite" class="material-icons star">star</a>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}
?>
