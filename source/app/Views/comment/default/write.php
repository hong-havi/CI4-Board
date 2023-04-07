
<div class="cmt-write-area">
    <form name="cmt-write-form" id="cmt-write-form" action="<?=$write_form['action']?>" onsbumit="return false;">
        <input type="hidden" name="uptype" value="<?=$write_form['uptype']?>" />
        <input type="hidden" name="parent" value="<?=$write_form['parent']?>" />
        <input type="hidden" name="depth" value="<?=$write_form['depth']?>" />
        <input type="hidden" name="cmt_upload" class="<?=$write_form['sub_class']?>_upload" value="<?=$wform_data['upload']?>" />
        <div class="cmt-w-editor"><textarea class="<?=$write_form['sub_class']?>-editor" name="cmt_content"><?=$wform_data['content']?></textarea></div>
        <?php if( $write_form['depth'] == 1){ ?>
            <div class="<?=$write_form['sub_class']?>-attach"></div>
        <?php } ?>
        <div class="cmt-btn-area">
            <?php if( $write_form['depth'] == 1){ ?>
            <div class="cmt-btn-left">
                <div class="form-check form-check-inline checkbox checkbox-st1">
                    <input class="form-check-input" type="checkbox" value="1" id="<?=$write_form['sub_class']?>_opt_hidden" name="cmt_opt_hidden">
                    <label class="form-check-label" for="<?=$write_form['sub_class']?>_opt_hidden" data-toggle="tooltip" data-placement="bottom" title="체크시 글 작성자에게만 댓글 열람">비밀댓글</label>
                </div>
            </div>
            <?php } ?>
            <div class="cmt-btn-right">
                <div class="form-check form-check-inline checkbox checkbox-st1">                            
                    <input class="form-check-input" type="checkbox" value="1" id="<?=$write_form['sub_class']?>_opt_sender_1" name="cmt_opt_sender_1">
                    <label class="form-check-label" for="<?=$write_form['sub_class']?>_opt_sender_1" data-toggle="tooltip" data-placement="bottom" title="체크시 글 수신인 전체에게 알림" >수신인</label>
                </div>
                <div class="form-check form-check-inline checkbox checkbox-st1">                            
                    <input class="form-check-input" type="checkbox" value="1" id="<?=$write_form['sub_class']?>_opt_sender_2"  name="cmt_opt_sender_2">
                    <label class="form-check-label" for="<?=$write_form['sub_class']?>_opt_sender_2" data-toggle="tooltip" data-placement="bottom" title="체크시 글 참조인 전체에게 알림" >참조인</label>
                </div>
                <button type="button" class="btn btn-siwon cmt_write_btn">등록</button>
            </div>
        </div>
    </form>
</div>