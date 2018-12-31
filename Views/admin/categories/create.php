<?php $title_for_layout = "Nouvelle Catégorie"; ?>

<?= $this->form->title("Nouvelle Catégorie"); ?>

<form action="create" method="post">
    <?= $this->form->input('name', 'Nom'); ?>
    <?= $this->form->submit('Valider'); ?>
</form>