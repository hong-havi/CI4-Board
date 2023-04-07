
<?= $this->extend('layouts/'.$layout_Thema.'/default/layout') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-group">
                <div class="card p-4">
                    <div class="card-body">
                        <form>
                            <h1>내정보</h1>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">이름(실명)</span>
                                        <span>&nbsp;<?=$USER_INFO['name']?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">생년월일 <span class="text-danger">*</span></span>
                                        <span>&nbsp;<?=date("Y-m-d",strtotime($USER_INFO['birth1'].$USER_INFO['birth2']))?></span>
                                        <span>&nbsp;<?=$USER_INFO['birthtype_txt']?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">성별 </span>
                                        <span>&nbsp;<?=$USER_INFO['sex_txt']?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">이메일</span>
                                    </div>
                                    <input type="text" id="email" name="email" class="form-control" placeholder="Email" value="<?=$USER_INFO['email']?>">
                                </div>
                                <span class="help-block">※ 가입 후 사내에서 할당받은 사내 이메일을 입력해 주시기 바랍니다.</span>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">휴대전화</span>
                                    </div>
                                    <input type="text" id="tel1" name="tel1" class="form-control form-hphone" value="<?=$USER_INFO['tel1']?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">내선번호</span>
                                    </div>
                                    <input type="text" id="tel2" name="tel2" class="form-control form-iphone" value="<?=$USER_INFO['tel2']?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">우편번호</span>
                                    </div>
                                    <input type="text" id="postnum" name="postnum" class="form-control" readonly placeholder="" value="<?=$USER_INFO['zip']?>">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-Light" onclick="Postnum.open()"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">주소</span>
                                    </div>
                                    <input type="text" id="addr1" name="addr1" class="form-control" readonly placeholder="" value="<?=$USER_INFO['addr1']?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">상세주소</span>
                                    </div>
                                    <input type="text" id="addr2" name="addr2" class="form-control" placeholder="" value="<?=$USER_INFO['addr2']?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary px-4 btn-loading">수정</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>



