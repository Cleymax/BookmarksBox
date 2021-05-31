<div style="position: absolute; right: 30px;top: 10px; background-color: var(--white); border-radius: 50px;">
    <a href="<?= get_query_url('/teams/' . $id . '/manager') ?>">
        <span class="material-icons" style="color: var(--dark); padding: 10px">edit</span>
    </a>
</div>
<h1>Hi <?= $data->name ?></h1>
<h2>With id: <?= $data->id ?></h2>
