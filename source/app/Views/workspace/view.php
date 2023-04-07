<?= $this->extend('layouts/'.$layout_Thema.'/default/layout') ?>
<?= $this->section('content') ?>
<div id="board-view">
    <input type="hidden" name="pj_idx" value="<?=$wp_data['pj_idx']?>" />
    <input type="hidden" name="bbs_upload" class="bbs_upload" value="<?=$wp_data['uploads']?>" />
    <input type="hidden" name="muid" id="muid" value="<?=MENU_INFO['uid']?>" />
    <div class="bv-header">
        <div class="bv-header-top">
            <div class="bv-header-category">
                <span class="cateitem"><?=$wp_data['cate1']?></span>
                <span class="cateitem"><?=$wp_data['cate2']?></span>
            </div>
            <div class="bv-header-detail">
                <div class="bv-header-l">
                    <a class="profile-img userinfopop" href="javascript:;" udata="<?=$wp_data['uno']?>" >
                    <img src="//intra.sjwcorp.kr/_var/simbol/b3aaf1ad792ef63517c61638452fbb2c.jpg" class="img-avatar" >
                    </a>
                </div>
                <div class="bv-header-c">
                    <div class="bv-header-subject">
                        <a href="javascript:;" onclick="Workspace.favorit(<?=$wp_data['pj_idx']?>,this)"><i class="sjwi sjwi-star favorit <?=($wp_data['favorit'] > 0 ) ? "checked": ""?>"></i></a>
                        <?=esc($wp_data['subject'])?>
                    </div>
                    <div class="bv-header-writer">
                        <a href="javascript:;" class="userinfopop" udata="<?=$user_data['memberuid']?>"><?=$user_data['gpname']?> <?=$user_data['gname']?> <?=$user_data['name']?> <?=$user_data['lname']?></a>
                    </div>       
                </div>
                <div class="bv-header-r">
                    <a href="javascript:;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="bbs_menu"><i class="sjwi-more"></i></a>
                    <ul class="dropdown-menu dropdown-st1" role="menu" aria-labelledby="bbs_menu">
                        <li role="presentation" class="dropdown-item"><a role="menuitem" href="javascript:;" onclick="copy_nowlink('<?=current_url()?>')">링크복사</a></li>
                        <li role="presentation" class="dropdown-item"><a role="menuitem" href="javascript:;" onclick="Sender.openList('workspace','<?=$wp_data['pj_idx']?>')" >수신확인</a></li>
                        <li role="presentation" class="dropdown-item"><a role="menuitem" href="javascript:;" onclick="Sender.toggleModSender('.sender-add-form');">수신추가</a></li>
                        <li role="presentation" class="dropdown-item"><a role="menuitem" href="javascript:;">인쇄</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="bv-header-bottom">
            <div class="bv-header-detail">
                <span class="tit">작성시간</span> <span class="date"><?=date("Y-m-d H:i",strtotime($wp_data['reg_date']))?></span>
                <?php if($wp_data['mod_date']){ ?>
                    <span class="divline">|</span>
                    <span class="tit">수정시간</span> <span class="date"><?=date("Y-m-d H:i",strtotime($wp_data['mod_date']))?></span>
                <?php } ?>
                <span class="divline">|</span>
                <span class="tit">조회수</span> <span class="date"><?=number_format($wp_data['hit'])?></span>
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
                    <button type="button" class="btn btn-dark btn-sm" onclick="Sender.submit_modify('workspace',<?=$wp_data['pj_idx']?>,<?=MENU_INFO['uid']?>,'sender_list')">수정</button>&nbsp;
                    <button type="button" class="btn btn-outline-dark btn-sm"onclick="Sender.toggleModSender('.sender-add-form');">취소</button>
                </div>
            </div>
        </div>
        
        <div class="bv-workinfo">
            <form name="wi-info-form" id="wi-info-form" onsubmit="return false;">
                <input type="hidden" name="pj_idx" value="<?=$wp_data['pj_idx']?>" />
                <div class="bv-wi-btn">
                    <div class="bv-wi-btn-left">
                        <button type="button" class="btn btn-outline-dark btn-sm mr-2" onclick="Workspace.openPjlog('<?=$wp_data['pj_idx']?>');">작업로그</button>
                        <button type="button" class="btn btn-dark btn-sm mr-2" >일정표</button>
                    </div>
                    <div class="bv-wi-btn-right">
                        <button type="button" class="btn btn-siwon btn-sm mr-2" onclick="Workspace.saveInfo('#wi-info-form')">저 장</button>
                    </div>
                    
                </div>
                <div class="bv-wi-info">
                    <table>
                        <colgroup>
                            <col style="width:100px" />
                            <col style="width:250px" />
                            <col style="width:100px" />
                            <col  />
                            <col style="width:100px" />
                            <col  />
                        </colgroup>
                        <tr>
                            <th>사업부/팀</th>
                            <td><?=$wp_data['cate1']?> <?=$wp_data['cate2']?></td>
                            
                            <th>작업유형</th>
                            <td><?=$wp_data['p_type_name']?> - <?=$wp_data['w_type_name']?></td>
                            
                            <th>상태</th>
                            <td>
                                <select class="select-st1 " name="state">
                                    <?php foreach( $form_data['w_state_arr'] as $k => $state ){ ?>
                                        <option value="<?=$k?>" <?=(($k == $wp_data['state']) ? "selected" : "")?> ><?=$state['name']?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>작업기간</th>
                            <td>
                                <input type="text" id="start_date" name="start_date" class="input-st3 datepicker " placeholder="" value="<?=$wp_data['start_date']?>" readonly> ~ 
                                <input type="text" id="end_date" name="end_date" class="input-st3 datepicker " placeholder="" value="<?=$wp_data['end_date']?>" readonly>
                            </td>
                            
                            <th>완료 희망일</th>
                            <td>
                                <input type="text" id="due_date" name="due_date" class="input-st3 datepicker " placeholder="" value="<?=$wp_data['due_date']?>" readonly>
                            </td>
                            
                            <th>진행률</th>
                            <td>
                                <input type="text" id="percent" name="percent" class="input-st3" style="width:50px" placeholder="" value="<?=$wp_data['percent']?>" maxlength="3"> %
                            </td>
                        </tr>
                        <tr>
                            <th>서비스유형</th>
                            <td>
                                <?=implode(" / ",$wp_data['pcate']['sv_type'])?>
                            </td>
                            
                            <th>서비스위치</th>
                            <td colspan="3">
                                <?=$wp_data['sv_link']?>                   
                            </td>                        
                        </tr>
                        <tr>
                            <th>서비스</th>
                            <td colspan="5">
                                <?=implode(" / ",$wp_data['pcate']['service'])?>                                
                            </td>                        
                        </tr>
                        <tr>
                            <th>관련링크</th>
                            <td colspan="5" class="text-left">
                                <ul>
                                    <?php foreach($wp_data['pcate']['l_link'] as $l_link){ ?>
                                    <li><a href="javascript:;" onclick="copy_clipboard('<?=$l_link?>')"><?=$l_link?></a></li>
                                    <?php } ?>
                                    <?php foreach($wp_data['pcate']['l_pj_idx'] as $l_pj_idx){ ?>
                                    <li><a href="/site/<?=$form_data['wlist'][$l_pj_idx['p_type']]['cinfo']?>/workspace/view/<?=$l_pj_idx['pj_idx']?>" target="_blank" >#<?=$l_pj_idx['pj_idx']?>. <?=$l_pj_idx['subject']?></a></li>
                                    <?php } ?>
                                </ul>
                            </td>                        
                        </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>


    <div class="bv-body bv-contents-body">
        <?=$wp_data['content']?>
    </div>
    
    <div class="bv-attach">
    </div>

    <div class="wp-worker-area">
    <?=$this->include('workspace/worker/def_list')?>
    </div>
    
    <div class="btn-area mt-4 text-right">
        <?php if(USER_INFO['memberuid'] == $wp_data['uno'] ){ ?>
            <a class="btn btn-outline-dark mr-2" href="/site/<?=MENU_INFO['uid']?>/workspace/write/<?=$wp_data['pj_idx']?>">수정</a>
            <button class="btn btn-outline-dark" onclick="bbs_delete('<?=MENU_INFO['uid']?>','<?=$wp_data['pj_idx']?>')" >삭제</buton>
        <?php } ?>
    </div>

    <div class="bv-comment">
    </div>
    
</div>
<?= $this->endSection() ?>