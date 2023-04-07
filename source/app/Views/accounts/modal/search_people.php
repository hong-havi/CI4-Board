<div class="modal-header">
    <div class="modal-title">
        <form id="search-people-form" name="search-people-form" onsubmit="return false">
            <select class="select_st1" name="sec_key">
                <option value="name" <?=($sec_data['sec_key'] == 'name') ? 'selected' : ""?> >이름</option>
                <option value="tel1" <?=($sec_data['sec_key'] == 'tel1') ? 'selected' : ""?> >내선번호</option>
                <option value="tel2" <?=($sec_data['sec_key'] == 'tel2') ? 'selected' : ""?> >연락처</option>
            </select>
            <div class="input-group search-st1 mb-2">
                <input type="text" class="input_search" name="sec_val" id="find-input" value="<?=$sec_data['sec_val']?>" placeholder="검색어를 입력하세요." onkeyup="enterinput(event,'.spmodal-btn-search')">
                <button type="button" class="btn spmodal-btn-search" onclick="SearchPeople.search()"><i class="sjwi-search"></i></button>
            </div>
        </form>
    </div>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <table class="table-st3">
        <thead>
            <tr>
                <th>이름</th>
                <th>부서</th>
                <th>직급/직책</th>
                <th>내선번호</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $ulists as $udata ){ ?>
            <tr>
                <td class="text-center">
                    <p><a href="javascript:;" class="userinfopop" udata="<?=$udata['memberuid']?>"><?=$udata['name']?></a></p>
                    <p style="color:<?=((isset($state_color[$udata['stateText']])) ? $state_color[$udata['stateText']] : "")?>">(<?=$udata['stateText']?>)</p>
                </td>
                <td class="text-center"><?=$udata['gname']?></td>
                <td class="text-center"><?=$udata['lname']?></td>
                <td class="text-center">
                    <p><?=$udata['tel1']?></p>
                    <?php if($udata['tel2']){ ?><p>(<?=$udata['tel2']?>)</p><?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-dark btn-sm px-3 mr-2" data-dismiss="modal" aria-label="Close">닫기</button>
</div>