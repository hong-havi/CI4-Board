<div class="worker-add-form">
    <div class="waf-btn">
        <button type="button" class="btn btn-siwon" onclick="Sender.open('waf-auser-form',99); return false"  onclick="ws_worker.add('2255','my');">인원찾기</button>
    </div>
    <div class="waf-list">        
        <div class="waf-auser-form sender-form" data="99">
            <ul class="sender-list">
            </ul>
            <input type="hidden" name="sender_list_99" class="input-waf-auser-form-99" value="" />
        </div>
    </div>
    <div class="waf-btn">
        <button type="button" class="btn btn-dark" onclick="ws_worker.add('<?=$pj_idx?>','lists');">추가</button>
    </div>
</div>