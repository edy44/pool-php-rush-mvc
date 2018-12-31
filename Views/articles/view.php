<?php $title_for_layout = $article['title']; ?>

<?= $this->form->title($article['title']); ?>

<div class="share col-12" data-toggle="tooltip" title="Partager la Page sur les Réseaux Sociaux">
    <label class="icon_share"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></label>
    <button type="button" class="btn share_btn share_twitter" data-toggle="tooltip" title="Partager la Page sur Twitter">
        <img class="share_img" alt="Partage Twitter" src="">
    </button>
    <button type="button" class="btn share_btn share_facebook" data-toggle="tooltip" title="Partager la Page sur Facebook">
        <img class="share_img" alt="Partage Facebook" src="">
    </button>
    <button type="button" class="btn share_btn share_gplus" data-toggle="tooltip" title="Partager la Page sur Google+">
        <img class="share_img" alt="Partage Google+" src="">
    </button>
    <button type="button" class="btn share_btn share_linkedin" data-toggle="tooltip" title="Partager la Page sur Linkedin">
        <img class="share_img" alt="Partage Linkedin" src="">
    </button>
    <a href="mailto:?subject='.$title_for_layout.'&body=%0A%0A'.$title_for-layout.'%0A'">
        <button type="button" class="btn share_btn share_mail" data-toggle="tooltip" title="Partager la Page par Mail">
            <img class="share_img" alt="Partage Mail" src="">
        </button>
    </a>
</div>

<img src="<?= $this->form->webroot('Webroot/img/articles/').$article['img_path']; ?>">

<p>Catégorie : <?= $article['category_name']; ?></p>
<p>Tags : <?= $str_tags; ?></p>
<p><?= $article['description']; ?></p><br>
<em>Article publié le <?= $article['creation_date']; ?> par <?= $article['user_name']; ;?></em><br>
<em><?= ($article['creation_date']!=$article['creation_date'])?
        'Dernière modification le '.$article['modification_date']:''; ?></em>

<div class="row">
    <div class="col s6 offset-s3">
        <?= $this->form->subtitle('Laissez votre Commentaire'); ?>
        <form action="<?= $this->form->url('comments/create/').$article['id']; ?>" method="post">
            <?= $this->form->input('content', '', 'textarea'); ?>
            <?= $this->form->submit('Valider'); ?>
        </form>
        <?= (!empty($comments))?$this->form->subtitle('Commentaires'):''; ?>
        <?php foreach ($comments as $key => $comment): ?>
            <div class="row">
                <div class="col s12">
                    <div class="card blue-grey darken-1">
                        <div class="card-content white-text">
                            <span class="card-title">#<?= $key+1 ;?> <?= $comment['user_name'] ?></span>
                            <em>Publié le <?= $comment['date']; ?></em>
                            <p><?= $comment['content']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="<?= $this->form->webroot('Webroot/js/share.js'); ?>"></script>
