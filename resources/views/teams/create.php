<?php

use App\Services\CsrfService;

?>
<div class="container card">
    <h1>Créer une équipe</h1>
    <form method="post" enctype="multipart/form-data">
        <?= CsrfService::html(); ?>
        <div class="form-group">
            <h3 style="text-align: left">Icon:</h3>
            <div class="file-upload">
                <button class="file-upload-btn" type="button" onclick="$('.file-upload-input').trigger( 'click' )">Add
                    Image
                </button>
                <div class="image-upload-wrap">
                    <input class="file-upload-input" type='file' name="file" onchange="readURL(this);"
                           accept="image/*"/>
                    <div class="drag-text">
                        <h3>Déplacer un fichier ici !</h3>
                    </div>
                </div>
                <div class="file-upload-content">
                    <img class="file-upload-image" src="#"
                         alt="your image"/>
                    <div class="image-title-wrap">
                        <button type="button" onclick="removeUpload()" class="remove-image">Supprimer <span
                                    class="image-title"></span></button>
                    </div>
                </div>
            </div>
            <div class="input-auth">
                <label for="name" class="textfield">
                    <input type="text" id="name" name="name" required aria-required="true" autofocus spellcheck="false"
                           title="Nom de l'équipe" dir="ltr" autocapitalize="characters">
                    <span aria-hidden="true">Nom de l'équipe</span>
                </label>
            </div>
            <div class="input-auth">
                <label for="description" class="textareafield margin-top30">
                    <textarea type="text" id="description" name="description"
                              title="Description de l'équipe" dir="ltr"
                              autocapitalize="characters"></textarea>
                    <span aria-hidden="true">Description</span>
                </label>
            </div>

            <div class="btn-container">
                <br>
                <div class="button">
                    <button class="btn btn-green">
                        <span class="material-icons">add</span>
                        Créer
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
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
