
    <div class="bv-wi-btn-left">
        <button type="button" class="btn btn-dark btn-sm mr-2" onclick="ws_worker.openAdd(this,'<?=$wp_data['pj_idx']?>');">작업자 추가</button>
        <button type="button" class="btn btn-siwon btn-sm mr-2" onclick="ws_worker.add('<?=$wp_data['pj_idx']?>','my');">참여</button>
    </div>
    <div class="bv-wi-list">
        <table>
            <colgroup>
                <col style="width:80px" />
                <col style="width:100px" />
                
                <col style="width:110px" />
                <col style="width:110px" />
                <col style="width:110px" />
                
                <col style="width:80px" />
                <col style="width:80px" />
                <col style="width:80px" />
                <col  />
                <col style="width:90px" />
                <col style="width:25px" />
            </colgroup>
            <thead>
                <tr>
                    <th>업무구분</th>
                    <th>담당자</th>
                    <th>시작일</th>
                    <th>완료 예정일</th>
                    <th>완료일</th>
                    <th>상태</th>
                    <th>작업시간</th>
                    <th>난이도</th>
                    <th colspan="2">진행률</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if( count($worker_datas['ulists']) > 0 ){ ?>
                    <?php foreach( $worker_datas['ulists'] as $worker_data ){ ?>
                        <?php if( $worker_data['uno'] == USER_INFO['memberuid'] ){ ?>
                            <tr class="worker-info-<?=$worker_data['wt_idx']?>">
                                <td>
                                    <select class="select-st1" name="w_type">
                                        <?php foreach( $worker_datas['form']['w_type_arr'] as $worker_wtype ){ ?>
                                        <option value="<?=$worker_wtype?>" <?=(($worker_wtype==$worker_data['wtype']) ? "selected" : "")?> ><?=$worker_wtype?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td><?=$worker_data['name']?></td>
                                <td><input type="text" name="start_date" class="datepicker input-st3 col-sm" value="<?=$worker_data['start_date']?>"/></td>
                                <td><input type="text" name="end_date" class="datepicker input-st3 col-sm" value="<?=$worker_data['end_date']?>" /></td>
                                <td><input type="text" name="due_date" class="datepicker input-st3 col-sm" value="<?=$worker_data['due_date']?>" /></td>
                                <td>
                                    <select class="select-st1" name="w_state">
                                        <?php foreach( $worker_datas['form']['w_state_arr'] as $key=>$worker_state ){ ?><
                                        <option value="<?=$key?>" <?=(($key==$worker_data['state']) ? "selected" : "")?> ><?=$worker_state['name']?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <a href="javascript:;" onclick="ws_worker.openTime('<?=$worker_data['wt_idx']?>');"><?=$worker_data['wt_time']?>H</a>
                                </td>
                                <td>
                                    <select class="select-st1" name="w_level">
                                        <?php for( $i = 1 ; $i <= 5 ; $i++ ){ ?>
                                            <option value="<?=$i?>" <?=( ($i == $worker_data['level']) ? "selected": "")?> ><?=$i?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <div class="progress progress-sm rounded">
                                        <div class="progress-bar bg-info" style="width: <?=$worker_data['percent']?>%" aria-valuenow="73" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="percent" class="input-st3 col-sm-8" value="<?=$worker_data['percent']?>" maxlength="3"/>%
                                </td>
                                <td>
                                    <a href="javascript:;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="wt_menu_<?=$worker_data['wt_idx']?>"><i class="sjwi-more"></i></a>
                                    <ul class="dropdown-menu dropdown-st1" role="menu" aria-labelledby="wt_menu_<?=$worker_data['wt_idx']?>">
                                        <li role="presentation" class="dropdown-item"><a role="menuitem" href="javascript:;" onclick="ws_worker.modify('<?=$worker_data['wt_idx']?>');" >변경저장</a></li>
                                        <li role="presentation" class="dropdown-item"><a role="menuitem" href="javascript:;" onclick="ws_worker.del('<?=$worker_data['wt_idx']?>');" >담당삭제</a></li>
                                        <!-- li role="presentation" class="dropdown-item"><a role="menuitem" href="javascript:;" onclick="" >작업기록</a></li>
                                        <li role="presentation" class="dropdown-item"><a role="menuitem" href="javascript:;" onclick="" >작업정보</a></li -->
                                    </ul>
                                </td>
                            </tr>
                        <?php }else{ ?>
                            <tr>
                                <td><?=$worker_data['wtype']?></td>
                                <td><?=$worker_data['name']?></td>
                                <td><?=$worker_data['start_date']?></td>
                                <td><?=$worker_data['end_date']?></td>
                                <td><?=$worker_data['due_date']?></td>
                                <td><?=$worker_datas['form']['w_state_arr'][$worker_data['state']]['name']?></td>
                                <td><?=$worker_data['wt_time']?>H</td>
                                <td><?=$worker_data['level']?></td>
                                <td>
                                    <div class="progress progress-sm rounded">
                                        <div class="progress-bar bg-info" style="width: <?=$worker_data['percent']?>%" aria-valuenow="73" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                                <td><?=$worker_data['percent']?>%</td>
                                <td></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php }else{ ?>
                    <tr>
                        <td colspan="11">지정된 작업자가 없습니다.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>