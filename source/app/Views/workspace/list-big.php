
    <?php foreach( $wpdatas['datas'] as $data ){ ?>
        <div class="card list_box">
            <div class="card-body">
                <div class="top">
                <div class="row">
                    <div class="info">                        
                        <?php if( $data['hidden'] == '1' && ( $data['view_per'] != '1' && MENU_PERMISSION['manager'] != '1')){ ?>
                            비밀글 입니다.
                        <?php }else{ ?>
                            <a href="javascript:;" onclick="Workspace.favorit(<?=$data['pj_idx']?>,this)"><i class="sjwi sjwi-star favorit <?=($data['favorit'] > 0 ) ? "checked": ""?>"></i></a>
                            <div class="txt">
                                <p class="proj_tit"> <a href="/site/<?=MENU_INFO['uid']?>/workspace/view/<?=$data['pj_idx']?>" >#<?=$data['pj_idx']?>. <?=$data['subject']?></a></p>
                                <span class="date"><?=date("Y.m.d H:i",strtotime($data['reg_date']))?></span>
                            </div>
                        <?php } ?>
                    </div>
                    
                    <!--
                    <div class="btns text-right">
                    <a href="javascript:void(0)" class="btn_view"></a>
                    <a href="" class="btn_view">일정보기</a>
                    </div>
                    -->
                </div>
                </div>
                <div class="cont">
                    <div class="proj_info">
                        <div class="row">
                            <table class="table table-align-middle mb-0 table_dash" style="width:100%">
                            <colgroup>
                                <col style="width:80px" />
                                <col style="width:210px" />
                                <col style="width:80px" />
                                <col style="" />
                                <col style="width:120px" />
                                <col style="" />
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>작성자</td>
                                    <td><?=$data['uname']?></td>
                                    <th>참여부서</td>
                                    <td><?=$data['cate1']?> <?=$data['cate2']?></td>
                                    <th>Working Date</td>
                                    <td><?=(($data['start_date'] == '0000-00-00') ? '미정' : $data['start_date'])?> ~ <?=(($data['end_date'] == '0000-00-00') ? '미정' : $data['end_date'])?> (<?=(($data['due_date'] == '0000-00-00') ? '미정' : $data['due_date'])?>)</td>
                                </tr>
                                <tr>
                                    <th>유형</td>
                                    <td>
                                        <?=implode(" / ",$data['pcate']['sv_type'])?>
                                    </td>
                                    <th>서비스</td>
                                    <td colspan="3" class="text-left">
                                        <?=implode(" / ",$data['pcate']['service'])?>
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <?php if( $data['hidden'] != '1' || ( $data['hidden'] == '1' && ($data['view_per'] == '1' || MENU_PERMISSION['manager'] != '1'))){ ?>
                    <div class="proj_box">
                    <table class="table table-align-middle mb-0 table_proj">
                        <colgroup>
                            <col width="9.5%">
                            <col width="5%">
                            <col width="5%">
                            <col width="auto">
                        </colgroup>
                        <tbody>
                            <tr class="all">
                                <td class="u_name">전체 진행률</td>
                                <td class="text-center" style="color:<?=$search['form']['state'][$data['state']]['color']?>"><?=$search['form']['state'][$data['state']]['name']?></td>
                                <td class="text-right"><?=$data['percent']?>%</td>
                                <td>
                                    <div class="progress progress-sm rounded">
                                    <div class="progress-bar bg-info" style="width:<?=$data['percent']?>%" aria-valuenow="73" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                            </tr> 
                            <?php foreach( $data['worker_lists'] as $worker_data ){ ?>
                            <tr class="ing_fin">
                                <td class="u_name"><?=$worker_data['wtype']?> <?=$worker_data['name']?></td>
                                <td class="text-center" style="color:<?=$search['form']['worker_state'][$worker_data['state']]['color']?>"><?=$search['form']['worker_state'][$worker_data['state']]['name']?></td>
                                <td class="text-right"><?=$worker_data['percent']?>%</td>
                                <td>
                                    <div class="progress progress-sm rounded">
                                    <div class="progress-bar bg-info" style="width:<?=$worker_data['percent']?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
