<div class="modal-header">
        <h4 class="modal-title">수신확인</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <table class="table-st2">
            <thead>
                <tr>
                    <th>구분</th>
                    <th>이름</th>
                    <th>확인시간</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $sender_lists as $cate => $sdatas ){ ?>
                    <?php if($cate == 'full_uid') continue; ?>
                    <?php foreach( $sdatas as $ptype => $sdatalist ){ ?>
                        <?php foreach( $sdatalist as  $data ){ ?>
                            <tr>
                                <td><?=(($cate=='1') ? "수신" : "참조")?></td>
                                <td><?=$data['iname']?></td>
                                <td><?=(($data['con_date'] =='0000-00-00 00:00:00') ? "-" : $data['con_date'])?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>