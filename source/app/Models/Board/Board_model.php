<?php namespace App\Models\Board;

use CodeIgniter\Model;

/**
 * Class Account
 *
 * @package App\Models
 */
class Board_model extends Model
{
    protected $intraDB;


    public function __construct()
    {
        $this->intraDB  = db_connect();
        
    }

    public function getBoardInfo( String $type, String $bid ){
        $board_build = $this->intraDB->table(DB_T_bbs_list);
        
        switch( $type ){
            case 'id' :
                $board_build->where('id',$bid);
                break; 
            case 'uid' :
                $board_build->where('uid',$bid);
                break; 
            default : 
                $board_build->where('uid',$bid);
                break;
        }
        $res = $board_build->get();
        
        $data = $res->getRowArray();

        return $data;
    }

    public function getDatas( String $bid , Int $page = 1 , Int $pagesize = 10, String $selector = "*", Array $where_arr = [] ){
        $limit_start= ( $page-1 ) * $pagesize;

        $datas_build = $this->intraDB->table(DB_T_bbs_data." as bd");
        $datas_build->select($selector);

        $datas_build->join(DB_T_s_mbrdata." as m", 'bd.mbruid = m.memberuid','left');

        $datas_build->where('bd.bbs',$bid);
        if( count($where_arr) > 0  ){
            $where = implode( " AND ", $where_arr);
            $datas_build->where( $where );
        }
        $datas_build->orderBy('bd.uid','DESC');
        $datas_build->limit($pagesize,$limit_start);
        $res = $datas_build->get();

        $datas = $res->getResultArray();

        return $datas;
    }
    
    public function getDataCount( String $bid , Array $where_arr = array(1) ){
        $datas_build = $this->intraDB->table(DB_T_bbs_data." as bd");
        $datas_build->join(DB_T_s_mbrdata." as m", 'bd.mbruid = m.memberuid','left');

        $datas_build->select("count(bd.uid) as total");
        $datas_build->where('bd.bbs',$bid);
        if( count($where_arr) > 0  ){
            $where = implode( " AND ", $where_arr);
            $datas_build->where( $where );
        }
        $res = $datas_build->get();

        $datas = $res->getRow();

        return $datas->total;
    }

    public function getData( String $bid , Int $uid, String $selector = "*" ){
        $datas_build = $this->intraDB->table(DB_T_bbs_data." as bd");
        $datas_build->select($selector);

        $datas_build->join(DB_T_s_mbrdata." as m", 'bd.mbruid = m.memberuid','left');
        if( $bid ){
            $datas_build->where('bd.bbs',$bid);
        }
        $datas_build->where('bd.uid',$uid);
        $res = $datas_build->get();

        $datas = $res->getRowArray();

        return $datas;
    }

    public function checkPermission(){
        
    }

    public function setInsert( Array $data ){
        $builder = $this->intraDB->table(DB_T_bbs_data);
        $builder->set($data);
        $res = $builder->insert();

        if( $res ){
            $bno = $this->intraDB->insertID();
            $this->setStatus( $data['bbs'], $bno );
            return $bno;
        }else{
            return 0;
        }
    }

    public function setUpdate( Int $bbs_uid, Array $data ){
        $builder = $this->intraDB->table(DB_T_bbs_data);
        $builder->set($data);
        $builder->where('uid',$bbs_uid);
        $res = $builder->update();

        if( $res ){
            return $bbs_uid;
        }else{
            return 0;
        }

    }

    //게시판 통계
    public function setStatus( int $bbsuid, int $bsno ){
        
        $builder = $this->intraDB->table(DB_T_bbs_list);
        $builder->set('num_r','num_r + 1 ',false);
        $builder->set('d_last',date("YmdHis",time()));
        $builder->where('uid',$bbsuid);
        $builder->update();

        $this-> setStatus_date(DB_T_bbs_month,date("Ym"),$bbsuid);
        $this-> setStatus_date(DB_T_bbs_day,date("Ymd"),$bbsuid);

    }

    public function setStatus_date($table,$date,$bbsuid){
        $builder = $this->intraDB->table($table);
        $builder->where('bbs',$bbsuid);
        $builder->where('date',$date);
        $res = $builder->get();
        $status_info = $res->getRowArray();
        if( isset($status_info['date']) ){
            $up_builder = $this->intraDB->table($table);
            $up_builder->set('num','num + 1 ',false);
            $up_builder->where('bbs',$bbsuid);
            $up_builder->where('date',$date);
            $up_builder->update();
        }else{
            $in_builder = $this->intraDB->table($table);
            $datas = ['date'=>$date,'site'=>1,'bbs'=>$bbsuid,'num'=>1];
            $in_builder->set($datas);
            $in_builder->insert();
        }

    }


    public function deleteData( Int $bbs_uid ){
        $backup_res = $this->backup( $bbs_uid );
        if( $backup_res ){
            $builder = $this->intraDB->table(DB_T_bbs_data);
            $builder->where('uid',$bbs_uid);
            $builder->delete();
        }
    }

    public function backup( Int $bbs_uid ){
        $qry = "INSERT INTO ".DB_T_bbs_data_del." (
                `deldate`,`del_muid`,
                `uid`,`site`,`gid`,`bbs`,`bbsid`,`depth`,`parentmbr`,`display`,`hidden`,`notice`,`emergency`,`name`,`nic`,`mbruid`,`id`,`pw`,`category`,`category2`,`category3`,`category4`,
                `subject`,`content`,`html`,`tag`,`hit`,`down`,`comment`,`oneline`,`trackback`,`score1`,`score2`,`singo`,`point1`,`point2`,`point3`,`point4`,`d_regis`,`d_modify`,`d_delete`,
                `d_comment`,`d_trackback`,`d_tmp`,`upload`,`ip`,`agent`,`sns`,`adddata`)
                SELECT NOW(),'".USER_INFO['memberuid']."',bbs.* FROM ".DB_T_bbs_data." bbs WHERE uid = '".$bbs_uid."' ";
        $res = $this->intraDB->query($qry);
        return $res;
    } 

    public function viewLog( Int $bbs_uid ){
        $set = [
            'type'  => 'bbs',
            'buid'  => $bbs_uid,
            'muid'  => USER_INFO['memberuid'],
            'rdate' => date("Y-m-d H:i:s") 
        ];
        $builder = $this->intraDB->table(DB_T_bbs_view);
        $builder->set($set);
        $builder->insert();

        $update_builder = $this->intraDB->table(DB_T_bbs_data);
        $update_builder->set('hit','hit+1',false);
        $update_builder->where('uid',$bbs_uid);
        $update_builder->update();
    }
}