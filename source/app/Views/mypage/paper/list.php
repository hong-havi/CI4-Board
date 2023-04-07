
<?= $this->extend('layouts/coreui/blank/layout') ?>
<?= $this->section('content') ?>
<div id="paper-wrap">
    <div class="paper-tab">
        <ul>
            <li><a href="/mypage/paper/write">메세지 작성</a></li><li class="active"><a href="/mypage/paper/list">수신 알림</a></li><li><a href="#">송신 알림</a></li><li><a href="#">보관함</a></li>
        </ul>
    </div>
    <div class="paper-content">
        <div class="paper-content-tab">
            <ul>
                <li><a href="/mypage/paper/list">전체 <span class="cnt">4</span></a></li>
                <li><a href="/mypage/paper/list?ptype=bbs">게시판알림 <span class="cnt">4</span></a></li>
                <li class="active"><a href="/mypage/paper/list?ptype=ws">워크시트 <span class="cnt">4</span></a></li>
                <li><a href="/mypage/paper/list?ptype=edms">전자결재 <span class="cnt">4</span></a></li>
                <li><a href="/mypage/paper/list?ptype=system">시스템알림 <span class="cnt">4</span></a></li>
                <li><a href="/mypage/paper/list?ptype=recruit">채용 <span class="cnt">4</span></a></li>
            </ul>
        </div>
        <div class="paper-content-search">
            <form id="paper-list-search" action="/mypage/paper/list" method="GET">
                <input type="hidden" name="ptype" value="" />
                <select class="select_st1" name="sec_type">
                    <option value="">알림종류</option>
                </select>
                <select class="select_st1" name="sec_key">
                    <option value="by_mbruid">보낸이</option>
                    <option value="content">내용</option>
                </select>
                <div class="input-group search-st1">
                    <input type="text" class="input_search" name="sec_val" id="find-input" value="" placeholder="검색어를 입력하세요." onkeyup="enterinput(event,'.paper-search-btn')">
                    <button type="button" class="btn paper-search-btn" onclick=""><i class="sjwi-search"></i></button>
                </div>
            </form>
        </div>
        <div class="paper-content-list">
            <table class="table-st3">
                <colgroup>
                    <col style="width:40px" />
                    <col  />
                    <col style="width:80px" />
                    <col style="width:120px" />
                </colgroup>
                <?php foreach( $lists as $data ){ ?>
                <tr class="<?=(($data['d_read_flag'] == '1') ? "read" :"noread")?>">
                    <td>
                        <div class="checkbox-st3">
                            <input type="checkbox" class="check_box" id="paper-check-<?=$data['uid']?>" value="<?=$data['uid']?>" /><label class="check_lb" for="paper-check-<?=$data['uid']?>"></label>
                        </div>
                    </td>
                    <td class="text-left"><a href="#"><?=mb_substr(strip_tags($data['content']),0,30,"utf-8")?>...</a></td>
                    <td><a href="javascript:;"><?=$data['name']?></a></td>
                    <td><?=date("Y.m.d H:i",strtotime($data['d_regis']))?></td>
                </tr>
                <?php } ?>
                <?php if( count($lists) == 0){ ?>
                    <tr>
                        <td colspan="4">검색되는 알림이 없습니다.</td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div class="paper-content-btn">
            <button type="button" class="btn btn-outline-dark btn-sm px-3 mr-2">읽음</button>
            <button type="button" class="btn btn-outline-dark btn-sm px-3 mr-2">보관</button>
            <button type="button" class="btn btn-dark btn-sm px-3 mr-2">삭제</button>
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
</div>
<?= $this->endSection() ?>



