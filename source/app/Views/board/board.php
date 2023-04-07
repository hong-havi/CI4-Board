
<?= $this->extend('layouts/'.$layout_Thema.'/default/layout') ?>
<?= $this->section('content') ?>
    <?= $this->include('board/'.$bbs_template.'/'.$view_mode) ?>
<?= $this->endSection() ?>