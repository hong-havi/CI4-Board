
<?php foreach( $lists as $data ){ ?>
    <div class="card list_box">
        <div class="card-body">
            <div class="top">
                <div class="row">
                    <div class="info">
                        <p class="service_tab">
                            <?php foreach( $data['pcate']['service'] as $service ){ ?>
                                <span class="badge badge-pill badge-primary"><?=$service?></span>
                            <?php } ?>
                        </p>
                        <a href="javascript:;" onclick="Workspace.favorit(<?=$data['pj_idx']?>,this)"><i class="sjwi sjwi-star favorit <?=($data['favorit'] > 0 ) ? "checked": ""?>"></i></a>
                        <p class="proj_tit"> #<?=$data['pj_idx']?>. <?=$data['subject']?></p>
                        <span class="date"><?=$data['reg_date']?></span>
                    </div>
                    <div class="btns">
                        <!-- a href="" class="btn_timetable">작업시간표</!-->
                        <!-- a href="" class="btn_cal">일정표</!-->
                        <a href="/site/<?=$workspace_lists[$data['p_type']]['cinfo']?>/workspace/view/<?=$data['pj_idx']?>" target="_blank" class="btn_cont">본문</a>
                    </div>
                </div>
            </div>
            <div class="dashboard_info">
                <table class="table table-align-middle mb-0 table_dash">
                    <tbody>
                        <tr>
                            <th class="text-center">프로젝트 타입</td>
                            <td><?=$data['p_type_name']?></td>
                            <th class="text-center">작업유형</td>
                            <td><?=$data['w_type_name']?></td>
                            <th class="text-center">서비스 유형</td>
                            <td><?=implode(" / ",$data['pcate']['sv_type'])?></td>
                        </tr>
                        <tr>
                            <th class="text-center">예정일</td>
                            <td><?=VDataCheck($data['due_date'],'date','미정')?></td>
                            <th class="text-center">시작일</td>
                            <td><?=VDataCheck($data['start_date'],'date','미정')?></td>
                            <th class="text-center">완료일</td>
                            <td><?=VDataCheck($data['end_date'],'date','미정')?></td>
                        </tr>
                    </tbody>
                </table>
                <div class="row dashboard_state">
                    <div class="chart_wrap">
                        <div class="chart_box">
                            <div id="state-chart-<?=$data['pj_idx']?>" class="chart_comm" value="<?=$data['percent']?>"></div>
                            <p>전체 진행률<span class="value"><?=$data['percent']?>%</span></p>
                        </div>
                    </div>
                    <div class="proj_box">
                        <table class="table table-align-middle mb-0 table_proj">
                            <colgroup>
                                <col width="14%">
                                <col width="7%">
                                <col width="auto">
                                <col width="13%">
                                <col width="13%">
                                <col width="13%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>담당자</th>
                                    <th class="text-center">상태</th>
                                    <th class="text-center">진행상황</th>
                                    <th class="text-center">시작일</th>
                                    <th class="text-center">예정일</th>
                                    <th class="text-center">완료일</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $data['worker_lists'] as $worker ){ ?>
                                    <tr class="ing_fin" style="color:<?=$worker_state_arr[$worker['state']]['color']?>">
                                        <td class="u_name"><?=$worker['wtype']?> <?=$worker['name']?></td>
                                        <td class="text-center"><?=$worker_state_arr[$worker['state']]['name']?></td>
                                        <td class="state">
                                            <span class="per"><?=$worker['percent']?>%</span>
                                            <div class="progress progress-sm rounded">
                                            <div class="progress-bar bg-info" style="width:<?=$worker['percent']?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                        <td class="text-center"><?=VDataCheck($worker['start_date'],'date','미정')?></td>
                                        <td class="text-center"><?=VDataCheck($worker['due_date'],'date','미정')?></td>
                                        <td class="text-center"><?=VDataCheck($worker['end_date'],'date','미정')?></td>
                                    </tr>
                                <?php } ?>
                                <?php if( count($data['worker_lists']) == 0 ){ ?>
                                    <tr>
                                        <td colspan="6">지정된 담당자가 없습니다.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>


<?php if( count($lists) > 0 ){ ?>
    <?php $pager->setSurroundCount(2) ?>
    <?php $pager_links = $pager->links(); ?>
    <div class="row board-list-paging justify-content-center">
        <ul class="pagination">           
            <li class="page-item">
                <a class="page-link" href="javascript:void(0);" onclick="ws_dashboard.getList(<?=$pager_links['0']['title']?>);">〈</a>
            </li>

            <?php foreach ($pager_links as $link){ ?>
                <li class="page-item <?= $link['active'] ? 'active' : '' ?>"><a class="page-link" href="javascript:void(0);" onclick="ws_dashboard.getList(<?=$link['title']?>);"><?=$link['title']?></a></li>
            <?php } ?>
        
            <li class="page-item">
                <a class="page-link" href="javascript:void(0);" onclick="ws_dashboard.getList(<?=($pager_links[(count($pager_links)-1)]['title']+1)?>);">〉</a>
            </li>
        </ul>
    </div>
<?php } ?>