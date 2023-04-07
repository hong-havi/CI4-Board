<div id="board-view">
    <input type="hidden" name="bbs_uid" value="<?=$board_data['uid']?>" />
    <input type="hidden" name="bbs_upload" class="bbs_upload" value="<?=$board_data['upload']?>" />
    <input type="hidden" name="bbs_bid"  value="<?=BOARD_INFO['uid']?>" />
    <input type="hidden" name="menu_uid"  value="<?=MENU_INFO['uid']?>" />
    <div class="bv-header">
        <div class="bv-header-top">
            <div class="bv-header-category">
                <span class="cateitem"><?=$board_data['category2']?></span>
                <span class="cateitem"><?=$board_data['category']?></span>
                <span class="cateitem"><?=$board_data['category3']?></span>
                <span class="cateitem"><?=$board_data['category4']?></span>
            </div>
            <div class="bv-header-detail">
                <div class="bv-header-l">
                    <a class="profile-img userinfopop" href="javascript:;" udata="<?=$user_data['memberuid']?>" >
                    <img src="//intra.sjwcorp.kr/_var/simbol/b3aaf1ad792ef63517c61638452fbb2c.jpg" class="img-avatar" >
                    </a>
                </div>
                <div class="bv-header-c">
                    <div class="bv-header-subject">
                        <a href="javascript:;" onclick="Favorit.action('bbs',<?=$board_data['uid']?>,this)"><i class="pr-1 sjwi sjwi-star favorit <?=($board_data['favorit'] > 0 ) ? "checked": ""?>"></i></a>
                        <?=esc($board_data['subject'])?>
                    </div>
                    <div class="bv-header-writer">
                        <a href="javascript:;" class="userinfopop" udata="<?=$user_data['memberuid']?>"><?=$user_data['gpname']?> <?=$user_data['gname']?> <?=$user_data['name']?> <?=$user_data['lname']?></a>
                    </div>       
                </div>
                <div class="bv-header-r">
                    <a href="javascript:;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="bbs_menu"><i class="sjwi-more"></i></a>
                    <ul class="dropdown-menu dropdown-st1" role="menu" aria-labelledby="bbs_menu">
                        <li role="presentation" class="dropdown-item"><a role="menuitem" href="javascript:;" onclick="copy_nowlink('<?=current_url()?>')">링크복사</a></li>
                        <li role="presentation" class="dropdown-item"><a role="menuitem" href="javascript:;" onclick="Sender.openList('bbs','<?=$board_data['uid']?>')" >수신확인</a></li>
                        <li role="presentation" class="dropdown-item"><a role="menuitem" href="javascript:;" onclick="Sender.toggleModSender('.sender-add-form');">수신추가</a></li>
                        <li role="presentation" class="dropdown-item"><a role="menuitem" href="javascript:;">인쇄</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="bv-header-bottom">
            <div class="bv-header-detail">
                <span class="tit">작성시간</span> <span class="date"><?=date("Y-m-d H:i",strtotime($board_data['d_regis']))?></span>
                <?php if($board_data['d_modify']){ ?>
                    <span class="divline">|</span>
                    <span class="tit">수정시간</span> <span class="date"><?=date("Y-m-d H:i",strtotime($board_data['d_modify']))?></span>
                <?php } ?>
                <span class="divline">|</span>
                <span class="tit">조회수</span> <span class="date"><?=number_format($board_data['hit'])?></span>
            </div>
            <div class="bv-header-detail">
                <span class="tit">수신인</span> 
                <?php foreach( $sender_lists['1'] as $mtype => $sender_datas ){ ?>
                    <?php foreach( $sender_datas as $sender_data){ ?>
                        <a href="javascript:;" <?=(($mtype == 'p') ? "class=\"userinfopop\" udata=\"".$sender_data['muid']."\"" :"")?>><?=$sender_data['iname']?></a>
                    <?php } ?>
                <?php } ?>
            </div>
            <div class="bv-header-detail">
            <span class="tit">참조인</span> 
                <?php foreach( $sender_lists['2'] as $mtype => $sender_datas ){ ?>
                    <?php foreach( $sender_datas as $sender_data){ ?>
                        <a href="javascript:;" <?=(($mtype == 'p') ? "class=\"userinfopop\" udata=\"".$sender_data['muid']."\"" :"")?>><?=$sender_data['iname']?></a>
                    <?php } ?>
                <?php } ?>
            </div>
            <div class="sender-add-form">
                <div class="form-group row ">
                    <label class="col-md-1 col-form-label" >수신인</label>
                    <div class="col-md-10">
                        <div class="sender-form" data="1">
                            <ul class="sender-list">
                                <?php $sender_arr = []; ?>
                                <?php foreach( $sender_lists[1] as $mtype => $sender_list ){ ?>
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
                                <?php foreach( $sender_lists[2] as $mtype => $sender_list ){ ?>
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
                <div class="form-group row sender-aform-btn">
                    <button type="button" class="btn btn-dark btn-sm" onclick="Sender.submit_modify('bbs',<?=$board_data['uid']?>,<?=MENU_INFO['uid']?>,'sender_list')">수정</button>&nbsp;
                    <button type="button" class="btn btn-outline-dark btn-sm"onclick="Sender.toggleModSender('.sender-add-form');">취소</button>
                </div>
            </div>
        </div>
    </div>
    <div class="bv-body">
        <?=$board_data['content']?>
    </div>
    
    <div class="bv-attach">
    </div>

    <div class="btn-area mt-4 text-right">
        <?php if(USER_INFO['memberuid'] == $board_data['mbruid'] ){ ?>
            <a class="btn btn-outline-dark mr-2" href="/site/<?=MENU_INFO['uid']?>/bbs/write/<?=$board_data['uid']?>">수정</a>
            <button class="btn btn-outline-dark" onclick="bbs_delete('<?=MENU_INFO['uid']?>','<?=$board_data['uid']?>')" >삭제</buton>
        <?php } ?>
    </div>

    <div class="bv-comment">
        <?php /* $this->include('comment/'.$comment_data['template'].'/full') */ ?>
    </div>
    
</div>