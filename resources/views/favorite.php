<h2 class="margin-bottom20">Mes favoris:</h2>

<div class="flex flex-gap-20">
    <?php
    foreach ($data as $bookmark) {
        ?>
        <div class="team cards" data-id="<?= $bookmark->id ?>">
            <a href="#<?= $bookmark->id ?>">
                <img class="img-200" src="<?= $bookmark->thumbnail ?>" alt="Miniature de  <?= $bookmark->title ?>">
                <h3>
                    <?= $bookmark->title ?>
                </h3>
            </a>
            <div class="cards__hover">
                <a class="material-icons">edit</a>
                <a id="remove-favorite" class="material-icons star">star</a>
            </div>
        </div>
        <?php
    }
    ?>
</div>
