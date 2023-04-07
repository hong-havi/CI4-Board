
<?= $this->extend('layouts/'.$layout_Thema.'/default/layout') ?>
<?= $this->section('content') ?>
<div class="row pt-4">
    <div class="col-lg-6">
        <div class="card card-st1">
            <div class="card-header">
                <span class="font-weight-bold font-lg">공지사항</span>
                <div class="card-actions">
                    <a href="/site/2/bbs"  class="font-sm text-gray-999">
                       전체보기 ＞
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="tasks-ing">
                        <table class="table table-hover table-align-middle mb-0 table-st1">
                        <colgroup>
                            <col />
                            <col style="width:120px;"/>
                            <col style="width:100px;"/>
                        </colgroup>
                        <thead>
                            <tr>
                                <th>제목</th>
                                <th class="text-center">이름</th>
                                <th class="text-center">날짜</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach( $main_datas['notice'] as $data ){ ?>
                            <tr>
                                <td><a href="/site/2/bbs/view/<?=$data['uid']?>"><?=$data['subject']?></a></td>
                                <td class="text-center text-gray-999"><?=$data['name']?></td>
                                <td class="text-center text-gray-999"><?=date("Y.m.d",strtotime($data['d_regis']))?></td>
                            </tr> 
                            <?php } ?>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>            
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-st1">
            <div class="card-header">
                <span class="font-weight-bold font-lg">워크시트</span>
                <div class="card-tit-tab">
                    <ul class="nav tab-st1" role="m-workspace-tab">
                        <li class="nav-item">
                            <a class="nav-link active font-sm" href="#m-workspace-ing" data-toggle="tab" role="tab" >진행, 대기</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-sm" href="#m-workspace-lastst" data-toggle="tab" role="tab" >최근 작업물</a>
                        </li>
                    </ul>
                </div>
                <div class="card-actions">
                    <a href="/site/677/workspace/dashboard"  class="font-sm text-gray-999">
                    전체보기 ＞
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="m-workspace-ing">
                        <table class="table table-hover table-align-middle mb-0 table-st1">
                        <thead>
                            <tr>
                            <th>구분</th>
                            <th>제목</th>
                            <th>진행율</th>
                            <th class="text-sm-center">상태</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach( $main_datas['workspace']['ing'] as $data ){ ?>
                            <tr>
                                <td><?=$data['p_type_nm']?></td>
                                <td><?=$data['subject']?></td>
                                <td>
                                    <div class="progress progress-sm rounded">
                                    <div class="progress-bar bg-info" style="width: <?=$data['percent']?>%" aria-valuenow="73" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                                <td class="text-sm-center" style="color:<?=$data['state_nm']['color']?>">
                                    <?=$data['state_nm']['name']?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="m-workspace-lastst">
                        <table class="table table-hover table-align-middle mb-0 table-st1">
                        <thead>
                            <tr>
                            <th>구분</th>
                            <th>제목</th>
                            <th>진행율</th>
                            <th class="text-sm-center">상태</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach( $main_datas['workspace']['lastst'] as $data ){ ?>
                            <tr>
                                <td><?=$data['p_type_nm']?></td>
                                <td><?=$data['subject']?></td>
                                <td>
                                    <div class="progress progress-sm rounded">
                                    <div class="progress-bar bg-info" style="width: <?=$data['percent']?>%" aria-valuenow="73" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                                <td class="text-sm-center" style="color:<?=$data['state_nm']['color']?>">
                                    <?=$data['state_nm']['name']?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card card-st1">
            <div class="card-header">
                <span class="font-weight-bold font-lg">최신 게시물</span>
                <div class="card-tit-tab">
                    <ul class="nav tab-st1" role="m-board-tab">
                        <li class="nav-item">
                            <a class="nav-link active font-sm" href="#m-board-lastst" data-toggle="tab" role="tab" >게시물</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-sm" href="#m-board-comment" data-toggle="tab" role="tab" >댓글</a>
                        </li>
                        <!-- li class="nav-item">
                            <a class="nav-link font-sm" href="#m-board-oneline" data-toggle="tab" role="tab" >대댓글</a>
                        </li -->
                    </ul>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="m-board-lastst">
                        <table class="table table-hover table-align-middle mb-0 table-st1">
                        <colgroup>
                            <col />
                            <col style="width:150px;"/>
                            <col style="width:150px;"/>
                        </colgroup>
                        <thead>
                            <tr>
                                <th>제목</th>
                                <th class="text-center">이름</th>
                                <th class="text-center">날짜</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach( $main_datas['board']['board'] as $data ){ ?>
                            <tr>
                                <td><a href="/site/<?=$data['menu_key']?>/bbs/view/<?=$data['uid']?>"><span class="font-weight-bold">[<?=$data['bbs_name']?>]</span> <?=$data['subject']?></a></td>
                                <td class="text-center text-gray-999"><?=$data['name']?></td>
                                <td class="text-center text-gray-999"><?=date("Y.m.d H:i",strtotime($data['d_regis']))?></td>
                            </tr> 
                            <?php }?>
                        </tbody>
                        </table>
                    </div>
                    
                    <div class="tab-pane" id="m-board-comment">
                        <table class="table table-hover table-align-middle mb-0 table-st1">
                        <colgroup>
                            <col />
                            <col style="width:150px;"/>
                            <col style="width:150px;"/>
                        </colgroup>
                        <thead>
                            <tr>
                                <th>제목</th>
                                <th class="text-center">이름</th>
                                <th class="text-center">날짜</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach( $main_datas['board']['comment'] as $data ){ ?>
                            <tr>
                                <td><a href="<?=$data['bbs_link']?>"><?=$data['subject']?></a></td>
                                <td class="text-center text-gray-999"><?=$data['name']?></td>
                                <td class="text-center text-gray-999"><?=date("Y.m.d H:i",strtotime($data['d_regis']))?></td>
                            </tr> 
                            <?php }?>
                        </tbody>
                        </table>
                    </div>
                    
                    <div class="tab-pane" id="m-board-oncline">
                        <table class="table table-hover table-align-middle mb-0 table-st1">
                        <colgroup>
                            <col />
                            <col style="width:150px;"/>
                            <col style="width:150px;"/>
                        </colgroup>
                        <thead>
                            <tr>
                                <th>제목</th>
                                <th class="text-center">이름</th>
                                <th class="text-center">날짜</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach( $main_datas['board']['oneline'] as $data ){ ?>
                            <tr>
                                <td><?=$data['subject']?></td>
                                <td class="text-center text-gray-999"><?=$data['name']?></td>
                                <td class="text-center text-gray-999"><?=date("Y.m.d H:i",strtotime($data['d_regis']))?></td>
                            </tr> 
                            <?php }?>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
    <div class="card card-st1">
        <div class="card-header">
            <span class="font-weight-bold font-lg">갤러리</span>
            <div class="card-actions">
                <a href="/"  class="font-sm text-gray-999">
                    전체보기 ＞
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="owl-carousel owl-theme">
                <?php foreach( $main_datas['gallery'] as $data ){ ?>
                    <div class="item"><img src="<?=$data['img_url']?>"></div>
                <?php } ?>
            </div>
        </div>  
    </div>
</div>
<?= $this->endSection() ?>



