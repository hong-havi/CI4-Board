<?= $this->extend('layouts/'.$layout_Thema.'/default/layout') ?>
<?= $this->section('content') ?>

<div class="animated">
    <form name="pj-sec-form" id="pj-sec-form">
    <div class="row mb-4 project_list_top">
        <ul class="pl-0 chk_area">
            <li>
                <input autocomplete="off" type="checkbox" id="chk_bookmark" class="check_box" name="sec_favorit" value="1" <?=(($search['value']['sec_favorit'] == '1') ? "checked" : "")?> />
                <label for="chk_bookmark" class="check_lb">즐겨찾기</label>
            </li>
            <li>
                <input autocomplete="off" type="checkbox" id="chk_my" class="check_box" name="sec_mywork" value="1" <?=(($search['value']['sec_mywork'] == '1') ? "checked" : "")?> />
                <label for="chk_my" class="check_lb">내 작업만 보기</label>
            </li>
        </ul>
        <div class="pr-0 cate_area">
            <div class="row">
                <div class="sel_box pl-0 mb-0">
                    <div class="select">
                        <input type="hidden" name="cate1" value="<?=$search['value']['cate1']?>"/>
                        <input type="hidden" name="cate1_nm" value="<?=$search['value']['cate1_nm']?>"/>
                        <input type="hidden" name="cate2" value="<?=$search['value']['cate2']?>"/>
                        <input type="hidden" name="cate2_nm" value="<?=$search['value']['cate2_nm']?>"/>

                        <input type="checkbox" class="select_view-button">
                        <div class="select_control">
                            <div class="control_label seccate-selector"><?= (($search['value']['cate2_nm']) ? $search['value']['cate2_nm'] : (($search['value']['cate1_nm']) ? $search['value']['cate1_nm'] : "본부선택") )?></div>
                            <div class="control_chevron"><i class="arrow"></i></div>
                        </div>
                        <div class="select_area">
                            <div class="tmenu_wrap" id="wpl-sec-group">
                                <ul class="tmenu"></ul>
                                <ul class="tcon"><li></li></ul>
                            </div>
                            <!-- <div class="btn_wrap">
                                <a href="" class="bt bt_cancel">취소</a>
                                <a href="" class="bt bt_apply">적용</a>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="sel_box pl-0 mb-0">
                    <select class="sel_work" name="sec_wtype">
                        <option value="" selected>작업구분</option>
                        <?php foreach( $search['form']['work_type'] as $sec_work_type ){ ?>
                            <option value="<?=$sec_work_type['idx']?>" <?=(($sec_work_type['idx'] == $search['value']['sec_wtype']) ? "selected" : "")?> ><?=$sec_work_type['name']?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="sel_box pl-0 mb-0">
                    <select class="sel_state" name="sec_state">
                    <option value="" selected>진행상태</option>
                    <?php foreach( $search['form']['state'] as $key=>$sec_state ){ ?>
                        <option value="<?=$key?>" <?=(($key == $search['value']['sec_state']) ? "selected" : "")?> ><?=$sec_state['name']?></option>
                    <?php } ?>
                    </select>
                </div>
                <div class="row search_box">
                    <div class="sel_box pl-0 mb-0">
                        <select class="" name="sec_key">
                            <option value="pj_name" <?=(('pj_name' == $search['value']['sec_key']) ? "selected" : "")?> >프로젝트명</option>
                            <option value="pj_idx" <?=(('pj_idx' == $search['value']['sec_key']) ? "selected" : "")?> >프로젝트코드</option>
                            <option value="wname <?=(('wname' == $search['value']['sec_key']) ? "selected" : "")?> ">작성자</option>
                            <option value="worker" <?=(('worker' == $search['value']['sec_key']) ? "selected" : "")?> >담당자</option>
                        </select>
                    </div>
                    <div class="pl-0 pr-0 mb-0">
                        <div class="form-group mb-0">
                            <div class="input-group search_st">
                                <input type="text" class="input_search" name="sec_val" id="sec_val" value="<?=$search['value']['sec_val']?>" placeholder="검색어를 입력하세요.">
                                <button type="button" class="btn btn-search" onclick="pjsec.sec_submit()"><i class="sjwi-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card cate_sel">
        <div class="card-body">
            <div class="row">
                <div class="cate cate01">
                    <h4 class="card-title text-center">서비스 선택</h4>
                    <ul class="serv_cont">
                        <?php foreach( $search['form']['service_lists'] as $key=>$sec_service ){ ?>
                            <li class="text-center">
                                <div class="checkbox-st2">
                                    <input class="form-check-input" type="checkbox" id="sec-service-<?=$key?>" value="<?=$sec_service?>" name="sec_service[]" <?=((in_array($sec_service,$search['value']['sec_service'])) ? "checked" : "") ?> >
                                    <label class="form-check-label" for="sec-service-<?=$key?>"><?=$sec_service?></label>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="cate cate02">
                    <h4 class="card-title text-center">서비스 유형</h4>
                    <ul class="serv_cont">
                        <?php foreach( $search['form']['service_type'] as $key=>$sec_service_type ){ ?>
                        <li class="text-center">
                                <div class="checkbox-st2">
                                    <input class="form-check-input" type="checkbox" id="sec-servicetype-<?=$key?>" value="<?=$sec_service_type?>" name="sec_servicetype[]" <?=((in_array($sec_service_type,$search['value']['sec_servicetype'])) ? "checked" : "") ?> >
                                    <label class="form-check-label" for="sec-servicetype-<?=$key?>"><?=$sec_service_type?></label>
                                </div>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <!--/.row-->
        </div>
        <div class="card-footer">
            <ul id="pj-sec-tag-lists">
                <?php foreach( $search['value']['sec_service'] as $k => $v ){ ?>
                    <li data="sec_service[]|<?=$v?>"><?=$v?><button class="btn_del" onclick="pjsec.delForm('sec_service[]','<?=$v?>')">×</button></li>
                <?php } ?>
                <?php foreach( $search['value']['sec_servicetype'] as $k => $v ){ ?>
                    <li data="sec_servicetype[]|<?=$v?>"><?=$v?><button class="btn_del" onclick="pjsec.delForm('sec_servicetype[]','<?=$v?>')">×</button></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <!--/.card-->
    <div class="row btns_area">
        <a href="/site/644/workspace" class="btn btn_refresh">초기화</a>
        <a href="javascript:;" class="btn btn_search" onclick="pjsec.sec_submit()">검색</a>
    </div>
    </form>
</div>
<!--/.btns_area-->
<div class="proj_list">
    <div class="list_top">
    <span class="all_count mb-4">총 <?=number_format($wpdatas['total'])?>건</span>
    <a href="<?=current_url()?>/write" class="btn_write mb-4">프로젝트 작성</a>
    </div>
    <?=$this->include('workspace/'.$list_size)?>

    <?php $pager->setSurroundCount(2) ?>
    <div class="row board-list-paging justify-content-center">
        <ul class="pagination">            
            <?php if ($pager->hasPrevious()) { ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getFirst() ?>" tabindex="-1">《</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getPrevious()?>" tabindex="-1">〈</a>
                </li>
            <?php } ?>

            <?php foreach ($pager->links() as $link){ ?>
                <li class="page-item <?= $link['active'] ? 'active' : '' ?>"><a class="page-link" href="<?=$link['uri']?>"><?=$link['title']?></a></li>
            <?php } ?>
            
            <?php if ($pager->hasNext()) { ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getNext()?>">〉</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getLast() ?>">》</a>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>

<?= $this->endSection() ?>