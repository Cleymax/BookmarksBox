<?php
include 'parts/head.php';
include 'parts/header-dashboard.php';
?>
    <main>
        <div class="settings">
            <div class="menu-settings" id="menu-settings">
                <div class="menu__row">
                    <a href=""
                       style="
    			display: flex;
    			justify-content: center;
		">
                        <h3>Paramètres</h3>
                    </a>
                </div>
                <div class="line"></div>
                <div class="menu__row">
                    <a href="<?= get_query_url('account') ?>">
                        <span class="material-icons">person_outline</span>
                        <span>Mon Compte</span>
                        <span class="material-icons"
                              style="
    				margin-left: 90px;
			"> navigate_next</span>
                    </a>
                </div>
                <div class="line"></div>
                <div class="menu__row">
                    <a href="<?= get_query_url('security') ?>">
                        <span class="material-icons">lock_open</span>
                        <span class="tooltipped">Sécurité</span>
                        <span class="material-icons"
                              style="
    				margin-left: 120px;
			"> navigate_next</span>
                    </a>
                </div>
                <div class="line"></div>
            </div>
            <hr style="
            height: 100%;
            width: 0%;
            position: absolute;
            top: 0;
            left: 280px;
        ">
            <div class="menu-settings second-menu" id="menu-settings-col">
                <div class="menu__row" style="
    		height: 51px;
    		display: flex;
    		justify-content: center;
	">
                    <a href="" style="
    			display: flex;
    			justify-content: center;
			">
                        <h4>Sécurité</h4>
                    </a>
                </div>
                <div class="line"></div>
                <div class="menu__row">
                    <a href="<?= get_query_url('settings2fa') ?>">
                        <span class="material-icons">info</span>
                        <span>Double Authentification</span>
                        <span class="material-icons">navigate_next</span>
                    </a>
                </div>
            </div>
            <div class="content" id="content">
                <?= $content ?>
            </div>
        </div>

    </main>
<?php include 'parts/footer.php';
