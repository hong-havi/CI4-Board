<?= $this->extend('layouts/'.$layout_Thema.'/default/layout') ?>
<?= $this->section('content') ?>
    <div id="board-write">
        <form name="ws_write_form" id="ws_write_form" action="<?=$action_url?>" onsubmit="return false">
            <input type="hidden" name="bbs_upload" class="bbs_upload" value="" />   
            <input type="hidden" name="muid" id="muid"  value="<?=MENU_INFO['uid']?>" />   
            <input type="hidden" name="bbs_uid"  value="<?=FormFill('text',$ws_data,'pj_idx')?>" />   
            <input type="hidden" name="depth"  value="0" />   
            <input type="hidden" name="parentmbr"  value="0" />   
            <div class="bw-top">
                
                <div class="form-group row">
                    <label class="title-tab col-form-label">제목 <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <input type="text" id="subject" name="subject" class="form-control input-st2" placeholder="제목을 입력하세요." value="<?=FormFill('text',$ws_data,'subject')?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="title-tab col-form-label">본부 팀 <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <select class="select-st1" name="cate1" onchange="Workspace.getGroup('3','cate1','cate2')" tmpvalue="<?=$ws_data['cate1']?>" >                            
                        </select>
                        <select class="select-st1" name="cate2" tmpvalue="<?=$ws_data['cate2']?>">                            
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="title-tab col-form-label">서비스 <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <?php foreach( $form_data['service_arr'] as $key => $service_data ){ ?>
                            <div class="form-check form-check-inline mr-2 checkbox-st1">
                                <input class="form-check-input" type="checkbox" id="service-check-<?=$key?>" value="<?=$service_data?>" name="service[]" <?=FormFill('checkbox',$ws_data['pcate'],'service',"",$service_data)?> />
                                <label class="form-check-label" for="service-check-<?=$key?>"><?=$service_data?></label>
                            </div>
                        <?php }?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="title-tab col-form-label">서비스유형</label>
                    <div class="col-md-10">
                        <?php foreach( $form_data['sv_type_arr'] as $key => $sv_type ){ ?>
                            <div class="form-check form-check-inline mr-2 checkbox-st1">
                                <input class="form-check-input" type="checkbox" id="ws-type-check-<?=$key?>" value="<?=$sv_type?>" name="sv_type[]" <?=FormFill('checkbox',$ws_data['pcate'],'sv_type',"",$sv_type)?> >
                                <label class="form-check-label" for="ws-type-check-<?=$key?>"><?=$sv_type?></label>
                            </div>
                        <?php }?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="title-tab col-form-label">작업유형 <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <select class="select-st1" name="p_type" onchange="Workspace.getPwtype(1,this.value,'w_type');" tmpvalue="<?=$ws_data['p_type']?>">
                            <?php foreach( $form_data['wlist'] as $wdata ){ ?>
                                <option value="<?=$wdata['idx']?>"><?=$wdata['name']?></option>
                            <?php } ?>
                        </select>
                        <select class="select-st1" name="w_type" tmpvalue="<?=$ws_data['w_type']?>">
                            <option>::유형선택::</option>
                            <?php foreach( $form_data['wlist'][$ws_data['p_type']]['cate'] as $wdata ){ ?>
                                <option value="<?=$wdata['idx']?>"><?=$wdata['name']?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="title-tab col-form-label">서비스위치</label>
                    <div class="col-md-10">
                        <input type="text" id="sv_link-input" name="sv_link" class="form-control input-st2" placeholder="서비스 위치 경로를 입력하세요." value="<?=FormFill('text',$ws_data,'sv_link')?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="title-tab col-form-label">작업상태</label>
                    <div class="col-md-10">
                        <select name="state" class="select-st1">
                            <?php foreach( $form_data['w_state_arr'] as $key => $val ){ ?>
                            <option value="<?=$key?>" <?=FormFill('select',$ws_data,'state',$key)?>><?=$val['name']?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="title-tab col-form-label">작업기한 <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <input type="text" id="start_date" name="start_date" class="input-st3 datepicker" placeholder="" value="<?=FormFill('text',$ws_data,'start_date')?>" readonly> ~
                        <input type="text" id="end_date" name="end_date" class="input-st3 datepicker" placeholder="" value="<?=FormFill('text',$ws_data,'end_date')?>" readonly> 
                        &nbsp;&nbsp;완료 희망일(목표일)<span class="text-danger">*</span> : <input type="text" id="due_date" name="due_date" class="input-st3 datepicker" placeholder="" value="<?=FormFill('text',$ws_data,'due_date')?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="title-tab col-form-label">진행률</label>
                    <div class="col-md-10">
                        <input type="text" name="percent" class="input-st3 col-sm-1" value="<?=FormFill('text',$ws_data,'percent')?>" maxlength="3"/> %
                    </div>
                </div>               
                <div class="form-group row">
                    <label class="title-tab col-form-label">관련링크/작업</label>
                    <div class="col-md-10 workspace-link-area">
                        <div class="workspace-link-btn">
                            <button class="btn btn-dark btn-sm" type="button" onclick="Workspace.addLink('link')">링크추가</button>
                            <button class="btn btn-dark btn-sm" type="button" onclick="Workspace.openFindWP()">작업추가</button>
                        </div>
                        <div class="workspace-link-lists">
                            <ul>
                                <?php
                                    $l_links = FormFill('array',$ws_data['pcate'],'l_link',[]);
                                    $l_pj_idxs = FormFill('array',$ws_data['pcate'],'l_pj_idx',[]);
                                ?>
                                <?php foreach($l_links as $l_link){ ?>
                                    <li>
                                        <input type="text" class="input-st3 col-sm-8" name="sv_links_link[]" value="<?=$l_link?>" /> <a href="javascript:;" onclick="Workspace.delLink(this)">
                                        <i class="sjwi-close_bold"></i></a>
                                    </li>
                                <?php } ?>
                                <?php foreach($l_pj_idxs as $l_pj_idx){ ?>
                                    <li>
                                        <input type="hidden" class="input-st3 col-sm-8" name="sv_links_pj[]" value="<?=$l_pj_idx['pj_idx']?>" />
                                        <span>#<?=$l_pj_idx['pj_idx']?>. <?=$l_pj_idx['subject']?></span> 
                                        <a href="javascript:;" onclick="Workspace.delLink(this)"><i class="sjwi-close_bold"></i></a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>         
                <div class="form-group row">
                    <label class="title-tab col-form-label">옵션</label>
                    <div class="col-md-10 col-form-label">
                        <div class="form-check form-check-inline mr-2 checkbox-st1">
                            <input class="form-check-input" type="checkbox" id="inline-secret" value="1" name="opt_secret" <?=FormFill('checkbox',$ws_data,'hidden',"",'1')?>>
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
                <div class="form-group row ">
                    <label class="title-tab col-form-label" >수신인</label>
                    <div class="col-md-9">
                        <div class="sender-form" data="1">
                            <ul class="sender-list">
                                <?php $sender_arr = []; ?>
                                <?php foreach( $ws_data['sender_lists'][1] as $mtype => $sender_list ){ ?>
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
                    <label class="title-tab col-form-label" >참조인</label>
                    <div class="col-md-9">
                        <div class="sender-form" data="2">
                            <ul class="sender-list">
                                <?php $sender_arr = []; ?>
                                <?php foreach( $ws_data['sender_lists'][2] as $mtype => $sender_list ){ ?>
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
            </div>
            <div class="bw-attach">
            </div>
            <div class="bw-contents">
                <textarea name="content" class="bw-editor"><?=FormFill('text',$ws_data,'content')?></textarea>
            </div>
            <div class="bw-btnarea">
                <a class="btn btn-outline-dark" href="/site/<?=MENU_INFO['uid']?>/workspace">리스트</a>
                <button class="btn btn-dark" onclick="write_submit(1)" type="button">글쓰기</button>
            </div>
        </form>
    </div>
<?= $this->endSection() ?>