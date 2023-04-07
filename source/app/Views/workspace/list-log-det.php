
        <?php foreach( $log_datas as $log_data ){ ?>
            <li class="<?=$log_type_arr[$log_data['info']['log_type']]['color']?>">
                <div class="history_l">
                    <p class="title">#<?=$log_data['info']['pj_idx']?>. <?=$log_data['info']['subject']?></p>
                    <span class="date"><?=$log_data['info']['regdate']?></span>
                    <?php foreach( $log_data['detdatas'] as $glog_data ){ ?>
                        <span class="working_date"><?=$glog_data?></span>
                    <?php } ?>
                </div>
                <div class="history_r">
                    <span class="cate"><?=$log_type_arr[$log_data['info']['log_type']]['name']?></span>
                    <i class="u_name">#L<?=$log_data['info']['wlg_idx']?>. <?=$log_data['info']['uname']?></i>
                </div>
            </li>
        <?php } ?>    