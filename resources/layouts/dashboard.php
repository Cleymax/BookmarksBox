<?php

use App\Helper\TeamHelper;

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
                <div class="line"></div>
                <div class="menu__row">
                    <a href="">
                        <span class="material-icons">folder</span>
                        <span class="tooltipped" data-text="Je sais aps torp pkk tu pense ça !  dqzd">Je sais aps torp pkk tu pense ça !  dqzd</span>
                    </a>
                </div>
                <div class="menu__row">
                    <a href="">
                        <span class="material-icons">folder</span>
                        <span>2</span>
                    </a>
                </div>
                <div class="menu__row">
                    <a href="">
                        <span class="material-icons">folder</span>
                        <span>3</span>
                    </a>
                </div>
                <div class="menu__row">
                    <a href="">
                        <span class="material-icons">folder</span>
                        <span>10</span
                        ></a
                    ></div>
                <div class="line"></div>
                <div class="menu__row">
                    <a href="<?= get_query_url('/favorite') ?>">
                        <span class="material-icons">star</span>
                        <span>Favoris</span>
                    </a>
                </div>
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
                                        <img class="team__icon" src="<?= $team->icon ?>"
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
                                        ?>
                                        <a href="<?= get_query_url('/teams/' . $team->id) . '/leave' ?>">
                                            <span class="material-icons" style="color: var(--red)">exit_to_app</span>
                                        </a>
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
        </div>

    </main>
<?php include 'parts/footer.php';
