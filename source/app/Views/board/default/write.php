<div id="board-write">
    <form name="bbs_write_form" id="bbs_write_form" action="<?=$action_url?>" onsubmit="return false">
        <input type="hidden" name="bbs_upload" class="bbs_upload" value="<?=$board_data['upload']?>" />   
        <input type="hidden" name="bbsid"  value="<?=$board_info['uid']?>" />    
        <input type="hidden" name="bbs_uid"  value="<?=$board_data['uid']?>" />   
        <input type="hidden" name="depth"  value="0" />   
        <input type="hidden" name="parentmbr"  value="0" />   
        <div class="bw-top">
            <div class="form-group row">
                <label class="col-md-1 col-form-label" for="text-input">제목</label>
                <div class="col-md-11">
                <input type="text" id="text-input" name="subject" class="form-control input-st2" placeholder="제목을 입력하세요." value="<?=$board_data['subject']?>">
                </div>
            </div>
            <?php if( $board_info['category'] || $board_info['category2'] || $board_info['category3'] || $board_info['category4'] ){ ?>
            <div class="form-group row">
                <label class="col-md-1 col-form-label" >카테고리</label>
                <div class="col-md-11">
                    <?php if( $board_info['category2'] ){ ?>
                        <?php $cate = explode(",",$board_info['category2']); ?>
                        <select class="select_st1" name="category2">
                            <?php foreach( $cate as $catev ){ ?>
                            <option value="<?=$catev?>" <?=(($board_data['category']['category2'] == $catev) ? "selected" :"")?> ><?=$catev?></option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                    <?php if( $board_info['category'] ){ ?>
                        <?php $cate = explode(",",$board_info['category']); ?>
                        <select class="select_st1" name="category">
                            <?php foreach( $cate as $catev ){ ?>
                            <option value="<?=$catev?>" <?=(($board_data['category']['category'] == $catev) ? "selected" :"")?> ><?=$catev?></option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                    <?php if( $board_info['category3'] ){ ?>
                        <?php $cate = explode(",",$board_info['category3']); ?>
                        <select class="select_st1" name="category3">
                            <?php foreach( $cate as $catev ){ ?>
                            <option value="<?=$catev?>" <?=(($board_data['category']['category3'] == $catev) ? "selected" :"")?> ><?=$catev?></option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                    <?php if( $board_info['category4'] ){ ?>
                        <?php $cate = explode(",",$board_info['category4']); ?>
                        <select class="select_st1" name="category4">
                            <?php foreach( $cate as $catev ){ ?>
                            <option value="<?=$catev?>" <?=(($board_data['category']['category4'] == $catev) ? "selected" :"")?> ><?=$catev?></option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            <div class="form-group row ">
                <label class="col-md-1 col-form-label" >수신인</label>
                <div class="col-md-10">
                    <div class="sender-form" data="1">
                        <ul class="sender-list">
                            <?php $sender_arr = []; ?>
                            <?php foreach( $board_data['sender_lists'][1] as $mtype => $sender_list ){ ?>
                                <?php foreach( $sender_list as $sender_data ){ ?>
                                    <?php $sender_arr[] = $sender_key = $mtype."_".$sender_data['muid'] ?>
                                    <li data="<?=$sender_key?>|<?=$sender_data['iname']?> <?=$sender_data['lname']?>"><?=$sender_data['iname']?> <?=$sender_data['lname']?> <a href="javascript:;" onclick="Sender.submit_del(this,'<?=$sender_key?>')" class="del">X</a></li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                        <input type="hidden" name="sender_list_1" class="input-sender-form-1" value="<?=implode(",",$sender_arr)?>" />
                    </div>
                </div>
                <div class="col-md-1 sender-btn">
                    <a href="javascript:void(0)" onclick="Sender.open('sender-form',1); return false" ><i class="sjwi sjwi-plus-circle"></i></a>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-1 col-form-label" >참조인</label>
                <div class="col-md-10">
                    <div class="sender-form" data="2">
                        <ul class="sender-list">
                            <?php $sender_arr = []; ?>
                            <?php foreach( $board_data['sender_lists'][2] as $mtype => $sender_list ){ ?>
                                <?php foreach( $sender_list as $sender_data ){ ?>
                                    <?php $sender_arr[] = $sender_key = $mtype."_".$sender_data['muid'] ?>
                                    <li data="<?=$sender_key?>|<?=$sender_data['iname']?> <?=$sender_data['lname']?>"><?=$sender_data['iname']?> <?=$sender_data['lname']?> <a href="javascript:;" onclick="Sender.submit_del(this,'<?=$sender_key?>')" class="del">X</a></li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                        <input type="hidden" name="sender_list_2" class="input-sender-form-2" value="<?=implode(",",$sender_arr)?>"  />
                    </div>
                </div>
                <div class="col-md-1 sender-btn">
                    <a href="javascript:void(0)" onclick="Sender.open('sender-form',2); return false" ><i class="sjwi sjwi-plus-circle"></i></a>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-1 col-form-label">옵션</label>
                <div class="col-md-11 col-form-label">
                    <div class="form-check form-check-inline mr-2 checkbox-st1">
                        <input class="form-check-input" type="checkbox" id="inline-notice" value="1" name="opt_notice" <?=(($board_data['opt']['notice'] == '1') ? "checked" :"" )?>>
                        <label class="form-check-label" for="inline-notice">공지글</label>
                    </div>
                    <div class="form-check form-check-inline mr-2 checkbox-st1">
                        <input class="form-check-input" type="checkbox" id="inline-secret" value="1" name="opt_secret" <?=(($board_data['opt']['hidden'] == '1') ? "checked" :"" )?>>
                        <label class="form-check-label" for="inline-secret">비밀글</label>
                    </div>
                    <?php if( $mode == 'modify' ){ ?>
                        <div class="form-check form-check-inline mr-2 checkbox-st1">
                            <input class="form-check-input" type="checkbox" id="inline-opt-sender1" value="1" name="opt_sender_1">
                            <label class="form-check-label" for="inline-opt-sender1" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="체크시에 수신인에 신규 추가된 인원외 모든 인원에게 알림을 발송합니다.">수신인알림</label>
                        </div>
                        <div class="form-check form-check-inline mr-2 checkbox-st1">
                            <input class="form-check-input" type="checkbox" id="inline-opt-sender2" value="1" name="opt_sender_2">
                            <label class="form-check-label" for="inline-opt-sender2" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="체크시에 참조인에 신규 추가된 인원외 모든 인원에게 알림을 발송합니다.">참조인알림</label>
                        </div>
                    <?php } ?>
                    <div class="form-check form-check-inline mr-2 checkbox-st1">
                        <input class="form-check-input" type="checkbox" id="inline-teams" value="1" name="opt_teams">
                        <label class="form-check-label" for="inline-teams" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="체크시에 글 링크와 제목이 게시판과 연괄된 팀즈 채널로 발송됩니다.">팀즈전송(시원스쿨 > 일반)</label>
                    </div>
                    <div class="form-check form-check-inline mr-2 checkbox-st1">
                        <input class="form-check-input" type="checkbox" id="inline-mail" value="1" name="opt_mail">
                        <label class="form-check-label" for="inline-mail">메일전송</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="bw-attach">
        </div>
        <div class="bw-contents">
            <textarea name="content" class="bw-editor"><?=$board_data['content']?></textarea>
        </div>
        <div class="bw-btnarea">
            <!--button class="btn btn-outline-dark" onclick="write_submit(2)" type="button">임시저장</button-->
            <a class="btn btn-outline-dark" href="/site/<?=MENU_INFO['uid']?>/bbs">리스트</a>
            <button class="btn btn-dark" onclick="write_submit(1)" type="button">글쓰기</button>
        </div>
    </form>
</div>