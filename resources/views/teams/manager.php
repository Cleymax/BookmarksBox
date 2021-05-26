<h1>Gestion de l'équipe <?= $data->name ?></h1>

<ul class="tabs">
    <li class="tab col s3"><a href="#test1">Test 1</a></li>
    <li class="tab col s3"><a class="active" href="#test2">Test 2</a></li>
    <li class="tab col s3 disabled"><a href="#test3">Disabled Tab</a></li>
    <li class="tab col s3"><a href="#test4">Test 4</a></li>
</ul>

<h3>Parramètres</h3>
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
<br>
<h3>Membres</h3>
<div>

    <table width="100%" class="striped highlight responsive">
        <thead>
        <tr>
            <th>Nom d'utilisateur</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Actions</th>
        </tr>
        </thead>

        <tbody>
        <?php
        foreach ($members as $member) {
            ?>
            <tr>
                <td>
                    <img class="materialboxed" src="<?= $member->avatar ?>" height="20px" width="20px" alt="">
                    <?php
                    if ($member->role == 'OWNER') {
                        ?>
                        <span style="color: var(--yellow)" class="material-icons">manage_accounts</span>
                        <?php
                    } elseif ($member->role == 'EDITOR') {
                        ?>
                        <span style="color: var(--green)" class="material-icons">edit</span>
                        <?php
                    }
                    ?>
                    <span style="margin-left: 10px"><?= $member->username ?></span>
                </td>
                <td>
                    <span><?= $member->first_name ?? '' ?></span>
                </td>
                <td>
                    <span><?= $member->last_name ?? '' ?></span>
                </td>
                <td>
                    <a href="">
                        <span class="material-icons">edit</span>
                    </a>
                    <a href="">
                        <span class="material-icons">delete</span>
                    </a>

                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>

</div>
