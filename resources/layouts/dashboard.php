<?php

use App\Helper\TeamHelper;
use App\Security\Auth;
use App\Services\FileUploader;

include 'parts/head.php';
include 'parts/header-dashboard.php';
?>
    <main <?php if (isset($id)) {
        echo 'data-id="' . $id . '"';
    } ?>>
        <div class="dashboard">
            <div class="menu active" id="menu">
                <div class="menu__row">
                    <a href="<?= get_query_url('dashboard') ?>">
                        <span class="material-icons">home</span>
                        <span>Accueil</span>
                    </a>
                </div>
                <div class="menu__row">
                    <a href="<?= get_query_url('/discover') ?>">
                        <span class="material-icons">language</span>
                        <span>Découverte</span>
                    </a>
                </div>
                <?php
                if (Auth::check()) {
                    ?>
                    <div class="line"></div>
                    <form action="<?= get_query_url('recherche') ?>" method="get">
                        <label for="search" class="textfield" style="width: 100%;">
                            <input type="text" name="q">
                            <span>Rechercher</span>
                            <span class="material-icons"
                                  style="    top: 10px; position: absolute; right: 5px;">search</span>
                        </label>
                        <input style="display: none" type="submit" value="Rechercher">
                    </form>
                    <?php
                }
                if (Auth::check() || isset($id)) {
                    ?>
                    <div class="line"></div>
                    <div id="folders">
                        <?php
                        for ($i = 0; $i < 4; $i++) {
                            ?>
                            <div class="menu__row">
                                <a href="">
                                    <skeleton-box rounded height="20px" width="20px"></skeleton-box>
                                    <div style="margin-right: 5px"></div>
                                    <skeleton-box width="140px"></skeleton-box>
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
                if (Auth::check()) {
                    ?>
                    <div class="line"></div>
                    <div class="menu__row">
                        <a href="<?= get_query_url('/favorite') ?>">
                            <span class="material-icons">star</span>
                            <span>Favoris</span>
                        </a>
                    </div>
                    <?php
                }
                ?>

                <?php
                if (isset($equipes)) {
                    ?>
                    <div class="line"></div>
                    <div class="menu__row">
                        <a href="<?= get_query_url('teams') ?>">
                            <span class="material-icons">people</span>
                            <span>Equipes</span>
                        </a>
                    </div>
                    <?php
                    foreach ($equipes as $team) {
                        ?>
                        <aside>
                            <div class="menu__row equipe">
                                <a href="<?= get_query_url('/teams/' . $team->id) ?>">
                                    <div>
                                        <img class="team__icon" src="<?= FileUploader::getSrc($team->icon) ?>"
                                             alt="<?= $team->name ?> icon">
                                        <span><?= $team->name ?></span>
                                    </div>
                                    <?php
                                    if ($team->favorite) {
                                        ?>
                                        <span class="material-icons hide_on_hover">star</span>
                                        <?php
                                    }
                                    ?>
                                </a>
                                <div class="menu__row__hover">
                                    <div class="menu__row__hover__content">
                                        <?php
                                        if (TeamHelper::canManageWithRole($team->role)) {
                                            ?>
                                            <a href="<?= get_query_url('/teams/' . $team->id . '/manager') ?>"
                                               class="material-icons">settings</a>
                                            <?php
                                        }
                                        if (TeamHelper::getRole($team->id) != 'OWNER') {
                                            ?>
                                            <a href="<?= get_query_url('/teams/' . $team->id) . '/leave' ?>">
                                                <span class="material-icons"
                                                      style="color: var(--red)">exit_to_app</span>
                                            </a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </aside>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="content" id="content">
                <?= $content ?>
            </div>
            <div class="menu-info" id="menu-info" style="transform: translateX(280px);">
                <span class="material-icons info-close">close</span>
                <img src="https://placeimg.com/200/200/any" id="imgInfo" class="img-info">
                <h3 class="flex-center" id="titleInfo">Mon Bookmarks</h3>
                <div class="info">
                    <h5 id="DifficultyInfo">Difficultés : </h5>
                    <h5><span class="material-icons">description</span> Description</h5>
                    <textarea class="description-info" disabled="disabled" id="DescriptionInfo">Description</textarea>
                    <h5><span class="material-icons">link</span> Liens</h5>
                    <textarea disabled="disabled" id="linkInfo">liens</textarea>
                </div>
            </div>
        </div>

    </main>
<?php include 'parts/footer.php';
