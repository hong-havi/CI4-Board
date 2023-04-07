<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">검색결과</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="ws-find-lists">
            <table class="table-st3">
                <tr>
                    <th>부서</th>
                    <th>이름</th>
                    <th>직급/직책</th>
                    <th>선택</th>
                </tr>
                <?php foreach( $ulists as $data ){ ?>
                <tr>
                    <td class="text-center"><?=$data['gname']?></td>
                    <td class="text-center"><?=$data['name']?></td>
                    <td class="text-center"><?=$data['lname']?></td>
                    <td class="text-center">
                        <button class="btn btn-dark btn-sm" type="button" onclick="ws_dashboard.getPeople('<?=$data['memberuid']?>')">선택</button>
                    </td>
                </tr>
                <?php } ?>
                <?php if( count($ulists) == 0 ){ ?>
                <tr>
                    <td colspan="4" class="text-center">검색 결과가 없습니다.</td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-dark btn-sm px-3 mr-2" data-dismiss="modal" aria-label="Close" type="button">닫기</button>
    </div>
</div>