<?php $title_for_layout = "Mon Profil"; ?>
<?= $this->form->title("Modifier mon Profil"); ?>

<form action="edit" method="post">
    <?= $this->form->input('username', 'Nom'); ?>
    <?= $this->form->input('email', 'Email', 'email'); ?>
    <?= $this->form->input('password', 'Mot de Passe', 'password'); ?>
    <?= $this->form->input('password_confirm', 'Confirmation du Mot de Passe', 'password'); ?>
    <?= $this->form->submit('Valider'); ?>
</form>