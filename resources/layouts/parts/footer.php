<footer>
    <span>Tout droit réservés &copy; 2022</span>
    <span>Par Clément PERRIN et Raphaël HIEN avec <span class="material-icons">favorite</span></span>
</footer>
<!-- js -->
<?php use App\Security\Auth;

if ($_ENV['MODE'] == 'dev') {
    echo $render->render();
} ?>
<script>
    window.BB = {
        USER: <?= Auth::check() ? Auth::user()->id : 'null' ?>,
        BASE_URL: '<?= $_ENV['BASE_URL'] ?>'
    };
</script>
<script async src="<?= $_ENV['BASE_URL'] ?>/js/app.js"></script>
</body>
</html>
