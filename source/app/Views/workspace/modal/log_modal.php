<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">프로젝트 변경 로그</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="ws-logs-lists">
            <?= $this->include('workspace/list-log') ?>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-dark btn-sm px-3 mr-2" data-dismiss="modal" aria-label="Close" type="button">닫기</button>
    </div>
</div>