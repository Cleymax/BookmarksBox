<div class="container-profil">

    <div class="profil-header">
        <img src="<?php

        use App\Services\FileUploader;
        use App\Tools\Str;

        if (Str::startsWith($data->avatar, '/img/default-avatar.png')) {
            echo get_query_url('/img/default-avatar.png');
        } else {
            echo FileUploader::getSrc($data->avatar);
        } ?>" class="profil">
        <a href="<?= get_query_url("/settings/account") ?>" class="profil-edit">
            <span class="material-icons">manage_accounts</span>
        </a>
    </div>
    <h1 class="margin-top10"><?= $data->first_name . " " . $data->last_name ?></h1>
    <h3 class="margin-top10">@<?= $data->username ?></h3>
    <p class="margin-top10"><?= $data->bio ?></p>

    <div class="activity margin-top20">
        <h3 class="profil-title">Activité</h3>
        <div class="activity-items">
            <?php
            if (isset($lastcreated)) {
            foreach ($lastcreated as $bookmarks) {
                ?>

                <div class="bookmark" bookmark-id="<?= $bookmarks->id ?>"
                     style="display: flex;flex-direction: column; align-items: center; padding: 5px; width: 200px !important;">
                    <img width="150px" height="150px" src="<?= $bookmarks->thumbnail ?>"
                         alt="Avatar de l'équpe <?= $bookmarks->title ?>">
                    <h3>
                        <?= $bookmarks->title ?>
                    </h3>
                    <div class="bookmark-infos">
                        <h4>
                                <span class="material-icons"
                                      style="margin-right: 5px">schedule</span><?= $bookmarks->reading_time ?>
                        </h4>
                        <h4>
                            <?= \App\Helper\BookmarkHelper::translateDifficulty($bookmarks->difficulty) ?>
                        </h4>
                    </div>
                </div>

                <?php
            }
            ?>
        </div>
        <?php
        } else {
            ?>
            <h4>Aucune</h4>
            <?php
        }
        ?>
    </div>
</div>
</div>
