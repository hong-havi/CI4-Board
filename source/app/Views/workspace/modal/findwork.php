<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">작업글 찾기</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="ws-find-area">
            <form name="ws-find-form" id="ws-find-form" onsubmit="return false">
                <div class="row">
                    <div class="form-group col-md-2 pr-0 mb-0">
                        <select class="form-control" name="sec_key">
                            <option value="subject" <?=( ($sec_arr['sec_key'] == 'subject') ? "selected" : "")?> >작업명</option>
                            <option value="content" <?=( ($sec_arr['sec_key'] == 'content') ? "selected" : "")?> >작업코드</option>
                        </select>
                    </div>
                    <div class="col-md-5 mb-0">
                        <div class="form-group mb-0">
                            <div class="input-group input-group-st1">
                                <input type="text" class="form-control" name="sec_val" value="<?=$sec_arr['sec_val']?>">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-search" onclick="Workspace.openFindWP('search',1);"><i class="sjwi-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="ws-find-lists">
            <table class="table-st3">
                <tr>
                    <th>작업코드</th>
                    <th>본부 사업부/팀</th>
                    <th>작업명</th>
                    <th>작성자</th>
                    <th>상태</th>
                    <th>작성일</th>
                    <th>추가</th>
                </tr>
                <?php foreach( $lists as $data ){ ?>
                <tr>
                    <td class="text-center">#<?=$data['pj_idx']?></td>
                    <td class="text-center"><?=$data['cate1']?></td>
                    <td><?=$data['subject']?></td>
                    <td class="text-center"><?=$data['uname']?></td>
                    <td class="text-center" style="color:<?=$sec_arr['state'][$data['state']]['color']?>"><?=$sec_arr['state'][$data['state']]['name']?></td>
                    <td class="text-center"><?=date("Y.m.d",strtotime($data['reg_date']))?></td>
                    <td class="text-center"><button class="btn btn-dark btn-sm" type="button" onclick="Workspace.addLink( 'pj_idx' , {pj_idx:'<?=$data['pj_idx']?>',subject:'<?=$data['subject']?>'})">추가</button></td>
                </tr>
                <?php } ?>
                <?php if( count($lists) == 0 ){ ?>
                <tr>
                    <td colspan="7" class="text-center">검색 결과가 없습니다.</td>
                </tr>
                <?php } ?>
            </table>
        </div>

        <?php if( count($lists) > 0 ){ ?>
            <?php $pager->setSurroundCount(2) ?>
            <?php $pager_links = $pager->links(); ?>
            <div class="row board-list-paging justify-content-center">
                <ul class="pagination">           
                    <li class="page-item">
                        <a class="page-link" href="javascript:void(0);" onclick="Workspace.openFindWP('search','<?=$pager_links['0']['title']?>');">〈</a>
                    </li>

                    <?php foreach ($pager_links as $link){ ?>
                        <li class="page-item <?= $link['active'] ? 'active' : '' ?>"><a class="page-link" href="javascript:void(0);" onclick="Workspace.openFindWP('search',<?=$link['title']?>);"><?=$link['title']?></a></li>
                    <?php } ?>
                
                    <li class="page-item">
                        <a class="page-link" href="javascript:void(0);" onclick="Workspace.openFindWP('search','<?=($pager_links[(count($pager_links)-1)]['title']+1)?>');">〉</a>
                    </li>
                </ul>
            </div>
        <?php } ?>
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-dark btn-sm px-3 mr-2" data-dismiss="modal" aria-label="Close" type="button">닫기</button>
    </div>
</div>