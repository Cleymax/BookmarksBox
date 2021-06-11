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
<h1>Hi <?= $data->name ?></h1>
<h2>With id: <?= $data->id ?></h2>
