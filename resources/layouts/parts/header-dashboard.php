<header>
    <div class="header-left">
        <div class="hamburger" id="hamburger">
            <svg focusable="false" viewBox="0 0 24 24">
                <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path>
            </svg>
        </div>
        <a class="header-logo" href="<?= get_query_url('dashboard') ?>" accesskey="a">
            <svg width="50" height="50" viewBox="0 0 117 114" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="box">
                    <path d="M69.9279 -0.0374008C69.5571 0.0145844 69.2007 0.191421 68.9307 0.482403L56.3748 13.0559L43.8189 0.482403C43.5077 0.172286 43.089 -0.00210578 42.6499 -0.000564262C42.3738 -0.000350282 42.0998 0.0638939 41.8529 0.187711L28.7126 7.2726C28.3292 7.49417 28.043 7.85085 27.9156 8.27537C27.7874 8.6944 27.8338 9.15068 28.0423 9.536C28.3331 10.0756 28.8932 10.4129 29.5055 10.416C29.7819 10.4182 30.0565 10.3538 30.3025 10.2277L42.3761 3.66671L53.7181 15.0451L15.6335 35.6449L4.41414 24.3279L24.421 13.5594C24.809 13.3533 25.0981 12.9961 25.218 12.573C25.5079 11.5201 24.7146 10.4796 23.624 10.4774C23.3479 10.4776 23.0779 10.5418 22.8311 10.6657L0.878708 22.4656C0.409619 22.7038 0.0881126 23.1589 0.0203947 23.6812C-0.0652693 24.2028 0.107719 24.7331 0.482249 25.1056L13.0381 37.6791L0.482249 50.2526C0.104937 50.6147 -0.0691358 51.1409 0.0203947 51.6565C0.0918744 52.1826 0.411285 52.642 0.878708 52.8926L13.6635 59.8301V90.3839C13.6764 90.4177 13.6764 90.4525 13.6635 90.4863V90.5927V90.695H13.8106L55.2998 113.48C55.2998 113.48 55.6512 113.725 55.9783 113.767H57.0859C57.3157 113.738 57.5963 113.526 57.8502 113.407L98.3095 91.5545L98.4403 91.4399L98.5139 91.354C98.5218 91.3195 98.53 91.2329 98.522 91.1984C98.5323 91.143 98.5323 91.086 98.522 91.0306L98.4812 85.9881L95.0847 83.7779L95.1092 89.0005L58.1118 109.514V63.9967L69.5601 75.4815V70.7051L68.7426 69.9069L59.2644 60.3745L68.8571 54.3374L68.8489 50.2117L56.5015 58.0251L18.8338 37.6791L56.5015 17.3085L86.8311 33.8507L93.8136 33.8031L59.2236 15.0451L70.4593 3.72812L108.421 24.3279L99.0247 33.8481L103.745 33.8932L112.439 25.1056C112.797 24.723 112.963 24.2013 112.897 23.6813C112.816 23.1643 112.498 22.7145 112.038 22.4657L71.0233 0.187711C70.6839 -0.0178292 70.2987 -0.089386 69.9279 -0.0374008ZM98.943 34.02L93.8136 33.8031L98.943 34.02ZM15.5681 39.8361L53.7181 60.3745L42.3965 71.7119L16.0504 57.5667V57.4398L4.35283 51.1531L15.5681 39.8361ZM16.9905 61.7579L41.8938 75.2728C42.1356 75.4122 42.4119 75.4837 42.6908 75.4815C43.1339 75.4824 43.5589 75.3 43.8638 74.9781L54.7848 64.0418V109.514L16.9905 89.0005V61.7579Z"
                          fill="#010101"/>
                </g>
                <g id="bookmark">
                    <path d="M115.305 33.8018H70.4595C70.0355 33.7847 69.6219 33.9358 69.3085 34.2222C68.995 34.5086 68.8071 34.9073 68.7854 35.3316V92.1221C68.7854 92.6841 68.9421 93.2349 69.2379 93.7126C69.5336 94.1902 69.9567 94.5756 70.4595 94.8254C71.0158 95.0786 71.626 95.1901 72.2358 95.1499C72.8455 95.1097 73.4359 94.919 73.9542 94.5949L92.788 82.4195L111.622 94.5949C112.14 94.919 112.731 95.1097 113.34 95.1499C113.95 95.1901 114.56 95.0786 115.117 94.8254C115.649 94.5977 116.105 94.2222 116.432 93.7432C116.758 93.2642 116.941 92.7017 116.958 92.1221V35.3316C116.936 34.911 116.751 34.5156 116.443 34.2297C116.134 33.9439 115.725 33.7903 115.305 33.8018ZM113.631 92.1221L93.8344 79.318C93.5392 79.1342 93.1985 79.0367 92.8508 79.0367C92.5032 79.0367 92.1625 79.1342 91.8673 79.318L72.0708 92.1221V36.8405H113.631V92.1221Z"
                          fill="#B01917"/>
                </g>
            </svg>

            Bookmarks<span style="color: var(--dark-red)">Box</span>
        </a>
    </div>

    <div class="header-right">
        <?php

        use App\Router\Router;
        use App\Security\Auth;

        if (!Auth::check()) {
            ?>
            <a href="<?= get_query_url('login') ?>" class="btn btn-secondary btn-rounded" accesskey="c"><span
                        class="material-icons">login</span>Connexion</a>
            <a href="<?= get_query_url('register') ?>" class="btn btn-rounded btn-outlined btn-white"
               accesskey="i"><span class="material-icons">how_to_reg</span>Inscription</a>
            <?php
        } else {
            ?>
            <a href="<?= get_query_url('profile') ?>" class="btn btn-rounded btn-outlined btn-white profile"
               accesskey="p">
                <img src="https://www.belin.re/wp-content/uploads/2018/11/default-avatar.png" alt="Avatar">
                <span><?= Auth::user()->username ?></span>
            </a>
            <a href="<?= get_query_url('logout') ?>" class="btn btn-rounded btn-outlined btn-white" accesskey="i"><span
                        class="material-icons">logout</span>Déconnexion</a>
            <?php
        }
        ?>
    </div>
</header>
<div id="context-menu">
    <div class="item" onclick="addFolders(this)">
        <span class="material-icons">add</span>  Ajouter un dossier
    </div>
    <div class="item" onclick="addBookmarks(this)">
        <span class="material-icons">add</span>  Ajouter une bookmarks
    </div>
    <hr>
    <div class="item">
        <span class="material-icons">drive_file_move</span>  Déplacer
    </div>
    <div class="item">
        <span class="material-icons">edit</span>  Modifier
    </div>
    <div class="item">
        <span class="material-icons">delete</span>  Supprimer
    </div>
    <div class="item">
        <span class="material-icons">star</span>  Mettre en favoris
    </div>
</div>
