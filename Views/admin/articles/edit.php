<?php $title_for_layout = "Modifier l'Article"; ?>

<?= $this->form->title("Modifier l'Article"); ?>

<form action="<?= $this->form->url('admin/articles/edit/').$id; ?>" enctype="multipart/form-data" method="post">
    <?= $this->form->input('title', 'Titre'); ?>
    <?= $this->form->input('file', 'Image', 'file'); ?>
    <img src="<?= $this->form->webroot('Webroot/img/articles/').$img_path; ?>" >
    <?= $this->form->input('description', 'Description', 'textarea'); ?>
    <label>Categorie</label>
    <select class="browser-default" name="category_id" value="<?= $category_id; ?>">
        <?php foreach ($categories as $category): ?>
            <option value="<?= $category['id']; ?>" <?= ($category['id']==$category_id)?'selected':''; ?>><?= $category['name']; ?></option>
        <?php endforeach; ?>
    </select><br>
    <div id="tags" class="col s12">
        <label>Tags</label>
        <input id="input-tag" type="text" name="tag" value="">
        <a id="add-tag" class="btn-floating btn-large waves-effect waves-light red"><i class="material-icons">add</i></a>
        <input id="tag-number" type="hidden" value="<?= count($tags)?>">
        <?php foreach ($tags as $number => $tag): ?>
            <div id="tag-div-<?= $number+1; ?>" class="tag-div">
                <input name="tag_<?= $number+1; ?>" class="tags" type="hidden" value="<?= $tag['name']; ?>">
                <p><?= $tag['name']; ?></p>
                <a id="tag-del-<?= $number+1; ?>" class="btn-floating btn-large waves-effect waves-light btn-tag-del red"><i class="material-icons">clear</i></a>
            </div>
        <?php endforeach; ?>
    </div>
    <?= $this->form->submit('Valider'); ?>
</form>

<script src="<?= $this->form->webroot('Webroot/js/tags.js'); ?>"></script>
