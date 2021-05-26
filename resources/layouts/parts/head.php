<?php

use App\Services\FlashService;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?> | BookmarkBox</title>

    <meta name="description" content="A teams bookmarkings for everyone !">
    <meta name="keywords" content="bookmark,box,bookmarksbox,free,opensource">

    <meta property="og:type" content="website"/>
    <meta property="og:site_name" content="BookmarksBox"/>
    <meta property="og:title" content="BookmarksBox — The best free teams bookmarkings"/>
    <meta property="og:description" content="A teams bookmarkings for everyone !"/>

    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:site" content="@bookmarksbox"/>
    <meta name="twitter:creator" content="@bookmarksbox"/>

    <!--  css  -->
    <link rel="stylesheet" href="<?= $_ENV['BASE_URL'] ?>/css/app.css"
          integrity="<?= hash_file('sha256', ROOT_PATH . '/css/app.css') ?>" crossorigin="anonymous">
    <?php if ($_ENV['MODE'] == 'dev') {
        echo $render->renderHead();
    } ?>
</head>
<body>

<div class="alert-container" id="alert-container">
    <?php
    if (FlashService::has()) {
        foreach (FlashService::get() as $flash) { ?>
            <alert-message type="<?= $flash['type'] ?>"
                    <?php if ($flash['duration'] != 'none'){ ?> duration="<?php echo $flash['duration'];
            } ?>"><?= $flash['text'] ?></alert-message> <?php }
        ?>
        <?php
    } ?>

</div>

