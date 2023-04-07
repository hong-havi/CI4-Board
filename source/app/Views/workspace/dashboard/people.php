<?= $this->extend('layouts/'.$layout_Thema.'/default/layout') ?>
<?= $this->section('content') ?>

<div class="container dashboard_wrap">
    <div class="animated fadeIn">
        <div class="dashboard-psearch-area text-center">
            <div class="input-group search-st1 mb-2">
                <input type="text" class="input_search" name="dw-find-input" id="find-input" value="" placeholder="이름을 검색하세요." onkeyup="enterinput(event,'.dw-btn-search')">
                <button type="button" class="btn dw-btn-search" onclick="ws_dashboard.findPeople()"><i class="sjwi-search"></i></button>
            </div>
        </div>
        <div class="calendar_box">
            <div class="card list_box">
                <div class="card-body">
                    <div id="dash-calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>