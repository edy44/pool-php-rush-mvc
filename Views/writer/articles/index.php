<?php $title_for_layout = "Liste des Articles"; ?>

<button class="waves-effect waves-light btn" onclick="location.href='create'" type="button">
    <i class="material-icons left">add_box</i>Nouvel Article</button>

<h5>Liste des Articles</h5><br/>

<table class="centered responsive-table highlight">
    <thead>
        <tr>
            <th><h6>Date de Modification</h6></th>
            <th><h6>Titre</h6></th>
            <th><h6>Catégorie</h6></th>
            <th><h6>Auteur</h6></th>
            <th><h6>Actions</h6></th>
            <th><h6>Commentaires</h6></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($articles as $article): ?>
        <tr>
            <td><?= $article['modification_date']; ?></td>
            <td><?= $article['title']; ?></td>
            <td><?= $article['category_name']; ?></td>
            <td><?= $article['user_name']; ?></td>
            <td>
                <?= $this->form->redirect('Modifier', 'edit/'.$article['id'], 'btn-edit', 'edit'); ?>
                <?= $this->form->redirect('Supprimer', 'delete/'.$article['id'], 'btn-delete', 'clear'); ?>
            </td>
            <td>
                <?= $this->form->redirect('Commentaires', '../comments/index/'.$article['id'], 'btn-comment', 'chat'); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
