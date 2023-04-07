
<?= $this->extend('layouts/'.$layout_Thema.'/one/layout') ?>
<?= $this->section('content') ?>
<body class="app flex-row align-items-center">
  <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-group">
                <div class="card p-4">
                    <div class="card-body">
                        <?= form_open('/accounts/register',['id' => 'accform']) ?>
                            <h1>Register</h1>
                            <p class="help-block">(<span class="text-danger">*</span>) 표시가 있는 항목은 반드시 입력해야 합니다.</p>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">이름(실명) <span class="text-danger">*</span></span>
                                    </div>
                                    <input type="text" id="uname" name="uname" class="form-control" placeholder="Username">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">생년월일 <span class="text-danger">*</span></span>
                                    </div>
                                    <input type="text" id="birth" name="birth" class="form-control form-date" placeholder="____-__-__">
                                    <div class="form-check  form-check-inline ml-1">
                                        <input class="form-check-input" type="checkbox" name="birthtype" id="birthtype-radio" value="1">
                                        <label class="form-check-label" for="birthtype-radio">음력</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">성별 <span class="text-danger">*</span></span>
                                    </div>
                                    <div class="col-md-9 col-form-label">
                                        <div class="form-check  form-check-inline mr-1">
                                            <input class="form-check-input" type="radio" name="sex" id="sex-radio1" value="1">
                                            <label class="form-check-label" for="sex-radio1">남성</label>
                                        </div>
                                        <div class="form-check  form-check-inline mr-1">
                                            <input class="form-check-input" type="radio" name="sex" id="sex-radio2" value="2">
                                            <label class="form-check-label" for="sex-radio2">여성</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">아이디 <span class="text-danger">*</span></span>
                                    </div>
                                    <input type="text" id="userid" name="userid" class="form-control" placeholder="ID">
                                </div>
                                <span class="help-block">※ 8~20자의 영문(소문자)과 숫자만 사용할 수 있습니다.</span>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">비밀번호 <span class="text-danger">*</span></span>
                                    </div>
                                    <input type="password" id="upassword" name="upassword" class="form-control" placeholder="Password">
                                </div>
                                <span class="help-block">※ 영문,숫자,특수문자(!@#$%^&*()_-)포함 8~20자만 사용할 수 있습니다.</span>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">비밀번호 확인 <span class="text-danger">*</span></span>
                                    </div>
                                    <input type="password" id="upassword_confirm" name="upassword_confirm" class="form-control" placeholder="Password Confirm">
                                </div>
                                <span class="help-block">※ 비밀번호를 한번 더 입력하세요. 비밀번호는 잊지 않도록 주의하시기 바랍니다.</span>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">이메일</span>
                                    </div>
                                    <input type="text" id="email" name="email" class="form-control" placeholder="Email">
                                </div>
                                <span class="help-block">※ 가입 후 사내에서 할당받은 사내 이메일을 입력해 주시기 바랍니다.</span>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">내선번호</span>
                                    </div>
                                    <input type="text" id="tel2" name="tel1" class="form-control form-iphone" placeholder="__-____-____">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">휴대전화</span>
                                    </div>
                                    <input type="text" id="tel1" name="tel2" class="form-control form-hphone" placeholder="___-____-____">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">우편번호</span>
                                    </div>
                                    <input type="text" id="postnum" name="postnum" class="form-control" readonly placeholder="">
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
                                    <input type="text" id="addr1" name="addr1" class="form-control" readonly placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">상세주소</span>
                                    </div>
                                    <input type="text" id="addr2" name="addr2" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary px-4 btn-loading">Register</button>
                                    <a type="button" class="btn btn-light px-4" href="/accounts/login">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</body>

<?= $this->endSection() ?>


