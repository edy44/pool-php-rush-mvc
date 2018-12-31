<?php $title_for_layout = "Recherche : ".$search; ?>

<div class="row">
    <div class="col s12">
        <?= $this->form->title('Résultats de la Recherche : '.$search); ?>
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

