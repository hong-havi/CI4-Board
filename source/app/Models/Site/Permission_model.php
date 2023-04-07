<?php namespace App\Models\Site;

use CodeIgniter\Model;

/**
 * Class common sitemodel
 *
 * @package App\Models
 */
class Permission_model extends Model
{
    protected $intraDB;
  
    public function __construct()
    {
        $this->intraDB  = db_connect();
    }

    public function getMenu( Int $uno, Int $muid ){

        $return_permission = ['read'=>0,'write'=>0,'manager'=>0,'aflag'=>0];

        if( USER_AFLAG > 0 ){
            return $return_permission = ['read'=>1,'write'=>1,'manager'=>1,'aflag'=>USER_AFLAG];
        }

        $permission_build = $this->intraDB->table(DB_T_s_permission);
        $permission_build->where( 'uid', $uno );
        $permission_build->where( 'mid', $muid );
        $res = $permission_build->get();

        $per = $res->getRowArray();

        $return_permission = [
            'read'      => (( isset($per['read']) ) ? $per['read'] : 0) ,
            'write'     => (( isset($per['write']) ) ? $per['write'] : 0) ,
            'manager'   => (( isset($per['manager']) ) ? $per['manager'] : 0) ,
            'aflag'     => 0
        ];

        return $return_permission;
    }
    

    public function getDetail( String $cate, Int $uno, int $bbs_uid  ){
        
        $builder = $this->intraDB->table(DB_T_bbs_hidshow." as p");
        $builder->select('p.*');
        $builder->where('p.cate',$cate);
        $builder->where('p.muid',$uno);
        $builder->where('p.bbs_uid',$bbs_uid);
        $res = $builder->get();

        $data = $res->getRowArray();

        return $data;

    }
    
    public function getList(String $cate, Int $bbs_uid, Int $type = 0){
        $Udatas = $this->getListU( $cate, $bbs_uid, $type );
        $Gdatas = $this->getListG( $cate, $bbs_uid, $type );

        if( $type > 0 ){
            return array_merge($Gdatas,$Udatas);
        }else{
            $retdatas = ['1'=>[],'2'=>[],'full_uid'=>['1'=>['p'=>[],'g'=>[]],'2'=>['p'=>[],'g'=>[]] ]];
            foreach( $Udatas as $data ){
                $retdatas[$data['type']]['p'][] = $data;
                $retdatas['full_uid'][$data['type']]['p'][] = $data['muid'];
            }
            foreach( $Gdatas as $data ){
                $retdatas[$data['type']]['g'][] = $data;
                $retdatas['full_uid'][$data['type']]['g'][] = $data['muid'];
            }


            return $retdatas;
        }

    }

    public function getListU( String $cate, Int $bbs_uid, Int $type = 0 ){
        $datas = [];

        $builder = $this->intraDB->table(DB_T_bbs_hidshow." as p");
        $builder->join(DB_T_s_mbrdata." as m"," p.muid = m.memberuid ","left");
        $builder->join(DB_T_s_mbrgroup." as g"," m.sosok = g.uid ","left");
        $builder->join(DB_T_s_mbrlevel." as l"," m.level = l.uid ","left");
        $builder->select('p.*,m.name as iname,g.name as gname,l.name as lname');
        $builder->where('p.cate',$cate);
        $builder->where('p.bbs_uid',$bbs_uid);
        $builder->where('p.mtype','p');
        if( $type > 0 ){
            $builder->where('p.type',$type);
        }
        $res = $builder->get();

        foreach( $res->getResultArray() as $data ){
            $datas[] = $data;
        }

        return $datas;

    }    

    public function getListG( String $cate, Int $bbs_uid, Int $type = 0 ){
        $datas = [];

        $builder = $this->intraDB->table(DB_T_bbs_hidshow." as p");
        $builder->join(DB_T_s_mbrgroup." as g","p.muid = g.uid ","left");
        $builder->select("p.*,g.name as iname,g.name as gname,'' as lname");
        $builder->where('p.cate',$cate);
        $builder->where('p.bbs_uid',$bbs_uid);
        $builder->where('p.mtype','g');
        if( $type > 0 ){
            $builder->where('p.type',$type);
        }
        $res = $builder->get();

        foreach( $res->getResultArray() as $data ){
            $datas[] = $data;
        }

        return $datas;

    }


    public function exLists( String $lists ){
        $new_list = ['p'=>[],'g'=>[]];
        if( !$lists ){
            return $new_list;
        }
        $lists = explode(",",$lists);
        foreach( $lists as $data ){
            $arr = explode("_",$data);
            $new_list[$arr[0]][] = $arr[1];
        }

        return $new_list;
    }
    
    //DB_T_bbs_hidshow
    public function setDatas( String $cate, Int $type, Int $bsno, String $sender_list = "" ){
        
        $sender_list = $this->exLists($sender_list);
        $now_list = $this->getList( $cate, $bsno );
        $add_list_arr = [];
        foreach( $sender_list as $mtype => $lists ){
            $this->delList($cate, $type,$mtype,$bsno,$lists);
            $add_lists = [];
            foreach( $lists as $data ){
                if( !in_array($data,$now_list['full_uid'][$type][$mtype]) ){
                    $add_lists[] = $data;
                }
            } 
            if( count($add_lists) > 0 ){
                $this->addList( $cate, $type, $mtype , $bsno , $add_lists);
            }

            $add_list_arr[$mtype] = $add_lists;
        }
        return $add_list_arr;
    }

    public function delList( String $cate, Int $type, String $mtype , Int $bsno, Array $lists = [] ){
        $builder = $this->intraDB->table(DB_T_bbs_hidshow);
        $builder->where('cate',$cate);
        $builder->where('type',$type);
        $builder->where('bbs_uid',$bsno);
        $builder->where('mtype',$mtype);
        if( count($lists) > 0 ){
            $builder->whereNotIn('muid',$lists);
        }
        $builder->delete();
    }

    public function addList( String $cate, Int $type, String $mtype , Int $bsno, $add_lists = [] ){
        $datas = [];
        $now = date("Y-m-d H:i:s",time());
        foreach( $add_lists as $data ){
            $tmp_ = [
                'cate'      => $cate,
                'type'      => $type,
                'mtype'     => $mtype,
                'muid'      => $data,
                'bbs_uid'   => $bsno,
                'by_uid'    => USER_INFO['memberuid'],
                'reg_date'  => $now
            ];
            $datas[] = $tmp_;
        }
        $builder = $this->intraDB->table(DB_T_bbs_hidshow);
        $builder->insertBatch($datas);
    }

    public function setLog( String $cate, Int $bbs_uid ){
        $upbuilder = $this->intraDB->table(DB_T_bbs_hidshow);
        $upbuilder->set('con_date',date("Y-m-d H:i:s"));
        $upbuilder->where('cate',$cate);
        $upbuilder->where('muid',USER_INFO['memberuid']);
        $upbuilder->where('bbs_uid',$bbs_uid);
        $upbuilder->where('con_date','0000-00-00 00:00:00');

        
        $upbuilder = $this->intraDB->table(DB_T_bbs_hidshow);
        $upbuilder->set('tmp_date',date("Y-m-d H:i:s"));
        $upbuilder->where('cate',$cate);
        $upbuilder->where('muid',USER_INFO['memberuid']);
        $upbuilder->where('bbs_uid',$bbs_uid);
    }
}