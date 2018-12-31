<?php $title_for_layout = "Modifier Utilisateur"; ?>

<?= $this->form->title("Modifier Utilisateur"); ?>

<form action="<?= $this->form->url('admin/users/edit/').$id; ?>" method="post">
    <?= $this->form->input('username', 'Nom'); ?>
    <?= $this->form->input('email', 'Email', 'email'); ?>
    <?= $this->form->input('password', 'Mot de Passe', 'password'); ?>
    <?= $this->form->input('password_confirm', 'Confirmation du Mot de Passe', 'password'); ?>
    <label>Droits Utilisateur</label>
    <select class="browser-default" name="rights" value="<?= $rights; ?>">
        <option value="0" selected>Utilisateur</option>
        <option value="1">Ecriture</option>
        <option value="2">Administrateur</option>
    </select><br>
    <?= $this->form->submit('Valider'); ?>
</form>
