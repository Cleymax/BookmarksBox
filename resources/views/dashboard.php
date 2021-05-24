<div class="flex-row cancel-padding">
    <nav class="navbar">
        <div class="nav-container">
            <ul class="folders">
                <li>
                    <span class="material-icons">folder_open</span>
                    <span>Mes dossiers</span>
                </li>
                <li class="items-secondary">
                    <span class="material-icons">folder</span>
                    <span>Réseaux</span>
                </li>
                <li class="items-secondary">
                    <span class="material-icons">folder</span>
                    <span>Télécom</span>
                </li>
                <li class="items-secondary">
                    <span class="material-icons">folder</span>
                    <span>Service Réseaux</span>
                </li>
            </ul>
            <ul class="team">
                <li>
                    <span class="material-icons">person</span>
                    <span>Equipes</span>
                </li>
                <li class="items-secondary">
                    <span class="picture"></span>
                    <span>RT1</span>
                </li>
                <li class="items-secondary">
                    <span class="picture"></span>
                    <span>RT2</span>
                </li>
                <li class="items-secondary">
                    <span class="picture"></span>
                    <span>Prof</span>
                </li>
            </ul>
            <ul class="bookmarks">
                <li>
                    <span class="material-icons">star_border</span>
                    <span>Favoris</span>
                </li>
            </ul>
        </div>
    </nav>
    <div class="flex-col margin-20">
        <div class="dashboard-header">
            <h1>Dashboard</h1>
            <button class="btn btn-green" aria-label="edit" title="edit"  onclick="add(this)" name="edit" id="btn-add" style="margin-right: 5px;"><span class="material-icons">add</span> Ajouter</button>
        </div>
        <div class="flex">
            <?php
            foreach ($data as $bookmarks) {
                ?>
                <div class="team" style="padding: 1rem;">
                    <img width="150px" height="150px" src="<?= $bookmarks->thumbnail ?>" alt="Avatar de l'équpe <?= $bookmarks->title ?>">
                    <h3>
                        <?= $bookmarks->title ?>
                    </h3>
                    <h4>
                        <?= $bookmarks->reading_time ?>
                    </h4>
                    <h4>
                        <?= $bookmarks->difficulty ?>
                    </h4>
                    <div class="flex-row">
                        <input type="hidden" name="title" id="title" value="<?= $bookmarks->title ?>">
                        <input type="hidden" name="thumbnail"  id="thumbnail" value="<?= $bookmarks->thumbnail ?>">
                        <input type="hidden" name="link" id="link" value="<?= $bookmarks->link ?>">
                        <input type="hidden" name="difficulty" value="<?= $bookmarks->difficulty ?>">
                        <button class="btn" aria-label="edit" title="edit"  onclick="edit(this)" name="edit" id="btn-edit" style="margin-right: 5px;"><span class="material-icons">edit</span></button>
                        <form method="post">
                            <input type="hidden" name="id_bookmarks" value="<?= $bookmarks->id ?>">
                            <button class="btn btn-white" aria-label="edit" title="delete" name="action" value="delete"><span
                                        class="material-icons">delete</span></button>
                            <button class="btn btn-yellow" aria-label="edit" title="pin" name="action" value="pin"><span
                                        class="material-icons">push_pin</span></button>
                        </form>
                    </div>
                </div>

                <?php
            }
            ?>

            <div class="modal" id="modal">
                <div class="modal-frame">
                    <h4 class="title-modal">Edit Settings</h4>
                    <form method="post">
                        <input type="hidden" name="id_bookmarks_modal" id="id_bookmarks_modal" value="value">
                        <label for="bookmarks-modal" class="textfield">
                            <input type="text" id="bookmarks-modal" autocomplete="title" name="bookmarks-modal" spellcheck="false"
                                   tabindex="2" aria-label="Titre" required aria-required="true"
                                   title="Titre"
                                   autocapitalize="none" dir="ltr">
                            <span aria-hidden="true">Titre</span>
                        </label>
                        <label for="link-modal" class="textfield">
                            <input type="text" id="link-modal" autocomplete="link" name="link-modal" spellcheck="false"
                                   tabindex="2" aria-label="Liens" required aria-required="true"
                                   title="Liens"
                                   autocapitalize="none" dir="ltr">
                            <span aria-hidden="true">Liens</span>
                        </label>
                        <label for="thumbnail-modal" class="textfield">
                            <input type="text" id="thumbnail-modal" autocomplete="thumbnail" name="thumbnail-modal" spellcheck="false"
                                   tabindex="2" aria-label="Thumbnail" required aria-required="true"
                                   title="Thumbnail"
                                   autocapitalize="none" dir="ltr">
                            <span aria-hidden="true">Thumbnail</span>
                        </label>
                        <select name="difficulty-modal" id="difficulty-modal">
                            <?php
                            $value = ["EASY", "MEDIUM", "DIFFICILE", "PRO"];

                            foreach ($value as $v) {
                                echo "<option value=" . $v . "" . ($v == $bookmarks->difficulty ? ' selected': '').">$v</option>";
                            }
                            ?>
                        </select>
                        <button class="btn" aria-label="edit" title="edit" name="action" value="edit">
                            <span class="material-icons">edit</span>Edit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    <script>

      var modal = document.getElementById("modal");
      var btn = document.getElementsByName("edit");

      function edit(e){

        document.getElementById("id_bookmarks_modal").value = e.parentNode.children[5].children.namedItem("id_bookmarks").value;

        // Get values
        var title_value = e.parentNode.children.namedItem("title").value;
        var thumbail_value = e.parentNode.children.namedItem("thumbnail").value;
        var link_value = e.parentNode.children.namedItem("link").value;
        var difficulty_value = e.parentNode.children.namedItem("difficulty").value;

        // Set values
        document.getElementById("bookmarks-modal").value = title_value;
        document.getElementById("thumbnail-modal").value = thumbail_value;
        document.getElementById("link-modal").value = link_value;
        document.getElementById("difficulty-modal").value = difficulty_value;

        document.getElementsByName("action")[4].value = "edit";
        document.getElementsByName("action")[4].style.backgroundColor = "";
        document.getElementsByName("action")[4].textContent = "Edit";


        modal.style.display = "block";
      }

      window.onclick = function(event) {
        if (event.target == modal) {
          modal.style.display = "none";
        }
      }

      function add(e){

        document.getElementById("bookmarks-modal").value = "";
        document.getElementById("thumbnail-modal").value = "";
        document.getElementById("link-modal").value = "";
        document.getElementById("difficulty-modal").value = "";

        document.getElementsByClassName("title-modal").innerText = "Ajouter un Bookmarks";

        document.getElementsByName("action")[4].value = "add";
        document.getElementsByName("action")[4].style.backgroundColor = "green";
        document.getElementsByName("action")[4].textContent = "Ajouter";

        modal.style.display = "block";

      }

    </script>
