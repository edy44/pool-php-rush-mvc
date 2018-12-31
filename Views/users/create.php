<?php $title_for_layout = "Inscription"; ?>

<div class="row">
    <div class="col s8 offset-s2">
        <?= $this->form->title("Formulaire d'inscription"); ?>
        <form action="create" method="post">
            <?= $this->form->input('username', 'Nom'); ?>
            <?= $this->form->input('email', 'Email', 'email'); ?>
            <?= $this->form->input('password', 'Mot de Passe', 'password'); ?>
            <?= $this->form->input('password_confirm', 'Confirmation du Mot de Passe', 'password'); ?>
            <?= $this->form->submit('Valider'); ?>
        </form>
    </div>
</div>
