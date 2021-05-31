<div class="container-profil">

    <div class="profil-header">
        <img src="<?= $_ENV['BASE_URL'].$data->avatar;?>" class="profil">
        <a href="<?= get_query_url("/settings/account") ?>" class="profil-edit">
            <span class="material-icons">manage_accounts</span>
        </a>
    </div>
    <h1 class="margin-top10"><?=$data->first_name." " .$data->last_name?></h1>
    <h3 class="margin-top10">@<?=$data->username?></h3>
    <p class="margin-top10"><?=$data->bio?></p>
    <div class="activity margin-top10">
        <h3 class="profil-title">Activité</h3>
        <div class="activity-items">
            <img src="https://via.placeholder.com/100" style="width: 100px;">
            <img src="https://via.placeholder.com/100" style="width: 100px;">
            <img src="https://via.placeholder.com/100" style="width: 100px;">
            <img src="https://via.placeholder.com/100" style="width: 100px;">
        </div>
    </div>
</div>
