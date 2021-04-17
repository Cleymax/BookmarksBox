<?php

use App\Services\FlashService;

include 'parts/head.php';
include 'parts/header.php';
?>
    <main>
        <?php
        if (FlashService::has()) {
            foreach (FlashService::get() as $type => $message) {
                ?>
                <div class="alert alert-"<?= $type ?>><?= $message ?></div>
                <?php
            }
        }
        ?>
        <?= $content ?>
    </main>
<?php include 'parts/footer.php';
