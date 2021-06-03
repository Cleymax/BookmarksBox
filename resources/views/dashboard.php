<div class="menu-info" id="menu" style="display: none">
    <span class="material-icons info-close">close</span>
    <img src="https://placeimg.com/200/200/any" class="img-info">
    <h3 class="flex-center">Mon Bookmarks</h3>
    <div class="info">
        <h5><span class="material-icons">description</span> Description</h5>
        <textarea class="description-info" disabled="disabled">Description</textarea>
        <h5><span class="material-icons">link</span> Liens</h5>
        <textarea disabled="disabled">liens</textarea>
    </div>
</div>

<div class="flex" style="justify-content: space-evenly;gap:15px;">
    <?php
    foreach ($data as $bookmarks) {
        ?>
        <div class="bookmark">
            <img width="200px" height="200px" src="<?= $bookmarks->thumbnail ?>"
                 alt="Avatar de l'équpe <?= $bookmarks->title ?>">
            <h3>
                <?= $bookmarks->title ?>
            </h3>
            <div class="bookmark-infos">
                <h4>
                    <span class="material-icons" style="margin-right: 5px">schedule</span><?= $bookmarks->reading_time ?>
                </h4>
                <h4>
                    Difficultés : <?= $bookmarks->difficulty ?>
                </h4>
            </div>
            <div class="flex-row">
                <input type="hidden" name="title" id="title" value="<?= $bookmarks->title ?>">
                <input type="hidden" name="thumbnail" id="thumbnail" value="<?= $bookmarks->thumbnail ?>">
                <input type="hidden" name="link" id="link" value="<?= $bookmarks->link ?>">
                <input type="hidden" name="difficulty" value="<?= $bookmarks->difficulty ?>">
            </div>
        </div>
        <?php
    }
    ?>

    <div class="modal" id="modal">
        <div class="modal-frame">
            <h4>Edit Settings</h4>
            <form method="post">
                <label for="title-modal" class="textfield">
                    <input type="text" id="title-modal" autocomplete="title" name="title" spellcheck="false"
                           tabindex="2" aria-label="Titre" required aria-required="true"
                           title="Titre"
                           autocapitalize="none" dir="ltr">
                    <span aria-hidden="true">Titre</span>
                </label>
                <label for="link-modal" class="textfield">
                    <input type="text" id="link-modal" autocomplete="link" name="link" spellcheck="false"
                           tabindex="2" aria-label="Liens" required aria-required="true"
                           title="Liens"
                           autocapitalize="none" dir="ltr">
                    <span aria-hidden="true">Liens</span>
                </label>
                <label for="thumbnail-modal" class="textfield">
                    <input type="text" id="thumbnail-modal" autocomplete="thumbnail" name="thumbnail"
                           spellcheck="false"
                           tabindex="2" aria-label="Thumbnail" required aria-required="true"
                           title="Thumbnail"
                           autocapitalize="none" dir="ltr">
                    <span aria-hidden="true">Thumbnail</span>
                </label>
                <select name="difficulty" id="difficulty-modal">
                    <?php
                    $value = ["EASY", "MEDIUM", "DIFFICILE", "PRO"];

                    foreach ($value as $v) {
                        echo "<option value=" . $v . "" . ($v == $bookmarks->difficulty ? ' selected' : '') . ">$v</option>";
                    }
                    ?>
                </select>
                <button class="btn" aria-label="edit" title="edit" name="edit-modal"><span
                        class="material-icons">edit</span>Edit
                </button>
            </form>
        </div>
    </div>
</div>

<script>

  const modal = document.getElementById('modal');
  // eslint-disable-next-line no-unused-vars
  const btn = document.getElementsByName('edit');

  // eslint-disable-next-line no-unused-vars
  function edit(e) {
    document.getElementById('id_bookmarks_modal').value = e.parentNode.children[5].children.namedItem('id_bookmarks').value;

    // Get values
    const title_value = e.parentNode.children.namedItem('title').value;
    const thumbail_value = e.parentNode.children.namedItem('thumbnail').value;
    const link_value = e.parentNode.children.namedItem('link').value;
    const difficulty_value = e.parentNode.children.namedItem('difficulty').value;

    // Set values
    document.getElementById('bookmarks-modal').value = title_value;
    document.getElementById('thumbnail-modal').value = thumbail_value;
    document.getElementById('link-modal').value = link_value;
    document.getElementById('difficulty-modal').value = difficulty_value;

    document.getElementsByName('action')[4].value = 'edit';
    document.getElementsByName('action')[4].style.backgroundColor = '';
    document.getElementsByName('action')[4].textContent = 'Edit';

    modal.style.display = 'block';
  }

  window.onclick = function (event) {
    if (event.target === modal) {
      modal.style.display = 'none';
    }
  };

  function addBookmarks(e) {
    document.getElementById('bookmarks-modal').value = '';
    document.getElementById('thumbnail-modal').value = '';
    document.getElementById('link-modal').value = '';
    document.getElementById('difficulty-modal').value = '';

    document.getElementById('thumbnail-modal').parentNode.style.display = "";
    document.getElementById('link-modal').parentNode.style.display = "";
    document.getElementById('difficulty-modal').style.display = "";
    document.getElementsByClassName('title-modal').innerText = 'Ajouter un Bookmarks';

    document.getElementsByName('action')[4].value = 'add';
    document.getElementsByName('action')[4].style.backgroundColor = 'green';
    document.getElementsByName('action')[4].textContent = 'Ajouter';

    modal.style.display = 'block';
  }

  function addFolders(e){
    console.log("yo");
    document.getElementById('bookmarks-modal').value = '';
    document.getElementById('thumbnail-modal').value = '';
    document.getElementById('link-modal').value = '';
    document.getElementById('difficulty-modal').value = '';

    document.getElementById('thumbnail-modal').parentNode.style.display = "none";
    document.getElementById('link-modal').parentNode.style.display = "none";
    document.getElementById('difficulty-modal').style.display = "none";

    document.getElementsByClassName('title-modal').innerText = 'Ajouter un dossier';

    document.getElementsByName('action')[4].value = 'add';
    document.getElementsByName('action')[4].style.backgroundColor = 'green';
    document.getElementsByName('action')[4].textContent = 'Ajouter';
  }

</script>
