<?php namespace App\Models\Workspace;

use CodeIgniter\Model;
use App\Models\Workspace\Workspace_model;

/**
 * Class Account
 *
 * @package App\Models
 */
class Worker_model extends Model
{
    protected $intraDB;

    public $w_state_arr = [
        '1'=>["name"=>'대기',"color"=>"#ff0404"],
        '2'=>["name"=>'진행',"color"=>"#008927"],
        '3'=>["name"=>'보류',"color"=>"#0c19e8"],
        '6'=>["name"=>'완료',"color"=>"#999"],
        '7'=>["name"=>'취소',"color"=>"#999"]
    ];

    public $wk_type_arr = [
        '','기획','디자인','퍼블','개발','검수'
    ];


    public $workspace_lists;

    public function __construct()
    {
        $this->intraDB  = db_connect();        
    }

    public function addUser( int $pj_idx, Array $lists ){
        $workspace = new Workspace_model();
        $user_model = new \App\Models\Accounts\User_model();

        $set_datas = [];
        $log_datas = [];
        $regdate = date("Y-m-d H:i:s");
        foreach( $lists as $uno ){
            $data = [
                'pj_idx'    => $pj_idx,
                'wtype'     => '',
                'wcontent'  => '',  
                'state'     => '1',
                'uno'       => $uno,
                'seq'       => '0',
                'start_date'=> '0000-00-00',
                'due_date'  => '0000-00-00',
                'end_date'  => '0000-00-00',
                'wt_time'   => '0',
                'percent'   => '0',
                'level'     => '1',
                'regdate'   => $regdate
            ];
            $set_datas[] = $data;
            $uinfo = $user_model->getUserInfo( $uno );
            $log_datas[] = ['log_type'=>'6','l_type'=>'user','l_field'=>'add','l_field_text'=>'담당자 추가','lb_field'=>$uinfo['name'],'la_field'=>'2'];
        }

        if( count($set_datas) > 0 ){
            $builder = $this->intraDB->table(DB_T_wp_time);
            $builder->insertBatch($set_datas);
            $workspace->setLog( $pj_idx, $log_datas );
        }

    }

    public function getWorkers( int $pj_idx , String $selector = "wt.*,m.name" , Array $where_arr = []){
        $builder = $this->intraDB->table(DB_T_wp_time.' as wt');
        $builder->join(DB_T_s_mbrdata.' as m','wt.uno = m.memberuid');
        $builder->select($selector);
        $builder->where('wt.pj_idx',$pj_idx);
        $builder->orderBy('start_date ASC, due_date ASC, end_date asc');
        $res = $builder->get();

        $datas = $res->getResultArray();

        return $datas;

    }

    public function getWorkerSec( String $selector = "wt.*,m.name" , Array $where_arr = [], int $page = 0, int $pagesize = 0){

        $builder = $this->intraDB->table(DB_T_wp_time.' as wt');
        $builder->join(DB_T_s_mbrdata.' as m','wt.uno = m.memberuid');
        $builder->join(DB_T_wp_plist.' as wp','wt.pj_idx = wp.pj_idx');
        $builder->select($selector);
        if( count($where_arr) > 0  ){
            $where = implode( " AND ", $where_arr);
            $builder->where( $where );
        }
        $builder->orderBy('wt.start_date ASC, wt.due_date ASC, wt.end_date asc');
        if( $page > 0 && $pagesize > 0){
            $limit_start= ( $page-1 ) * $pagesize;
            $builder->limit($pagesize,$limit_start);
        }
        $res = $builder->get();

        $datas = $res->getResultArray();

        return $datas;
        
    }

    public function getInfo( int $wt_idx , String $selector = "wt.*,wp.subject" ){
        $builder = $this->intraDB->table(DB_T_wp_time.' as wt');
        $builder->join(DB_T_wp_plist.' as wp','wt.pj_idx = wp.pj_idx');
        $builder->select($selector);
        $builder->where('wt.wt_idx',$wt_idx);
        $res = $builder->get();

        $data = $res->getRowArray();

        return $data;        
    }

    public function getWorked( Int $wt_idx, String $w_date ){
        $builder = $this->intraDB->table(DB_T_wp_timed);
        $builder->where('wt_idx',$wt_idx);
        $builder->where('workdate',$w_date);
        $res = $builder->get();

        $data = $res->getRowArray();

        return $data;
    }


    public function setWorkTime( int $pj_idx, int $wt_idx , string $w_date , int $w_time , int $percent, String $w_content ){
        
        $check = $this->getWorked($wt_idx,$w_date);
        $builder = $this->intraDB->table(DB_T_wp_timed);

        $regdate = date("Y-m-d H:i:s");
        if( isset($check['wtd_idx']) ){
            $update_datas = [
                'w_time'    => $w_time,
                'percent'   => $percent,
                'memo'      => $w_content,
                'moddate'   => $regdate
            ];
            $builder->where('wtd_idx',$check['wtd_idx']);
            $builder->update($update_datas);
        }else{
            $insert_datas = [
                'pj_idx'    => $pj_idx,
                'wt_idx'    => $wt_idx,
                'uno'       => USER_INFO['memberuid'],
                'workdate'  => $w_date,
                'w_time'    => $w_time,
                'percent'   => $percent,
                'memo'      => $w_content,
                'regdate'   => $regdate
            ];
            $builder->insert($insert_datas);
        }

        $a_time = $this->sumWtime($wt_idx);

        $update_datas = [
            'percent' => $percent,
            'wt_time' => $a_time
        ];
        $this->updateWorker( $wt_idx, $update_datas );
    }
    
    private $oneUpdata = ['logs'=>[],'updatas'=>[]];
    private $field_nm = ['state'=>'작업 상태','percent'=>'진행률','start_date'=>'시작일','end_date'=>'완료일','due_date'=>'완료 희망일'];

    public function updateWorker( int $wt_idx , Array $updatas ){
        $workspace = new Workspace_model();

        $wt_info = $this->getInfo(  $wt_idx , "wt.*" );

        $check = 0;
        foreach( $updatas as $field => $value ){

            if( $wt_info[$field] != $value ){
                $this->oneUpdata['updatas'][$field] = $value;
                $this->oneUpdata['logs'][] = ['log_type'=>'7','l_type'=>'user','l_field'=>$field,'l_field_text'=>$this->field_nm[$field],'lb_field'=>$wt_info[$field],'la_field'=>$value];
                $check++;
            }
        }

        if( $check > 0 ){
            $builder = $this->intraDB->table(DB_T_wp_time);
            $builder->where('wt_idx',$wt_idx);
            $res = $builder->update($updatas);
            
            $workspace->setLog( $wt_info['pj_idx'], $this->oneUpdata['logs']);
            return $res;
        }else{
            return '1';
        }

    }

    public function sumWtime( int $wt_idx){
        $builder = $this->intraDB->table(DB_T_wp_timed);
        $builder->where('wt_idx',$wt_idx);
        $builder->select(' sum(w_time) as ttime');
        $res = $builder->get();

        $total = $res->getRowArray(); 
        return $total['ttime'];
    }

    public function delWorker( int $wt_idx ){
        $builder = $this->intraDB->table(DB_T_wp_time);
        $builder->where('wt_idx',$wt_idx);
        return $builder->delete();
    }
}