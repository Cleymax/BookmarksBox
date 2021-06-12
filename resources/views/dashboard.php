<div class="menu active menu-info" id="menu" style="right: 0;display: none" menu-info>
    <span class="material-icons info-close">close</span>
    <img src="https://placeimg.com/200/200/any" class="img-info">
    <h3 class="flex-center">Mon Bookmarks</h3>
    <div class="info">
        <h5><span class="material-icons">description</span> Description</h5>
        <textarea class="description-info" disabled="disabled">Description</textarea>
        <h5><span class="material-icons">link</span> Liens</h5>
        <textarea disabled="disabled">liens</textarea>
    </div>
</div>

<div class="btn-container"style="margin: 10px;">
    <button class="btn" aria-label="Ajouter un Bookmarks" title="addBookmarks" name="addBookmarks" value="addBookmarks"><span
                class="material-icons">add</span>Ajouter un Bookmarks
    </button>
    <button class="btn" aria-label="Ajouter un dossier" title="addFolders" name="addFolders" value="addFolders"><span
                class="material-icons">add</span>Ajouter un dossier
    </button>
</div>

<div class="flex" style="justify-content: space-evenly;gap:15px;">

    <?php
    foreach ($folders as $folder) {
        ?>
        <a href="<?= get_query_url('/folder/'.$folder->id)?>">
            <div class="folder">
                <span class="material-icons">folder</span>
                <h3>
                    <?= $folder->name ?>
                </h3>
            </div>
        </a>
        <?php
    }
    ?>


    <?php
    foreach ($data as $bookmarks) {
        ?>
        <div class="bookmark" bookmark-id="<?= $bookmarks->id?>">
            <input type="hidden" value="<?= $bookmarks->thumbnail?>" name="link">
            <input type="hidden" value="<?= $bookmarks->title?>" name="title">
            <input type="hidden" value="<?= $bookmarks->reading_time?>" name="reading_time">
            <input type="hidden" value="<?= $bookmarks->difficulty?>" name="difficulty">

            <img width="200px" height="200px" src="<?= $bookmarks->thumbnail ?>"
                 alt="Avatar de l'équpe <?= $bookmarks->title ?>">
            <h3>
                <?= $bookmarks->title ?>
            </h3>
            <div class="bookmark-infos">
                <h4>
                    <span class="material-icons" style="margin-right: 5px">schedule</span><?= $bookmarks->reading_time ?>
                </h4>
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

    <div class="modal" id="modal">
        <div class="modal-frame">
            <h4>Edit Settings</h4>
            <form method="post">
                <input type="hidden" value="default" id="id_bookmarks" name="id_bookmarks">
                <label for="title-modal" class="textfield">
                    <input type="text" id="title-modal" autocomplete="title" name="title" spellcheck="false"
                           tabindex="2" aria-label="Titre" required aria-required="true"
                           title="Titre"
                           autocapitalize="none" dir="ltr">
                    <span aria-hidden="true">Titre</span>
                </label>
                <label for="link-modal" class="textfield">
                    <input type="text" id="link-modal" autocomplete="link" name="link" spellcheck="false"
                           tabindex="2" aria-label="Liens" required aria-required="true"
                           title="Liens"
                           autocapitalize="none" dir="ltr">
                    <span aria-hidden="true">Liens</span>
                </label>
                <label for="thumbnail-modal" class="textfield">
                    <input type="text" id="thumbnail-modal" autocomplete="thumbnail" name="thumbnail"
                           spellcheck="false"
                           tabindex="2" aria-label="Thumbnail" required aria-required="true"
                           title="Thumbnail"
                           autocapitalize="none" dir="ltr">
                    <span aria-hidden="true">Thumbnail</span>
                </label>
                <select name="difficulty" id="difficulty-modal">
                    <?php
                    $value = ["EASY", "MEDIUM", "DIFFICILE", "PRO"];

                    foreach ($value as $v) {
                        echo "<option value=" . $v . "" . ($v == $bookmarks->difficulty ? ' selected' : '') . ">$v</option>";
                    }
                    ?>
                </select>
                <button class="btn" aria-label="edit" title="edit" name="action" value="edit"><span
                            class="material-icons">edit</span>Edit
                </button>
            </form>
        </div>
    </div>
</div>

<script>

</script>
