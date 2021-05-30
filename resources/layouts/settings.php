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
                <a href="<?= get_query_url('account')?>">
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
                <a href="<?= get_query_url('security')?>">
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
                    <h4>Mon compte</h4>
                </a>
            </div>
            <div class="line"></div>
            <div class="menu__row">
                <a href="<?=get_query_url('infos')?>">
                    <span class="material-icons">info</span>
                    <span>Informations</span>
                    <span class="material-icons" style="
    				margin-left: 90px;
			"> navigate_next </span>
                </a>
            </div>
            <div class="line"></div>
            <div class="menu__row">
                <a href="<?= get_query_url('password')?>">
                    <span class="material-icons">vpn_key</span>
                    <span>Mot de passe</span>
                    <span class="material-icons" style="
    			margin-left: 82px;
			"> navigate_next </span>
                </a>
            </div>
            <div class="line"></div>
            <div class="menu__row">
                <a href="<?= get_query_url('email')?>">
                    <span class="material-icons">email</span>
                    <span>Email</span>
                    <span class="material-icons" style="
    				margin-left: 140px;
			"> navigate_next </span>
                </a>
            </div>
            <div class="line"></div>
            <div class="menu__row">
                <a href="<?= get_query_url('identity')?>">
                    <span class="material-icons">badge</span>
                    <span>Identité</span>
                    <span class="material-icons" style="
    				margin-left: 125px;
			"> navigate_next </span>
                </a>
            </div>
            <div class="line"></div>
            <div class="menu__row">
                <a href="<?= get_query_url('profil_picture')?>">
                    <span class="material-icons">image</span>
                    <span>Image de profil</span>
                    <span class="material-icons" style="
    				margin-left: 72px;
			"> navigate_next </span>
                </a>
            </div>
            <div class="line"></div>
            <div class="menu__row">
                <a href="<?= get_query_url('biography')?>">
                    <span class="material-icons">library_books</span>
                    <span>Biographie</span>
                    <span class="material-icons" style="
    				margin-left: 100px;
			"> navigate_next </span>
                </a>
            </div>
            <div class="line"></div>
            <div class="menu__row">
                <a href="<?= get_query_url('delete')?>">
                    <span class="material-icons">highlight_off</span>
                    <span>Supprimer</span>
                    <span class="material-icons" style="
				margin-left: 100px;
			"> navigate_next </span>
                </a>
            </div>
            <div class="line"></div>
        </div>
        <div class="content" id="content">
            <?= $content ?>
        </div>
    </div>

</main>
<?php include 'parts/footer.php';
