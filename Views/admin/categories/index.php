<?php $title_for_layout = "Liste des Catégories"; ?>

<button class="waves-effect waves-light btn" onclick="location.href='create'" type="button">
    <i class="material-icons left">add_box</i>Nouvelle Catégorie</button>

<h5 class="center-align">Liste des Catégories</h5><br/>

<table class="centered responsive-table highlight">
    <thead>
    <tr>
        <th><h6 class="z-depth-5">Nom</h6></th>
        <th><h6 class="z-depth-5">Actions</h6></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($categories as $category): ?>
        <tr>
            <td><?= $category['name']; ?></td>
            <td>
                <?= $this->form->redirect('Modifier', 'edit/'.$category['id'], 'btn-edit', 'edit'); ?>
                <?= $this->form->redirect('Supprimer', 'delete/'.$category['id'], 'btn-delete', 'clear'); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>