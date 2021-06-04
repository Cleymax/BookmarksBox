<?php

use App\Security\Auth;
use App\Services\CsrfService;

?>
<h1 class="margin-bottom20">Gestion de l'équipe <?= $data->name ?></h1>

<ul class="tabs" role="tablist">
    <li><a class="active" data-tab-target="#main" href="#main">Parramètres</a></li>
    <li><a data-tab-target="#invitations" href="#invitations">Invitations</a></li>
    <li><a data-tab-target="#members" href="#members">Membres</a></li>
    <li><a data-tab-target="#add-members" href="#add-members">Ajouter des membres</a></li>
    <li><a data-tab-target="#authoriation" href="#authoriation">Authrorizations</a></li>
</ul>

<div id="main" data-tab-content class="active">
    <form method="post">
        <?= CsrfService::html(); ?>
        <h4>Choisiser un icon</h4>
        <div class="form-group">
            <div class="file-upload">
                <button class="file-upload-btn" type="button" onclick="$('.file-upload-input').trigger( 'click' )">Add
                    Image
                </button>
                <div class="image-upload-wrap" <?php if (!is_null($data->icon)) {
                    echo 'style="display: none !important;"';
                } ?>>
                    <input class="file-upload-input" type='file' onchange="readURL(this);" accept="image/*"/>
                    <div class="drag-text">
                        <h3>Déplacer un fichier ici !</h3>
                    </div>
                </div>
                <div class="file-upload-content" <?php if (!is_null($data->icon)) {
                    echo 'style="display: block !important;"';
                } ?>>
                    <img class="file-upload-image" src="<?= $data->icon ?? '' ?>" alt="your image"/>
                    <div class="image-title-wrap">
                        <button type="button" onclick="removeUpload()" class="remove-image">Supprimer <span
                                    class="image-title"></span></button>
                    </div>
                </div>
            </div>

            <label for="name" class="textfield">
                <input type="text" id="name" name="name" value="<?= $data->name ?>" autofocus spellcheck="false"
                       title="Nom de l'équipe" dir="ltr" autocapitalize="characters">
                <span aria-hidden="true">Nom de l'équipe</span>
            </label>
            <label for="description" class="textareafield margin-top30">
                    <textarea type="text" id="description" name="description" autofocus
                              title="Description de l'équipe" dir="ltr"
                              autocapitalize="characters"><?= $data->description ?></textarea>
                <span aria-hidden="true">Description</span>
            </label>
            <br>
            <label for="public" class="switch" style="margin-right: 10px">
                <input type="checkbox" id="public" <?= $data->public ? 'checked' : '' ?> name="public"
                       aria-labelledby="public-label">
                <span class="slider round"></span>
            </label>
            <span id="public-label" aria-hidden="true">Visibilité de l'équipe</span>

            <div class="btn-container">
                <br>
                <div class="button">
                    <button class="btn">
                        <span class="material-icons">edit</span>
                        Modifier
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="members" data-tab-content class="">
    <div>
        <table class="striped highlight responsive" sortable data-team-id="<?= $id ?>">
            <caption>Liste membres</caption>
            <thead>
            <tr>
                <th scope="col">Nom d'utilisateur</th>
                <th scope="col">Prénom</th>
                <th scope="col">Nom</th>
                <th scope="col">Rôle</th>
                <th scope="col" not-sortable>Actions</th>
            </tr>
            </thead>

            <tbody id="members-list">
            <?php
            foreach ($members as $member) {
                ?>
                <tr data-user-id="<?= $member->id ?>">
                    <td>
                        <img src="<?= $member->avatar ?>" height="20px" width="20px" alt="Username Avatar">
                        <span style="margin-left: 10px"><?= $member->username ?></span>
                    </td>
                    <td>
                        <span><?= $member->first_name ?? '' ?></span>
                    </td>
                    <td>
                        <span><?= $member->last_name ?? '' ?></span>
                    </td>
                    <td>
                        <?php
                        $role = [
                                'MEMBER' => 'Membre',
                                'EDITOR' => 'Editeur',
                                'MANAGER' => 'Manager',
                                'OWNER' => 'Propriétaire'
                        ];
                        ?>
                        <select name="role" id="change-role">
                            <?php
                            foreach ($role as $key => $value) {
                                ?>
                                <option <?php if ($member->role == $key) {
                                    echo "selected";
                                } ?> value="<?= $key ?>"><?= $value ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <?php
                        if (Auth::user()->id != $member->user_id) {
                            ?>
                            <a id="delete-member"><span class="material-icons">delete</span> </a>
                            <?php
                        }
                        ?>
                    </td>

                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<div id="add-members" data-tab-content class="">
    <label for="q-members" data-team-id="<?= $id ?>" class="textfield margin-top15">
        <input type="text" id="q-members">
        <span>Rechercher un membre à ajouté</span>
    </label>
    <div>
        <table class="striped highlight" data-team-id="<?= $id ?>">
            <thead>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Prénom</th>
                <th scope="col">Nom d'utilisateur</th>
            </tr>
            </thead>
            <tbody id="result-members">
            </tbody>
        </table>
    </div>
</div>

<div id="invitations" data-tab-content class="">
    <div class="margin-top20">
        <h4>Code d'invitation</h4>
        <form method="post">
            <?= CsrfService::html() ?>
            <?php
            if ($data->invite_code) {
                ?>
                <p data-copy="<?= $data->invite_code ?>" class="margin-10"
                   style="padding: 5px; background-color: rgba(0,0,0,0.13)"><?= $data->invite_code ?></p>

                <button class="btn" name="action">Regénérer</button>
                <?php
            } else {
                ?>
                <button class="btn" name="action">Générer</button>
                <?php
            }
            ?>
        </form>
    </div>
</div>

<script src="<?= getenv('BASE_URL') . '/debugbar/vendor/jquery/dist/jquery.min.js' ?>"></script>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {

            var reader = new FileReader();

            reader.onload = function (e) {
                $('.image-upload-wrap').hide();

                $('.file-upload-image').attr('src', e.target.result);
                $('.file-upload-content').show();

                $('.image-title').html(input.files[0].name);
            };

            reader.readAsDataURL(input.files[0]);

        } else {
            removeUpload();
        }
    }

    function removeUpload() {
        $('.file-upload-input').replaceWith($('.file-upload-input').clone());
        $('.file-upload-content').hide();
        $('.image-upload-wrap').show();
    }

    $('.image-upload-wrap').bind('dragover', function () {
        $('.image-upload-wrap').addClass('image-dropping');
    });
    $('.image-upload-wrap').bind('dragleave', function () {
        $('.image-upload-wrap').removeClass('image-dropping');
    });
</script>
