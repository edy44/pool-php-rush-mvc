<!doctype html>
<html lang="fr">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title><?= $title_for_layout; ?></title>
        <link href="<?= $this->form->webroot('Webroot/css/materialize.min.css'); ?>" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="<?= $this->form->webroot('Webroot/css/modal.css'); ?>" rel="stylesheet">
    </head>

    <body>

        <div class="navbar-fixed">
            <nav>
                <div class="nav-wrapper">
                    <a class="brand-logo">Mon super site</a>
                </div>
            </nav>
        </div>

        <main>
            <div class="container">
                <div class="alert-flash"><?= $this->getFlash() ?></div>
                <?= $content_for_layout; ?>
            </div>
        </main>

        <footer class="page-footer">
            <div class="footer-copyright">
                <div class="container">
                    © 2018 Coding-Academy - Tous Droits Réservés
                    <a class="grey-text text-lighten-4 right" href="">Liens</a>
                </div>
            </div>
        </footer>

        <script src="<?= $this->form->webroot('Webroot/js/materialize.min.js'); ?>"></script>
    </body>
</html>