<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.password_generator.name') ?></li>
        </ol>
    </nav>

    <div class="row mb-4">
        <div class="col-12 col-xl d-flex align-items-center mb-3 mb-xl-0">
            <h1 class="h4 m-0"><?= l('tools.password_generator.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.password_generator.description') ?>">
                    <i class="fa fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="characters"><i class="fa fa-fw fa-sort-numeric-up fa-sm text-muted mr-1"></i> <?= l('tools.password_generator.characters') ?></label>
                    <input type="number" min="1" max="2048" id="characters" name="characters" class="form-control <?= \Altum\Alerts::has_field_errors('characters') ? 'is-invalid' : null ?>" value="<?= $data->values['characters'] ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('characters') ?>
                </div>

                <div class="custom-control custom-checkbox mb-3">
                    <input id="numbers" name="numbers" type="checkbox" class="custom-control-input" <?= $data->values['numbers'] ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="numbers">
                        <?= l('tools.password_generator.numbers') ?>
                    </label>
                </div>

                <div class="custom-control custom-checkbox mb-3">
                    <input id="symbols" name="symbols" type="checkbox" class="custom-control-input" <?= $data->values['symbols'] ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="symbols">
                        <?= l('tools.password_generator.symbols') ?>
                    </label>
                </div>

                <div class="custom-control custom-checkbox mb-3">
                    <input id="lowercase" name="lowercase" type="checkbox" class="custom-control-input" <?= $data->values['lowercase'] ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="lowercase">
                        <?= l('tools.password_generator.lowercase') ?>
                    </label>
                </div>

                <div class="custom-control custom-checkbox mb-3">
                    <input id="uppercase" name="uppercase" type="checkbox" class="custom-control-input" <?= $data->values['uppercase'] ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="uppercase">
                        <?= l('tools.password_generator.uppercase') ?>
                    </label>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.submit') ?></button>
            </form>

        </div>
    </div>

    <?php if(isset($data->result)): ?>
        <div class="mt-4">

            <div class="card">
                <div class="card-body">

                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="result"><?= l('tools.password_generator.result') ?></label>
                            <div>
                                <button
                                        type="button"
                                        class="btn btn-link text-muted"
                                        data-toggle="tooltip"
                                        title="<?= l('global.clipboard_copy') ?>"
                                        aria-label="<?= l('global.clipboard_copy') ?>"
                                        data-copy="<?= l('global.clipboard_copy') ?>"
                                        data-copied="<?= l('global.clipboard_copied') ?>"
                                        data-clipboard-target="#result"
                                        data-clipboard-text
                                >
                                    <i class="fa fa-fw fa-sm fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <textarea id="result" class="form-control"><?= $data->result ?></textarea>
                    </div>

                </div>
            </div>

        </div>
    <?php endif ?>

    <div class="mt-4">
        <div class="card">
            <div class="card-body">
                <?= l('tools.password_generator.extra_content') ?>
            </div>
        </div>
    </div>
</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
