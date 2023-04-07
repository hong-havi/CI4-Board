<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">파일 정보수정</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <form name="fedit-form" id="fedit-form">
            <input type="hidden" name="fuid" value="<?=$finfo['uid']?>"/>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="text-input">파일명</label>
                <div class="col-md-8">
                    <?=$finfo['name']?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="text-input">파일태그</label>
                <div class="col-md-8">
                    <select name="f_tag">
                        <option value="">::선택::</option>
                        <?php foreach( $ftaglists as $ftag ){ ?>
                        <option value="<?=$ftag?>" <?=(($finfo['caption']==$ftag) ? "selected" : "")?> ><?=$ftag?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="text-input">버전</label>
                <div class="col-md-8">
                <input type="text" id="text-input" name="f_version" class="form-control input-st2" placeholder="버전" value="<?=$finfo['version']?>">
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-dark btn-sm px-3 mr-2" data-dismiss="modal" aria-label="Close" type="button">닫기</button>
        <button class="btn btn-dark btn-sm px-3" id="att-edit-btn" type="button">수정</button>
    </div>
</div>