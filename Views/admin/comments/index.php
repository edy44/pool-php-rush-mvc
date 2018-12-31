<?php $title_for_layout = "Liste des Commentaires"; ?>

<h5 class="center-align">Liste des Commentaire existants</h5><br/>

<table class="centered responsive-table highlight">
    <thead>
    <tr>
        <th><h6 class="z-depth-5">Publié le</h6></th>
        <th><h6 class="z-depth-5">Utilisateur</h6></th>
        <th><h6 class="z-depth-5">Contenu</h6></th>
        <th><h6 class="z-depth-5">Actions</h6></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($comments as $comment): ?>
        <tr>
            <td><?= $comment['date']; ?></td>
            <td><?= $comment['user_name']; ?></td>
            <td><?= $comment['content']; ?></td>
            <td>
                <?= $this->form->redirect('Supprimer', '../delete/'.$comment['id'].DS.$article_id, 'btn-delete', 'clear'); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="row">
    <div class="col s6 offset-s3">
        <?= $this->form->subtitle ('Laissez votre Commentaire'); ?>
        <form action="<?= $this->form->url('admin/comments/create/').$article_id; ?>" method="post">
            <?= $this->form->input('content', '', 'textarea'); ?>
            <?= $this->form->submit('Valider'); ?>
        </form>
    </div>
</div>