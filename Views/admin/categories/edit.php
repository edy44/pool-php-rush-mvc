<?php $title_for_layout = "Modifier la Catégorie"; ?>

<?= $this->form->title("Modifier la Catégorie"); ?>

<form action="<?= $this->form->url('admin/categories/edit/').$id; ?>" method="post">
    <?= $this->form->input('name', 'Nom'); ?>
    <?= $this->form->submit('Valider'); ?>
</form>