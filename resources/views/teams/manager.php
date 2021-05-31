<?php

use App\Security\Auth;

?>
<h1 class="margin-bottom20">Gestion de l'équipe <?= $data->name ?></h1>

<ul class="tabs" role="tablist">
    <li><a class="active" data-tab-target="#main" href="#main">Parramètres</a></li>
    <li><a data-tab-target="#members" href="#members">Membres</a></li>
    <li><a data-tab-target="#add-members" href="#add-members">Ajouter des membres</a></li>
    <li><a data-tab-target="#authoriation" href="#authoriation">Authrorizations</a></li>
</ul>

<div id="main" data-tab-content class="active">
    <div>
        <form method="post">
            <div class="form-group">
                <div class="input-group">
                    <label for="name" class="textfield">
                        <input type="text" id="name" name="name" value="<?= $data->name ?>" autofocus spellcheck="false"
                               title="Nom de l'équipe" dir="ltr" autocapitalize="characters">
                        <span aria-hidden="true">Nom de l'équipe</span>
                    </label>
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
            </div>
        </form>
    </div>
</div>

<div id="members" data-tab-content class="">
    <div>
        <table class="striped highlight responsive" data-team-id="<?= $id ?>">
            <caption>Liste membres</caption>
            <thead>
            <tr>
                <th scope="col">Nom d'utilisateur</th>
                <th scope="col">Prénom</th>
                <th scope="col">Nom</th>
                <th scope="col">Rôle</th>
                <th scope="col">Actions</th>
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
