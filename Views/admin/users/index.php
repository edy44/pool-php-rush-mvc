<?php $title_for_layout = "Liste des Utilisateurs"; ?>

<button class="waves-effect waves-light btn" onclick="location.href='create'" type="button">
    <i class="material-icons left">add_box</i>Nouvel Utilisateur</button>

<h5 class="center-align">Liste des Utilisateurs</h5><br/>

<table class="centered responsive-table highlight">
    <thead>
    <tr>
        <th><h6 class="z-depth-5">Statut</h6></th>
        <th><h6 class="z-depth-5">Nom</h6></th>
        <th><h6 class="z-depth-5">Email</h6></th>
        <th><h6 class="z-depth-5">Droits</h6></th>
        <th><h6 class="z-depth-5">Actions</h6></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><a class="btn waves-effect waves-light status-<?= ($user['status']==0)?'open':'lock'; ?>" type="button" href="<?= $this->form->url('admin/users/status/').$user['id']; ?>">
                    <i class="material-icons left"><?= ($user['status']==0)?'lock_open':'lock'; ?></i><?= $user['status_name']; ?></a></td>
            <td><?= $user['username']; ?></td>
            <td><?= $user['email']; ?></td>
            <td><?= $user['rights']; ?></td>
            <td>
                <?= $this->form->redirect('Modifier', 'edit/'.$user['id'], 'btn-edit', 'edit'); ?>
                <?= $this->form->redirect('Supprimer', 'delete/'.$user['id'], 'btn-delete', 'clear'); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>