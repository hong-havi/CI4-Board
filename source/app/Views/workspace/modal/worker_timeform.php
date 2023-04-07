<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">작업 시간 입력</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="worker-time">
            <form name="ws_worker_tform" id="ws_worker_tform">
                <input type="hidden" name="wt_idx" value="<?=$wtdata['wt_idx']?>" />
                <table class="table-st3">
                    <colgroup>
                        <col style="width:120px" />
                        <col />
                    </colgroup>
                    <tr>
                        <th colspan="2" >#<?=$wtdata['pj_idx']?>. <?=$wtdata['subject']?></th>
                    </tr>
                    <tr>
                        <th>구분</th>
                        <td><?=$wtdata['wtype']?></td>
                    </tr>
                    <tr>
                        <th>누적시간</th>
                        <td><?=$wtdata['wt_time_arr']['hour']?>.<?=$wtdata['wt_time_arr']['min']?>H</td>
                    </tr>
                    <tr>
                        <th>작업일자</th>
                        <td>
                            <input type="text" name="w_date" class="datepicker input-st3" placeholder="" value="<?=$wtdate?>" data-provide='datepicker' onchange="javascript:alert(1)"/>
                        </td>
                    </tr>
                    <tr>
                        <th>작업시간</th>
                        <td>
                            <input type="text" name="w_hour" class="input-st3" style="width:50px" placeholder="" value="<?=$wtd_data['wt_hour']?>" maxlength="2" /> 시간 
                            <input type="text" name="w_min" class="input-st3" style="width:50px" placeholder="" value="<?=$wtd_data['wt_min']?>" maxlength="2" /> 분
                        </td>
                    </tr>
                    <tr>
                        <th>진행률</th>
                        <td>
                            <input type="text" name="w_percent" class="input-st3" style="width:50px" placeholder="" value="<?=$wtdata['percent']?>" maxlength="3" /> %
                        </td>
                    </tr>
                    <tr>
                        <th>작업내용</th>
                        <td>
                            <input type="text" name="w_content" class="input-st3" style="width:100%" placeholder="" value="<?=$wtd_data['memo']?>" maxlength="2" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <div class="modal-footer text-center">
        <button type="button" class="btn btn-siwon btn-sm px-3 mr-2" onclick="ws_worker.saveTime()">저장</button>
        <button type="button" class="btn btn-outline-dark btn-sm " data-dismiss="modal" aria-label="Close" type="button">닫기</button>
    </div>
</div>