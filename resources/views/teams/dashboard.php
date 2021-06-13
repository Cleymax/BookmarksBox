<?php

use App\Helper\TeamHelper;

if (TeamHelper::canManage($id)) {
    ?>
    <div class="info-point tooltipped" data-tooltip="left" data-text="Gérer l'équipe">
        <a href="<?= get_query_url('/teams/' . $id . '/manager') ?>">
            <span class="material-icons">edit</span>
        </a>
    </div>
    <?php
}
?>

<div class="btn-container"style="margin: 10px;">
    <button class="btn" aria-label="Ajouter un Bookmarks" title="addBookmarks" name="addBookmarks" value="addBookmarks"><span
                class="material-icons">add</span>Ajouter un Bookmarks
    </button>
    <button class="btn" aria-label="Ajouter un dossier" title="addFolders" name="addFolders" value="addFolders"><span
                class="material-icons">add</span>Ajouter un dossier
    </button>
</div>

<div class="moveMenuContainer">
    <div class="moveMenu" id="moveMenu">
        <input type="hidden" value="default" name="id">
        <span class="material-icons" style="margin: 10px;position: absolute;right: 0;">close</span>
        <div id="foldersMove"></div>
        <hr>
        <button class="btn" style="width: 80px; margin: 15px; position: absolute;bottom: 0;right: 0;">Déplacer</button>
    </div>
</div>

<div class="grid-250">

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
