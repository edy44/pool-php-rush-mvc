<?php $title_for_layout = "Connexion"; ?>

<div class="row">
    <div class="col s6 offset-s3">
        <?= $this->form->title("Connexion"); ?>
        <form action="login" method="post">
            <div><?= (isset($this->form->getErrors()['connexion']))?$this->form->getErrors()['connexion']:''; ?></div>
                <?= $this->form->input('email', 'Email', 'email'); ?>
                <?= $this->form->input('password', 'Mot de Passe', 'password'); ?>
            <div>
                <input type="checkbox" name="remamber_me"/>
                <span>Remember Me</span>
            </div>
            <?= $this->form->submit('Se Connecter'); ?>
        </form>
        <div class="row">
            <p class="col s6">Vous n'êtes pas encore inscrit? Créer un compte</p><br>
            <?= $this->form->redirect('Créer Profil', 'create', 'btn-new', 'face'); ?>
        </div>
    </div>
</div>
