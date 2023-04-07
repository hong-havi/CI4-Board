<div id="board-list">
    <div class="row board-list-top mb-4" >
        <div class="col-md-2 text-left pl-0"><span class="board_count align-middle">총 <?=number_format($bbs['total'])?>건</span></div>
        <div class="col-md-10 pr-0">
            <form name="board_sec_form" id="board_sec_form" type="GET" action="<?=current_url()?>" >
                <div class="row float-right">
                    <div class="col-md-1">
                    </div>
                    <div class="form-group col-md-4 pr-0 mb-0">
                        <select class="form-control" name="sec_key">
                            <option value="subject" <?=($search['sec_key'] =='subject') ? "selected" : ""?> >제목</option>
                            <option value="content" <?=($search['sec_key'] =='content') ? "selected" : ""?> >내용</option>
                            <option value="sub_con" <?=($search['sec_key'] =='sub_con') ? "selected" : ""?> >제목+내용</option>
                            <option value="name" <?=($search['sec_key'] =='name') ? "selected" : ""?> >작성자</option>
                        </select>
                    </div>
                    <div class="col-md-7 mb-0">
                        <div class="form-group mb-0">
                            <div class="input-group input-group-st1">
                                <input type="text" class="form-control" name="sec_val" value="<?=$search['sec_val']?>">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-search" ><i class="sjwi-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>    
        </div>
    </div>

    <div class="row board-list-content mb-4">
        <table class="table table-hover table-align-middle mb-0 table-st1">
            <colgroup>
                <col />
                <col  />
                <col />
                <col class="max-width:100px" />
                <col class="max-width:100px" />
                <col class="max-width:100px" />
            </colgroup>

            <thead>
                <tr>
                    <th class="text-center">번호</th>
                    <th>제목</th>
                    <th class="text-center">글쓴이</th>
                    <th class="text-center">내선번호</th>
                    <th class="text-center">조회</th>
                    <th class="text-center">날짜</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $bbs['notice_datas']  as $notice_data ){ ?>
                <tr>
                    <td class="text-center text-siwon">공지</td>
                    <td class="font-weight-medium">
                            <?php if( $notice_data['hidden'] == '1' && ( $notice_data['view_per'] != '1' && MENU_PERMISSION['manager'] != '1')){ ?>
                            비공개 글입니다.
                        <?php }else{ ?>
                            <a href="javascript:;" onclick="Favorit.action('bbs',<?=$notice_data['uid']?>,this)"><i class="pr-1 sjwi sjwi-star favorit <?=($notice_data['favorit'] > 0 ) ? "checked": ""?>"></i></a>
                            <a href="<?=current_url()?>/view/<?=$notice_data['uid']?>"><?=esc($notice_data['subject'])?></a>  
                            <?=(($notice_data['upload'] != '') ? "<span class=\"sjwi sjwi-file\"></span>" : "")?>
                        <?php } ?>
                        <?=(($notice_data['hidden'] == '1') ? "<span class=\"sjwi sjwi-lock\"></span>" : "")?>
                    </td>
                    <td class="text-center text-gray-999"><a href="javascript:;" class="userinfopop" udata="<?=$notice_data['mbruid']?>"><?=$notice_data['name']?></a></td>
                    <td class="text-center text-gray-999"><?=$notice_data['intel']?></td>
                    <td class="text-center text-gray-999"><?=number_format($notice_data['hit'])?></td>
                    <td class="text-center text-gray-999"><?=date("Y.m.d H:i",strtotime($notice_data['d_regis']))?></td>
                </tr>
                <?php } ?>

                <?php if( count($bbs['datas']) ){ ?>
                    <?php foreach( $bbs['datas']  as $bbs_data ){ ?>
                    <tr>
                        <td class="text-center"><?= $bbs['article_num']--; ?></td>
                        <td>
                            <?php if( $bbs_data['hidden'] == '1' && ( $bbs_data['view_per'] != '1' && MENU_PERMISSION['manager'] != '1')){ ?>
                                비공개 글입니다.
                            <?php }else{ ?>
                                <a href="javascript:;" onclick="Favorit.action('bbs',<?=$bbs_data['uid']?>,this)"><i class="pr-1 sjwi sjwi-star favorit <?=($bbs_data['favorit'] > 0 ) ? "checked": ""?>"></i></a>
                                <a href="<?=current_url()?>/view/<?=$bbs_data['uid']?>"><?=esc($bbs_data['subject'])?></a>  
                                <?=(($bbs_data['upload'] != '') ? "<span class=\"sjwi sjwi-file\"></span>" : "")?>
                            <?php } ?>
                            <?=(($bbs_data['hidden'] == '1') ? "<span class=\"sjwi sjwi-lock\"></span>" : "")?>

                        </td>
                        <td class="text-center text-gray-999"><a href="javascript:;" class="userinfopop" udata="<?=$bbs_data['mbruid']?>"><?=$bbs_data['name']?></a></td>
                        <td class="text-center text-gray-999"><?=$bbs_data['intel']?></td>
                        <td class="text-center text-gray-999"><?=number_format($bbs_data['hit'])?></td>
                        <td class="text-center text-gray-999"><?=date("Y.m.d H:i",strtotime($bbs_data['d_regis']))?></td>
                    </tr>
                    <?php } ?>
                <?php }else{ ?>
                    <tr>
                        <td class="text-center font-weight-medium" colspan="6">게시글이 존재 하지 않습니다.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="row board-list-btnarea mb-4">
        <div class="col-sm-6 text-left pl-0">
            <a class="btn btn-outline-dark px-3 mr-2" href="<?= current_url() ?>">처음목록</a>    
            <a class="btn btn-outline-dark px-3" href="<?= current_url() ?>?sec_key=name&sec_val=<?=USER_INFO['name']?>">내가쓴글</a>
        </div>
        <div class="col-sm-6 text-right pr-0">  
            <a class="btn btn-dark px-4" href="<?=current_url()?>/write">글쓰기</a>
        </div>
    </div>
    
    
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