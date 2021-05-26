<?php
include 'parts/head.php';
include 'parts/header.php';
?>
    <main>
        <div class="dashboard">
            <div class="menu">
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
                        <span>4</span>
                    </a>
                </div>
                <div class="menu__row">
                    <a href="">
                        <span class="material-icons">folder</span>
                        <span>5</span>
                    </a>
                </div>
                <div class="menu__row">
                    <a href="">
                        <span class="material-icons">folder</span>
                        <span>6</span>
                    </a>
                </div>
                <div class="menu__row">
                    <a href="">
                        <span class="material-icons">folder</span>
                        <span>7</span>
                    </a>
                </div>
                <div class="menu__row">
                    <a href="">
                        <span class="material-icons">folder</span>
                        <span>8</span>
                    </a>
                </div>
                <div class="menu__row">
                    <a href="">
                        <span class="material-icons">folder</span>
                        <span>9</span>
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
                    foreach ($equipes as $equipe) {
                        ?>
                        <aside>
                            <div class="menu__row equipe">
                                <a href="<?= get_query_url('/teams/' . $equipe->id) ?>">
                                    <div>
                                         <img class="team__icon" src="<?= $equipe->icon ?>"
                                             alt="<?= $equipe->name ?> icon">
                                        <span><?= $equipe->name ?></span>
<!--                                        <skeleton-box rounded width="25px" height="25px"></skeleton-box>-->
<!--                                        <div style="margin-right: 10px"></div>-->
<!--                                        <skeleton-box width="200px"></skeleton-box>-->
                                    </div>
                                    <?php
                                    if ($equipe->favorite) {
                                        ?>
                                        <span class="material-icons">star</span>
                                        <?php
                                    }
                                    ?>
                                </a>
                            </div>
                        </aside>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="content">
                <?= $content ?>
            </div>
        </div>

    </main>
<?php include 'parts/footer.php';
