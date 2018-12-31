<!doctype html>
<html lang="fr">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title><?= $title_for_layout; ?> - Mon Super Site</title>
        <link href="<?= $this->form->webroot('Webroot/css/materialize.min.css'); ?>" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="<?= $this->form->webroot('Webroot/css/default.css'); ?>" rel="stylesheet">
    </head>

    <body>
        <div class="navbar-fixed">
            <nav>
                <div class="nav-wrapper">
                    <a href="<?= $this->form->url('articles/index'); ?>" class="brand-logo">Mon super site</a>
                    <ul class="right hide-on-med-and-down">
                        <li>
                            <form action="<?= $this->form->url('articles/search'); ?>" method="get">
                                <div class="input-field">
                                    <input class="search" name="search" type="search" value="" placeholder="Rechercher Articles...">
                                    <label class="label-icon" for="search"><button class="btn-search" type="submit"><i class="material-icons left">search </i></button></label>
                                    <i class="material-icons">close</i>
                                </div>
                            </form>
                        <li>
                        <li><a href="<?= $this->form->url('articles/index'); ?>">Articles</a></li>
                        <li><a href="<?= $this->form->url('users/edit'); ?>">Profil</a></li>
                        <li><a href="<?= ($this->session->read('Writer'))?$this->form->url('writer/articles/index'):''; ?>">
                                <?= ($this->session->read('Writer'))?'Ecriture':''; ?></a></li>
                        <li><a href="<?= ($this->session->read('Writer'))?$this->form->url('writer/articles/index'):''; ?>">
                                <?= ($this->session->read('Writer'))?'Auteur':''; ?></a></li>
                        <li><a href="<?= ($this->session->read('Admin'))?$this->form->url('admin/articles/index'):''; ?>">
                                <?= ($this->session->read('Admin'))?'Administration':''; ?></a></li>
                        <li><a href="<?= $this->form->url('users/logout'); ?>">Déconnexion</a></li>
                    </ul>
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
                    <a class="grey-text text-lighten-4 right" href="">More Links</a>
                </div>
            </div>
        </footer>

        <script src="<?= $this->form->webroot('Webroot/js/materialize.min.js'); ?>"></script>
    </body>
</html>
