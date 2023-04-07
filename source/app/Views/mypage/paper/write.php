
<?= $this->extend('layouts/'.$layout_Thema.'/one/layout') ?>
<?= $this->section('content') ?>


<body class="app flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card-group">
                    <div class="card ">

                        <form name="accform" id="accform">
                            <div class="card-body">
                                <div class="row">
                                    <h1>Write</h1>
                                </div>
                                <div class="row sender-choice">
                                </div>
                                <div class="form-group row">
                                    <textarea id="textarea-input" name="textarea-input" rows="9" class="form-control" placeholder=""></textarea>
                                </div>
                            </div>                                        
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?= $this->endSection() ?>


