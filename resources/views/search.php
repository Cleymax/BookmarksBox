<h2>Votre recherche:</h2>

<?php
if (empty($result)) {
    ?>
        <alert-message type="danger">Aucun résultat</alert-message>
    <?php
} else {
    ?>

    <div class="grid-250">
        <?php
        foreach ($result as $bookmarks) {
            ?>
            <div class="bookmark" bookmark-id="<?= $bookmarks->id ?>">
                <input type="hidden" value="<?= $bookmarks->thumbnail ?>" name="link">
                <input type="hidden" value="<?= $bookmarks->title ?>" name="title">
                <input type="hidden" value="<?= $bookmarks->reading_time ?>" name="reading_time">
                <input type="hidden" value="<?= $bookmarks->difficulty ?>" name="difficulty">

                <img width="200px" height="200px" src="<?= $bookmarks->thumbnail ?>"
                     alt="Avatar de l'équpe <?= $bookmarks->title ?>">
                <h3 style="font-size: 21px;">
                    <?= $bookmarks->title ?>
                </h3>
                <div class="bookmark-infos">
                    <?php
                    if ($bookmarks->reading_time) {
                        ?>
                        <h4>
                    <span class="material-icons"
                          style="margin-right: 5px">schedule</span><?= $bookmarks->reading_time ?>
                        </h4>
                        <?php
                    }
                    ?>

                    <h4>
                        Difficultés : <?= $bookmarks->difficulty ?>
                    </h4>
                </div>
                <div class="flex-row">
                    <input type="hidden" name="title" id="title" value="<?= $bookmarks->title ?>">
                    <input type="hidden" name="thumbnail" id="thumbnail" value="<?= $bookmarks->thumbnail ?>">
                    <input type="hidden" name="link" id="link" value="<?= $bookmarks->link ?>">
                    <input type="hidden" name="difficulty" value="<?= $bookmarks->difficulty ?>">
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}
?>
