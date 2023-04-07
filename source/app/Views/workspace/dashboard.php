<?= $this->extend('layouts/'.$layout_Thema.'/default/layout') ?>
<?= $this->section('content') ?>

<input type="hidden" name="pagetype" value="<?=$page_type?>" /> 

<div class="container dashboard_wrap">
    <div class="animated fadeIn">
        <div class="row mb-4 dashboard_list_top list_top">

            <ul class="pl-0 chk_area ws-dash-state">
                <span>작업진행도표</span>
                <?php foreach( $forms['worker_state_arr'] as $key => $ws_state ){ ?>
                    <?php $checked = (in_array($key,[1,2])) ? "checked" : ""; ?>
                <li>
                    <input autocomplete="off" type="checkbox" id="ws_dash_statechk_<?=$key?>" class="check_box" name="ws_dash_statechk" value="<?=$key?>" <?=$checked?> onclick="ws_dashboard.getList()">
                    <label for="ws_dash_statechk_<?=$key?>" class="check_lb"><?=$ws_state['name']?></label>
                </li>
                <?php } ?>
            </ul>
            <div class="btn_area">
                <a href="javascript:;" onclick="ws_dashboard.getList(1)" class="pr-0 btn_refresh"><i></i>새로고침</a>
            </div>
        </div>
        <div class="proj_list dashboard_list mt-0 proj-lists-tpl">

        </div>
        <div class="row mb-4 calendar_top list_top">
            <ul class="pl-0 chk_area">
                <span>작업 캘린더</span>
                <li>
                    <input autocomplete="off" type="checkbox" id="chk_new" class="check_box" name="ws_dash_ptypechk" value="1" onclick="ws_dashboard.getCalendar()">
                    <label for="chk_new" class="check_lb">프로젝트</label>
                </li>
                <li>
                    <input autocomplete="off" type="checkbox" id="chk_proj" class="check_box" name="ws_dash_ptypechk" value="2" onclick="ws_dashboard.getCalendar()">
                    <label for="chk_proj" class="check_lb">운영업무</label>
                </li>
                <li>
                    <input autocomplete="off" type="checkbox" id="chk_oper" class="check_box" name="ws_dash_ptypechk" value="3" onclick="ws_dashboard.getCalendar()">
                    <label for="chk_oper" class="check_lb">오류수정</label>
                </li>
            </ul>
            <div class="btn_area">
                <a href="javascript:;" onclick="ws_dashboard.getCalendar()" class="pr-0 btn_refresh"><i></i>새로고침</a>
            </div>
        </div>
        <div class="calendar_box">
            <div class="card list_box">
                <div class="card-body">
                    <div id="dash-calendar"></div>
                </div>
            </div>
        </div>
        <div class="row mb-4 history_list_top list_top">
            <ul class="pl-0 chk_area">
                <span>히스토리</span>
            </ul>
            <div class="btn_area">
                <a href="javascript:;" onclick="ws_dashboard.getHistory(1)" class="pr-0 btn_refresh"><i></i>새로고침</a>
            </div>
        </div>
        <div class="history_list_area">
            <div class="history_box">
                <ul class="histoy_list">
                </ul>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>