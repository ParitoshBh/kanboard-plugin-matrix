<h3><img src="<?= $this->url->dir() ?>plugins/Matrix/matrix-logo.svg"/></h3>
<div class="panel">
    <?= $this->form->label(t('Server Url'), 'matrix_server_url') ?>
    <?= $this->form->text('matrix_server_url', $values) ?>

    <?= $this->form->label(t('Room ID'), 'matrix_room_id') ?>
    <?= $this->form->text('matrix_room_id', $values) ?>

    <?= $this->form->label(t('Access Token'), 'matrix_access_token') ?>
    <?= $this->form->text('matrix_access_token', $values) ?>

    <div class="form-actions">
        <input type="submit" value="<?= t('Save') ?>" class="btn btn-blue"/>
    </div>
</div>