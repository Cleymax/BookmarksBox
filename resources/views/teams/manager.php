<?php

use App\Helper\TeamHelper;
use App\Helper\UserHelper;
use App\Security\Auth;
use App\Services\CsrfService;
use App\Services\FileUploader;

require ROOT_PATH . '/../App/Tools/Array.php'
?>
<h1 class="margin-bottom20">Gestion de l'équipe <?= $data->name ?></h1>

<ul class="tabs" role="tablist">
    <li><a class="active" data-tab-target="#main" href="#main">Parramètres</a></li>
    <li><a data-tab-target="#invitations" href="#invitations">Invitations</a></li>
    <li><a data-tab-target="#members" href="#members">Membres</a></li>
    <li><a data-tab-target="#add-members" href="#add-members">Ajouter des membres</a></li>
    <?php
    if (TeamHelper::isOwner($id)) {
        ?>
        <li><a data-tab-target="#danger" href="#danger">Danger</a></li>
        <?php
    }
    ?>
</ul>

<div id="main" data-tab-content class="active">
    <form method="post" enctype="multipart/form-data">
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
                    <input class="file-upload-input" type='file' name="file" onchange="readURL(this);"
                           accept="image/*"/>
                    <div class="drag-text">
                        <h3>Déplacer un fichier ici !</h3>
                    </div>
                </div>
                <div class="file-upload-content" <?php if (!is_null($data->icon)) {
                    echo 'style="display: block !important;"';
                } ?>>
                    <img class="file-upload-image" src="<?= $data->icon ? FileUploader::getSrc($data->icon) : '' ?>"
                         alt="your image"/>
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
            <label for="visibility" class="switch" style="margin-right: 10px">
                <input name="visibility" type="checkbox" id="visibility" <?= $data->visibility ? 'checked' : '' ?>
                       aria-labelledby="visibility-label">
                <span class="slider round"></span>
            </label>
            <span id="public-label" aria-hidden="true">Visibilité de l'équipe</span>
            <br>
            <div class="btn-container" style="margin-top: 10px">
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
                        <img src="<?= FileUploader::getSrc($member->avatar) ?>" height="20px" width="20px" alt="Username Avatar">
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
                        $roles = [
                                'MEMBER' => 'Membre',
                                'EDITOR' => 'Editeur',
                                'MANAGER' => 'Manager',
                                'OWNER' => 'Propriétaire'
                        ];
                        ?>
                        <select name="role"
                                id="change-role" <?= $member->id == Auth::user()->id || (get_array_index($member->role, $roles) > get_array_index(TeamHelper::getRole($id), $roles)) ? 'disabled' : '' ?>>
                            <?php
                            foreach ($roles as $key => $value) {
                                ?>
                                <option <?php if ($member->role == $key) {
                                    echo "selected";
                                } ?> <?= get_array_index($key, TeamHelper::getRoles()) > get_array_index(TeamHelper::getRole($id), TeamHelper::getRoles()) ? 'disabled ' : '' ?>value="<?= $key ?>"><?= $value ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <?php
                        if (Auth::user()->id != $member->user_id && get_array_index($member->role, TeamHelper::getRoles()) < get_array_index(TeamHelper::getRole($id), TeamHelper::getRoles())) {
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
                <button class="btn btn-red" name="delete">Supprimer</button>
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
<?php
if (TeamHelper::isOwner($id)) {
    ?>

    <div id="danger" data-tab-content class="">
        <div class="container card">
            <h1>Supprimer l'équipe !</h1>
            <form method="post">
                <div class="form-group">
                    <?= CsrfService::html() ?>
                    <input type="hidden" name="delete-teams">
                    <div class="input-auth">
                        <label for="name" class="textfield">
                            <input type="text" id="name" name="name" required aria-required="true" autofocus
                                   spellcheck="false"
                                   title="Nom de l'équipe" dir="ltr" autocapitalize="characters">
                            <span aria-hidden="true">Nom de l'équipe</span>
                        </label>
                    </div>
                    <?php
                    if (UserHelper::has2fa()) {
                        ?>
                        <div class="input-auth">
                            <p class="label-2fa" style="margin-bottom: 5px;">Entrez votre code de double
                                authentification.</p>
                            <label for="code" class="textfield">
                                <input type="tel" pattern="[0-9]{6}" minlength="6" maxlength="6" id="code" tabindex="0"
                                       spellcheck="false" aria-label="Saisir le code" name="code2fa"
                                       autocapitalize="off"
                                       autofocus
                                       required aria-required="true" dir="ltr" title="Entre votre code 2FA">
                                <span>Saisir le code</span>
                            </label>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="btn-container margin-top20">
                        <div class="button">
                            <button class="btn btn-red" aria-label="Supprimer" title="Supprimer"><span
                                        class="material-icons">delete</span>Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php
}
?>

<script src="<?= $_ENV['BASE_URL'] . '/debugbar/vendor/jquery/dist/jquery.min.js' ?>"></script>
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
