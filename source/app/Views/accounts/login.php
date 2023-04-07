
<?= $this->extend('layouts/'.$layout_Thema.'/one/layout') ?>
<?= $this->section('content') ?>


<body class="app flex-row align-items-center">
  <div class="container">
    <div class="row justify-content-center login-box">
      <div class="col-md-6">
        <div class="card-group">
          <div class="card p-4">
            <form name="accform" id="accform">
                <div class="card-body">
                <h2 class="title text-center mb-4">
                  <div class="s-tit">Intranet</div>
                  <div class="m-tit">SIWONSCHOOL</div>
                </h2>
                <div class="input-group mb-3">
                    <input type="text" class="form-control input-st1" name="acc_id" placeholder="아이디">
                </div>
                <div class="input-group mb-4">
                    <input type="password" class="form-control input-st1" name="acc_pw" placeholder="비밀번호">
                </div>
                <?php if( $PLACE == 'OUT' ){ ?>
                  <div class="input-group mb-4">
                      <input type="text" class="form-control input-st1" name="acc_cnumber" placeholder="인증번호">
                      <span class="input-group-append">
                        <button type="button" class="btn btn-dark rounded" onclick="sendNumber()">인증번호 발송</button>
                      </span>
                  </div>
                <?php } ?>
                <div class="row">
                  <button type="button" class="btn btn-siwon btn-lg btn-block rounded" onclick="accounts()">Login</button>
                    <?php if( $PLACE == 'IN' ){ ?>
                      <a href="/accounts/register" class="btn btn-link btn-lg btn-block rounded" >Register</a>
                    <?php } ?>
                </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
<?= $this->endSection() ?>


