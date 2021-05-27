<div class="container card">
    <h3>Voulez vous vraiment quitter cette équipe !</h3>
    <br>
    <form method="post">
        <?= \App\Services\CsrfService::html() ?>
        <div class="btn-container">
            <div class="button">
                <button class="btn btn-red">Quitter</button>
            </div>
        </div>
    </form>
</div>
