<?php $title_for_layout = "Liste des Articles"; ?>

<div class="row">
    <div class="col s8">
        <?= $this->form->title('Liste des Articles'); ?>
    </div>
    <div class="col s5" data-toggle="tooltip" title="Choisissez un filtre pour afficher les Articles">
        <label class="col s6">Trier les Articles </label>
        <select class="browser-default col s6" onchange="document.location = this.options[this.selectedIndex].value;">
            <option value="<?= $this->form->url('articles/index/modification_date_asc'); ?>"
                <?= ($sort=='modification_date_asc')?'selected':''; ?>>Par Date Croissante</option>
            <option value="<?= $this->form->url('articles/index/modification_date_desc'); ?>"
                <?= ($sort=='modification_date_desc')?'selected':''; ?>>Par Date Décroissante</option>
            <option value="<?= $this->form->url('articles/index/title_asc'); ?>"
                <?= ($sort=='title_asc')?'selected':''; ?>>Par Titre Croissant</option>
            <option value="<?= $this->form->url('articles/index/title_desc'); ?>"
                <?= ($sort=='title_desc')?'selected':''; ?>>Par Titre Décroissant</option>
        </select><br>
    </div>
</div>
<?php foreach ($articles as $article): ?>
    <div class="col s12">
        <div class="card horizontal">
            <div class="card-image">
                <img src="<?= $this->form->webroot('Webroot/img/articles/').$article['img_path']; ?>">
            </div>
            <div class="card-stacked">
                <span class="card-title"><a href="<?= $this->form->url('articles/view/').$article['id']; ?>">
                        <?= $article['title']; ?></a></span>
                <div class="card-content">
                    <p>Catégorie : <?= $article['category_name']; ?></p><br>
                    <p><?= $article['description']; ?>...</p><br>
                    <em>Article publié le <?= $article['creation_date']; ?> par <?= $article['user_name']; ;?></em><br>
                    <em><?= ($article['creation_date']!=$article['creation_date'])?
                            'Dernière modification le '.$article['modification_date']:''; ?></em>
                </div>
                <div class="card-action">
                    <a href="<?= $this->form->url('articles/view/').$article['id']; ?>">Voir Détails</a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

