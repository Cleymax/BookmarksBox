    <footer>
        <span>Tout droit réservés &copy; 2022</span>
        <span>Par Clément PERRIN et Raphaël HIEN avec <span class="material-icons">favorite</span></span>
    </footer>
    <!-- js -->
    <?php if ($_ENV['MODE'] == 'dev') {
        echo $render->render();
    } ?>
    <script async src="<?= $_ENV['BASE_URL'] ?>/js/app.js"></script>
</body>
</html>
