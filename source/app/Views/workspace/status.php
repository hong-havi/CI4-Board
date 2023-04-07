<?= $this->extend('layouts/'.$layout_Thema.'/default/layout') ?>
<?= $this->section('content') ?>

<div class="animated fadeIn">
    <form name="status_form" id="status_form">
        <div class="mb-4 statistics_list_top list_top">
            <div class="pr-0 cate_area">
                <div class="row">
                    <div class="sel_box pl-0 mb-0 sel_mn">
                        <select class="sel_group year" name="sec_year">
                            <?php for( $i = 2018 ; $i <= date("Y") ; $i++ ){ ?>
                                <option value="<?=$i?>" <?=($i == $search['value']['sec_year']) ? "selected" : ""?> ><?=$i?>년</option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="sel_box pl-0 mb-0 sel_mn">
                        <select class="sel_group mon" name="sec_month">
                            <?php for( $i = 1 ; $i <= 12 ; $i++ ){ ?>
                                <?php $i = sprintf("%20d",$i) ?>
                            <option value="<?=$i?>" <?=($i == $search['value']['sec_month']) ? "selected" : ""?>><?=$i?>월</option>
                            <?php } ?>
                        </select>
                    </div>
                    <a href="javascript:;" onclick="status_sec.sec_submit()" class="btn btn_search">검색</a>
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
                                        <input class="form-check-input" type="checkbox" id="sec-service-<?=$key?>" value="<?=$sec_service?>" name="sec_service[]" <?=((in_array($sec_service,$search['value']['sec_service'])) ? "checked" : "") ?>  >
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
                                    <input class="form-check-input" type="checkbox" id="sec-servicetype-<?=$key?>" value="<?=$sec_service_type?>" name="sec_servicetype[]" <?=((in_array($sec_service_type,$search['value']['sec_servicetype'])) ? "checked" : "") ?>  >
                                    <label class="form-check-label" for="sec-servicetype-<?=$key?>"><?=$sec_service_type?></label>
                                </div>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <ul id="status-sec-tag-lists">
                    <?php foreach( $search['value']['sec_service'] as $k => $v ){ ?>
                        <li data="sec_service[]|<?=$v?>"><?=$v?><button class="btn_del" onclick="pjsec.delForm('sec_service[]','<?=$v?>')">×</button></li>
                    <?php } ?>
                    <?php foreach( $search['value']['sec_servicetype'] as $k => $v ){ ?>
                        <li data="sec_servicetype[]|<?=$v?>"><?=$v?><button class="btn_del" onclick="pjsec.delForm('sec_servicetype[]','<?=$v?>')">×</button></li>
                    <?php } ?>
                </ul>
            </div>
        </form>
    </div>
    <div class="row btns_area">
        <a href="/site/695/workspace/status" class="btn btn_refresh">초기화</a>
        <a href="javascript:;" onclick="status_sec.sec_submit()" class="btn btn_search">검색</a>
    </div>
    <div class="proj_list statistics_list">
    <div class="card list_box">
        <div class="card-body">
            <div class="top">
                <p class="stat_tit">전체</p>
            </div>
            <ul class="statistics_cont">
                <?php foreach( $status['total'] as $p_type => $cnt_data ){ ?>
                    <?php 
                        switch( $p_type ){
                            case '1' : $p_name='프로젝트'; $cls = "proj"; break;
                            case '2' : $p_name='운영업무'; $cls = "oper"; break;
                            case '3' : $p_name='오류수정'; $cls = "error"; break;
                        }

                        $group_key = 't'.$p_type;

                        
                        $cnt_per = floor(($cnt_data['end_cnt']/$cnt_data['all_cnt'])*100);

                    ?>
                <li class="<?=$cls?>">
                    <p class="cate"><?=$p_name?></p>
                    <div class="dounut_box">
                        <div class="chart_wrap">
                            <div class="chart_box">
                                <div id="<?=$cls?>-chart-<?=$group_key?>" class="chart_comm"></div>
                                <p>완료율<span class="value"><?=$cnt_per?>%</span></p>
                            </div>
                        </div>
                        <p class="txt"><span>전체 : <?=$cnt_data['all_cnt']?>건</span><span>완료 : <?=$cnt_data['end_cnt']?>건</span></p>
                    </div>
                    <div class="date_box">
                        <p class="box">
                            <span>평균소요기간</span>
                            <span class="day_hour"><?=$cnt_data['work_day']?>D</span>
                        </p>
                        <p class="box">
                            <span>평균처리기간</span>
                            <span class="day_hour"><?=round($cnt_data['work_time'],2)?>H</span>
                        </p>
                    </div>
                </li>
                <?php } ?>                        
            </ul>
        </div>
    </div>

        <?php foreach( $status['datas'] as $usosok => $datas ){ ?>
            <div class="card list_box">
                <div class="card-body">
                    <div class="top">
                        <p class="stat_tit"><?=$datas['info']['name']?></p>
                    </div>
                    <ul class="statistics_cont">
                        <?php foreach( $datas['datas'] as $p_type => $cnt_data ){ ?>
                            <?php 
                                switch( $p_type ){
                                    case '1' : $p_name='프로젝트'; $cls = "proj"; break;
                                    case '2' : $p_name='운영업무'; $cls = "oper"; break;
                                    case '3' : $p_name='오류수정'; $cls = "error"; break;
                                }
                                $group_key = $usosok.$p_type;

                            ?>
                        <li class="<?=$cls?>">
                            <p class="cate"><?=$p_name?></p>
                            <div class="dounut_box">
                                <div class="chart_wrap">
                                    <div class="chart_box">
                                        <div id="<?=$cls?>-chart-<?=$group_key?>" class="chart_comm"></div>
                                        <p>완료율<span class="value"><?=$cnt_data['cnt_per']?>%</span></p>
                                    </div>
                                </div>
                                <p class="txt"><span>전체 : <?=$cnt_data['all_cnt']?>건</span><span>완료 : <?=$cnt_data['end_cnt']?>건</span></p>
                            </div>
                            <div class="date_box">
                                <p class="box">
                                    <span>평균소요기간</span>
                                    <span class="day_hour"><?=$cnt_data['work_day']?>D</span>
                                </p>
                                <p class="box">
                                    <span>평균처리기간</span>
                                    <span class="day_hour"><?=round($cnt_data['work_time'],2)?>H</span>
                                </p>
                            </div>
                        </li>
                        <?php } ?>                        
                    </ul>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<script>
    var dataset = {

    };
</script>
<?= $this->endSection() ?>