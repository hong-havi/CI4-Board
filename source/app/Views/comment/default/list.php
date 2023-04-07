
    <div class="comment-list">
        <?php foreach( $comment_lists as $comment_data ){ ?>
            <div class="cmt-l-content" id="comment_<?=$comment_data['uid']?>">
                <div class="cmt-lc-top">
                    <a href="javascript:;" class="userinfopop" udata="<?=$comment_data['mbruid']?>"><span class="cmt-lct-name "><?=$comment_data['name']?></span></a>
                    <span class="cmt-lct-date"><?=date("Y.m.d H:i",strtotime($comment_data['d_regis']))?></span>
                </div>
                <div class="cmt-lc-content">
                    <?=$comment_data['content']?>
                </div>
                <div class="cmt-lc-attach">
                    <ul>
                        <?php foreach( $comment_data['upload_datas'] as $updata ){ ?>
                        <li><a href="/common/attach/download/<?=$updata['uid']?>"><?=$updata['name']?></a> <span class="cmt-att-size"><?=number_to_size($updata['size'],2)?></span> (<?=number_format($updata['down'])?>)</li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="cmt-lc-btn" cuid="<?=$comment_data['uid']?>" depth="1">
                    <a href="javascript:;" class="comment_reply_btn">답글</a>
                    <?php if( $comment_data['mbruid'] == USER_INFO['memberuid'] ){ ?>
                        <a href="javascript:;" class="comment_modify">수정</a>
                        <a href="javascript:;" class="comment_delete">삭제</a>
                    <?php }?>
                </div>
                <div class="cmtc-warea cmtc-wform-<?=$comment_data['uid']?>"></div>
            </div>
            <?php foreach( $comment_data['onelists'] as $onedata ){ ?>

                <div class="cmt-l-content depth2">
                    <div class="cmt-lc-top">
                        <span class="cmt-lct-name"><?=$onedata['name']?></span>
                        <span class="cmt-lct-date"><?=date("Y.m.d H:i",strtotime($onedata['d_regis']))?></span>
                    </div>
                    <div class="cmt-lc-content">
                        <?=$onedata['content']?>
                    </div>
                    <div class="cmt-lc-btn" cuid="<?=$onedata['uid']?>" depth="2">
                        <?php if( $onedata['mbruid'] == USER_INFO['memberuid'] ){ ?>
                            <a href="javascript:;" class="comment_modify">수정</a>
                            <a href="javascript:;" class="comment_delete">삭제</a>
                        <?php }?>
                    </div>
                    <div class="cmtc-warea cmtc-wform-<?=$onedata['uid']?>"></div>
                </div>
            <?php } ?>
        <?php } ?>
