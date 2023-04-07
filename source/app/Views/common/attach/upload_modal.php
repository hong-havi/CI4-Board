<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">파일 업로드</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="upoad_modal_fidx" value="" />
        <input type="hidden" name="upoad_modal_gidx" value="<?=$gidx?>" />
        <input type="hidden" name="attach_tempcode" value="<?=TEMPCODE?>" />
        <div class="fzone-explain">
            <p>파일 사이즈 최대 <span class="text-danger"><?=$maxsize?></span>까지만 가능합니다.</p>
            <p>파일 추가 후 반드시 아래의 <span class="text-danger">첨부버튼</span>을 눌러야 첨부가 됩니다.</p>
        </div>
        <div class="fzone-btn">
            <label for="fzone-fileBtn" class="btn btn-siwon btn-sm px-3">파일업로드</label>
			<input type="file" id="fzone-fileBtn" name="files[]" multiple="" style="display:none">
        </div>
        <div class="fzone-list-info">
            0KB / 0개
        </div>
        <div class="fzone-list">
            <ul>
            </ul>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-dark btn-sm px-3 mr-2" data-dismiss="modal" aria-label="Close" type="button">닫기</button>
        <button class="btn btn-dark btn-sm px-3" id="attach-submit" type="button">첨부</button>
    </div>
</div>