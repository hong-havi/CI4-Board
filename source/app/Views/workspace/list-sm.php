
<div class="card list_box_sm">
    <table class="table table-hover table-align-middle mb-0 table-st1">
        <colgroup>
            <col />
            <col  />
            <col />
            <col />
            <col class="max-width:100px" />
            <col class="max-width:100px" />
            <col class="max-width:100px" />
        </colgroup>

        <thead>
            <tr>
                <th class="text-center">번호</th>
                <th class="text-center">제목</th>
                <th class="text-center">요청자</th>
                <th class="text-center">요청일시</th>
                <th class="text-center">목표일시</th>
                <th class="text-center">상태</th>
                <th class="text-center">조회</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach( $wpdatas['datas'] as $data ){ ?>
            <tr>
                <td class="text-center ">#<?=$data['pj_idx']?></td>
                <td class=" text-left">
                    <?php if( $data['hidden'] == '1' && ( $data['view_per'] != '1' && MENU_PERMISSION['manager'] != '1')){ ?>
                        비공개 글입니다.
                    <?php }else{ ?>
                        <a href="javascript:;" onclick="Workspace.favorit(<?=$data['pj_idx']?>,this)"><i class="sjwi sjwi-star favorit <?=($data['favorit'] > 0 ) ? "checked": ""?>"></i></a>
                        <div class="subject-data">
                            <div class="subject-category">
                                <span><?=$data['cate1']?></span>
                                <span><?=$data['cate2']?></span>
                            </div>
                            <div class="subject-tit">
                                <a href="/site/<?=MENU_INFO['uid']?>/workspace/view/<?=$data['pj_idx']?>"><?=esc($data['subject'])?></a>  
                                <?=(($data['uploads'] != '') ? "<span class=\"sjwi sjwi-file\"></span>" : "")?>
                                <?=(($data['hidden'] == '1') ? "<span class=\"sjwi sjwi-lock\"></span>" : "")?>
                            </div>
                        </div>
                    <?php } ?>
                </td>
                <td class="text-center text-gray-999"><a href="javascript:;" class="userinfopop" udata="<?=$data['uno']?>"><?=$data['uname']?></a></td>
                <td class="text-center text-gray-999"><?=date("Y.m.d H:i",strtotime($data['reg_date']))?></td>
                <td class="text-center text-gray-999"><?=$data['due_date']?></td>
                <td class="text-center " style="color:<?=$search['form']['state'][$data['state']]['color']?>"><?=$search['form']['state'][$data['state']]['name']?></td>
                <td class="text-center text-gray-999"><?=(($data['end_date'] == "0000-00-00") ? "-" :$data['end_date'])?></td>
            </tr>
        <?php } ?>
        
        </tbody>
    </table>
</div>