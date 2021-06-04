<div class="settings-container">
    <h3>Changez votre photo de profil</h3>
    <form method="post">
        <?= \App\Services\CsrfService::html() ?>
        <div class="form-group">
            <div class="file-upload">
                <button class="file-upload-btn" type="button" onclick="$('.file-upload-input').trigger( 'click' )">Add Image</button>
                <div class="image-upload-wrap">
                    <input class="file-upload-input" type='file' onchange="readURL(this);" accept="image/*" />
                    <div class="drag-text">
                        <h3>Déplacer un fichier ici !</h3>
                    </div>
                </div>
                <div class="file-upload-content">
                    <img class="file-upload-image" src="#" alt="your image" />
                    <div class="image-title-wrap">
                        <button type="button" onclick="removeUpload()" class="remove-image">Supprimer <span class="image-title">Uploaded Image</span></button>
                    </div>
                </div>
            </div>
            <button class="btn" aria-label="Enregistrer" title="Enregistrer" style="
    			width: 250px;
		">
                <span class="material-icons">save</span>Enregistrer
            </button>
        </div>
    </form>
    <br><br><br><br><br>
    <files-uploader multiple></files-uploader>
</div>
<script src="<?= getenv('BASE_URL').'/debugbar/vendor/jquery/dist/jquery.min.js' ?>"></script>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {

            var reader = new FileReader();

            reader.onload = function(e) {
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
