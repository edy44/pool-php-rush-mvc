<?php $title_for_layout = "Nouvel Article"; ?>

<?= $this->form->title("Nouvel Article"); ?>

<form action="create" enctype="multipart/form-data" method="post">
    <?= $this->form->input('title', 'Titre'); ?>
    <?= $this->form->input('file', 'Image', 'file'); ?>
    <?= $this->form->input('description', 'Description', 'textarea'); ?>
    <label>Categorie</label>
    <select class="browser-default" name="category_id" value="<?= $category_id; ?>">
        <?php foreach ($categories as $category): ?>
            <option value="<?= $category['id']; ?>"><?= $category['name']; ?></option>
        <?php endforeach; ?>
    </select><br>
    <div id="tags" class="col s12">
        <label>Tags</label>
        <input id="input-tag" type="text" name="tag" value="">
        <a id="add-tag" class="btn-floating btn-large waves-effect waves-light red"><i class="material-icons">add</i></a>
    </div>
    <?= $this->form->submit('Valider'); ?>
</form>

<script src="<?= $this->form->webroot('Webroot/js/tags.js'); ?>"></script>
