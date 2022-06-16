<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="user_delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="modal-title text-truncate">
                        <i class="fa fa-fw fa-sm fa-trash-alt text-primary-900 mr-2"></i>
                        <span id="user_delete_modal_title"><?= l('delete_modal.header') ?></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <p class="text-muted" id="user_delete_modal_subheader"><?= l('delete_modal.subheader1') ?></p>

                <div class="mt-4">
                    <a href="" id="user_delete_modal_url" class="btn btn-lg btn-block btn-danger"><?= l('global.delete') ?></a>
                </div>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    /* On modal show load new data */
    $('#user_delete_modal').on('show.bs.modal', event => {
        let user_id = event.relatedTarget.getAttribute('data-user-id');
        event.currentTarget.querySelector('#user_delete_modal_url').setAttribute('href', `${url}admin/users/delete/${user_id}&global_token=${global_token}`);
        event.currentTarget.querySelector('#user_delete_modal_subheader').innerHTML = event.currentTarget.querySelector('#user_delete_modal_subheader').innerHTML.replace('%s', event.relatedTarget.getAttribute('data-modal-resource-name'));
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
