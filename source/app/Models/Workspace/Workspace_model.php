<?php namespace App\Models\Workspace;

use CodeIgniter\Model;
use App\Models\Workspace\Worker_model;

/**
 * Class Account
 *
 * @package App\Models
 */
class Workspace_model extends Model
{
    protected $intraDB;

	public $service_arr = [
		'기초영어','어린이영어','맥스','리얼트레이닝','B2B','시험영어','일본어','중국어','베트남어','인도네시아','태국어','이탈리아어','스페인어',
		'프랑스어','러시아어','독일어','아랍어','한국어','한국어능력시험','히브리어',
		'통합회원','강의실앱','보카앱','암기펜앱','기타어플','인사','경영기획','대외협력','재무','SCM총무','서비스운영','텔레세일즈','CRM','관리자','물류(SCM)','인트라넷','짠내영어','암기고래','서버','기타'];
	public $sv_type_arr = [
		'PC-WEB','M-WEB','APP','TAB'
	];
    public $w_state_arr = [
        '1'=>["name"=>'대기',"color"=>"#ff0404"],
        //'2'=>array("name"=>'대기',"color"=>"#ff0404"),
        '3'=>["name"=>'진행',"color"=>"#008927"],
        '4'=>["name"=>'보류',"color"=>"#0c19e8"],
        //'5'=>array("name"=>'검수',"color"=>"#008927"),
        //'6'=>array("name"=>'적용',"color"=>"#000000"),
        '7'=>["name"=>'완료',"color"=>"#000000"],
        '8'=>["name"=>'취소',"color"=>"#000000"],
    ];

    public $w_ptype_nm_arr = ['1'=>'프로젝트','2'=>'운영업무','3'=>'오류보고'];


    public $workspace_lists;
    public $Worker_model;

    public function __construct()
    {
        $this->intraDB  = db_connect();
        $this->Worker_model = new Worker_model();
        
    }
    
    public function getDatas( Int $page = 1 , Int $pagesize = 10, String $selector = "*", Array $where_arr = [] , Array $opt = []){



        $limit_start= ( $page-1 ) * $pagesize;

        $datas_build = $this->intraDB->table(DB_T_wp_plist." as wp");
        $datas_build->join(DB_T_s_mbrdata." as m", 'wp.uno = m.memberuid','left');
        $datas_build->select($selector);

        if( count($where_arr) > 0  ){
            $where = implode( " AND ", $where_arr);
            $datas_build->where( $where );
        }
        $datas_build->orderBy('wp.pj_idx','DESC');
        if( $pagesize > 0 && $pagesize > 0 ){
            $datas_build->limit($pagesize,$limit_start);
        }
        
        $res = $datas_build->get();

        $datas = $res->getResultArray();

		foreach( $datas as $key => $data ){
			
			$data = $this->getPjDetail($data, $opt);

			$datas[$key]  = $data;
        }
        
        return $datas;
    }
    
    public function getDataCount( Array $where_arr = array(1) ){
        $datas_build = $this->intraDB->table(DB_T_wp_plist." as wp");
        $datas_build->join(DB_T_s_mbrdata." as m", 'wp.uno = m.memberuid','left');

        $datas_build->select("count(wp.pj_idx) as total");
        if( count($where_arr) > 0  ){
            $where = implode( " AND ", $where_arr);
            $datas_build->where( $where );
        }
        $res = $datas_build->get();

        $datas = $res->getRow();

        return $datas->total;
    }


    public function getPJData( int $pj_idx , string $selector){
        $datas_build = $this->intraDB->table(DB_T_wp_plist." as wp");
        $datas_build->select($selector);

        //$datas_build->join(DB_T_s_mbrdata." as m", 'wp.mbruid = m.memberuid','left');
        $datas_build->where('wp.pj_idx',$pj_idx);
        $res = $datas_build->get();

        $datas = $res->getRowArray();

        return $datas;

    }

    public $DetailOpt = ['worker'=>true,'pcate'=>true];
    public function getPjDetail( Array $data , Array $opt = []){

        $DetailOpt = array_merge($this->DetailOpt,$opt);

        if( isset($data['pj_idx']) ){
            if( $DetailOpt['pcate'] == true ){
                $data['pcate'] = $this->getPcate($data['pj_idx']);
            }
            if( $DetailOpt['worker'] == true ){
                $data['worker_lists'] = $this->Worker_model->getWorkers( $data['pj_idx'] , "wt.*,m.name" );
            }
        }

        if( isset($data['p_type']) ){
            $data['p_type_name'] = (isset($this->workspace_lists[$data['p_type']])) ? $this->workspace_lists[$data['p_type']]['name'] : "";
        }
        
        if( isset($data['p_type']) && isset($data['w_type']) ){
            $data['w_type_name'] = (isset($this->workspace_lists[$data['p_type']]['cate'][$data['w_type']])) ? $this->workspace_lists[$data['p_type']]['cate'][$data['w_type']]['name'] : "";
        }


        return $data;
    }

    public function getInfolists( int $depth , int $pno){
        $builder = $this->intraDB->table(DB_T_wp_cate);
        $builder->where("useYN",'Y');
        $builder->where('depth',$depth);
        if( $pno > 0 ){
            $builder->where('pno',$pno);
        }
        $builder->orderBy('gid','asc');
        $res = $builder->get();
        
        $tmp_ = $res->getResultArray();
        $datas = [];
        if( $depth == '0' ){
            foreach( $tmp_ as $data ){
                $data['cate'] = $this->getInfolists(($data['depth']+1),$data['idx']);
                $datas[$data['idx']] = $data;
            }
        }else{
            foreach( $tmp_ as $data ){
                $datas[$data['idx']] = $data;
            }
        }

        return $datas;
    }

    public function setInsert( Array $data ){
        $builder = $this->intraDB->table(DB_T_wp_plist);
        $builder->set($data);
        $res = $builder->insert();

        if( $res ){
            $pj_idx = $this->intraDB->insertID();
            return $pj_idx;
        }else{
            return 0;
        }
    }

    public function setUpdate( Int $pj_idx, Array $data ){
        $builder = $this->intraDB->table(DB_T_wp_plist);
        $builder->set($data);
        $builder->where('pj_idx',$pj_idx);
        $res = $builder->update();

        if( $res ){
            return $pj_idx;
        }else{
            return 0;
        }
    }

    public function setPcate( Int $pj_idx, String $type, Array $values){
        if( count($values) > 0){
            $del_builder = $this->intraDB->table(DB_T_wp_pcate);
            $del_builder->where('pj_idx',$pj_idx);
            $del_builder->where('type',$type);
            $del_builder->delete();

            $setdatas = [];
            foreach( $values as $val ){
                $data = [
                    'type'      => $type,
                    'pj_idx'    => $pj_idx,
                    'value'     => $val
                ];
                $setdatas[] = $data;
            }
            if( count($setdatas) > 0 ){
                $builder = $this->intraDB->table(DB_T_wp_pcate);
                $builder->insertBatch($setdatas);
            }
        }
    }

    public function getPcate( Int $pj_idx ){


        $builder = $this->intraDB->table(DB_T_wp_pcate);
        $builder->where("pj_idx",$pj_idx);
        $res = $builder->get();

        $datas = ['sv_type'=>[],'service'=>[],'l_link'=>[],'l_pj_idx'=>[]];
        foreach( $res->getResultArray() as $data ){
            if( $data['type'] == 'l_pj_idx' ){
                $info = $this->getPJData( $data['value'] , "pj_idx,subject,p_type");                
                $value = $info;
            }else{
                $value = $data['value'];
            }

            $datas[$data['type']][] = $value;
        }
        return $datas;
    }

    public function setPcolum(){
        
        $builder = $this->intraDB->table('information_schema.columns');
        $builder->where("table_schema",'intradb');
        $builder->where("table_name",'intra_wp_plist');
        $builder->select("column_name,column_comment");
        $res = $builder->get();
        foreach( $res->getResultArray() as $data ){
            $this->field_nm[$data['column_name']] = $data['column_comment'];
        }

    }

    private $oneUpdata = ['logs'=>[],'updatas'=>[]];
    private $field_nm = ['state'=>'작업 상태','percent'=>'진행률','start_date'=>'시작일','end_date'=>'완료일','due_date'=>'완료 희망일'];

    public function setOneUpdate( int $pj_idx, Array $setDatas ){
        $this->oneUpdata = ['logs'=>[],'updatas'=>[]];

        $pj_info = $this->getPJData( $pj_idx , 'wp.*');
        $check = 0;
        foreach( $setDatas as $field => $value ){
            if( $pj_info[$field] != $value ){
                $this->oneUpdata['updatas'][$field] = $value;
                $this->oneUpdata['logs'][] = ['log_type'=>'3','l_type'=>'pj','l_field'=>$field,'l_field_text'=>$this->field_nm[$field],'lb_field'=>$pj_info[$field],'la_field'=>$value];
                $check++;
            }
        }

        if( $check > 0 ){
            $this->oneUpdata['updatas']['mod_date'] = date("Y-m-d H:i:s");
            $this->setUpdate($pj_idx,$this->oneUpdata['updatas']);
            $this->setLog($pj_idx,$this->oneUpdata['logs']);
        }
    }


    public function favoritInfo( Int $pj_idx ){
        $builder = $this->intraDB->table(DB_T_wp_favorit);
        $builder->where('pj_idx',$pj_idx);
        $builder->where('uno',USER_INFO['memberuid']);
        $res = $builder->get();

        return $res->getRowArray();
    }

    public function favoritInsert( Int $pj_idx ){
        $builder = $this->intraDB->table(DB_T_wp_favorit);
        $set = [
            'uno' => USER_INFO['memberuid'],
            'pj_idx' => $pj_idx,
            'wdate'=>date("Y-m-d H:i:s")
        ];
        $builder->set($set);
        return $builder->insert();
    }

    public function favoritDelete( Int $pj_idx ){
        $builder = $this->intraDB->table(DB_T_wp_favorit);
        $builder->where('pj_idx',$pj_idx);
        $builder->where('uno',USER_INFO['memberuid']);
        return $builder->delete();
    }

    /*
    *
    *
    * type [ 
            1:작업등록, 2:작업전체수정, 3:작업일부수정 , 
            4:파일추가, 5:파일삭제
            6:담당자추가, 7:담당자 내용수정, 8:담당자 시간입력,9:담당자 삭제,10: ]
    */
    //$log_datas = ['log_type'=>'타입키','l_type'=>'타입str','l_field'=>'필드명','l_field_text'=>'필드명 표시','lb_field'=>'이전값','la_field'=>'변경값'];
    public $log_type_arr =[
        '1' => ['name'=>'작업 등록','color'=>'bg_green'],
        '2' => ['name'=>'작업 수정','color'=>'bg_green'],
        '3' => ['name'=>'작업 수정','color'=>'bg_green'],
        
        '4' => ['name'=>'파일 추가','color'=>'bg_yellow'],
        '5' => ['name'=>'파일 삭제','color'=>'bg_yellow'],
        
        '6' => ['name'=>'담당자 추가','color'=>'bg_blue'],
        '7' => ['name'=>'담당자 수정','color'=>'bg_blue'],
        '8' => ['name'=>'담당자 시간입력','color'=>'bg_blue'],
        '9' => ['name'=>'담당자 삭제','color'=>'bg_blue'],
    ];
    public function setLog( int $pj_idx, Array $log_datas ){
        $builder = $this->intraDB->table(DB_T_wp_log);
        $builder->select(' max(wlg_idx) as wlg_idx ');
        $builder->where("pj_idx",$pj_idx);
        $res = $builder->get();

        $wl = $res->getRowArray();

        $wlg_idx = 0;
        if( isset($wl['wlg_idx']) ){
            $wlg_idx = $wl['wlg_idx'] + 1;
        }

        $set_datas = [];
        $regdate = date("Y-m-d H:i:s");
        foreach( $log_datas  as $log_data ){
            $data = [
                'pj_idx'        => $pj_idx,
                'wlg_idx'       => $wlg_idx,
                'uno'           => USER_INFO['memberuid'],
                'uname'         => USER_INFO['name'],    

                'log_type'      => $log_data['log_type'],
                'l_type'        => $log_data['l_type'], //타입[pj:본문,file:파일,worker:담당자]
                'l_field'       => $log_data['l_field'], //필드명
                'l_field_text'  => $log_data['l_field_text'], //필드명 표시명

                'lb_field'      => $log_data['lb_field'], //이전값
                'la_field'      => $log_data['la_field'], //변경값
                'regdate'       => $regdate
            ];

            $set_datas[] = $data;
        }

        $insert_builder = $this->intraDB->table(DB_T_wp_log);
        $insert_builder->insertBatch($set_datas);
    }

    public function getLog( int $pj_idx , String $selector = "log.*"){
        $builder = $this->intraDB->table(DB_T_wp_log." as log");
        $builder->join(DB_T_wp_plist." as p","log.pj_idx = p.pj_idx");
        $builder->select($selector);
        $builder->where('log.pj_idx',$pj_idx);
        $builder->orderBy('log.wlg_idx','desc');
        $res = $builder->get();
        $datas = [];
        //$res->getC
        foreach( $res->getResultArray() as $data ){
            $chg_log = $this->setLogForm($data);
            

            if( $chg_log ){
                $datas[$data['wlg_idx']]['info'] = $data;
                $datas[$data['wlg_idx']]['detdatas'][] = $chg_log;
            }
        }
        return $datas;
    }

    public function getLogAll( Int $page, Int $pagesize, String $selector = "log.*", Array $where_arr ){
        $builder = $this->intraDB->table(DB_T_wp_log." as log");
        $builder->join(DB_T_wp_plist." as wp","log.pj_idx = wp.pj_idx");
        $builder->select($selector);
        
        if( count($where_arr) > 0  ){
            $where = implode( " AND ", $where_arr);
            $builder->where( $where );
        }        
        $builder->orderBy('log.regdate','desc');
        $limit_start= ( $page-1 ) * $pagesize;
        $builder->limit($pagesize ,$limit_start); 
        $res = $builder->get();
        $datas = [];
        foreach( $res->getResultArray() as $data ){
            $chg_log = $this->setLogForm($data);
            

            if( $chg_log ){
                $datas[$data['wlg_idx']]['info'] = $data;
                $datas[$data['wlg_idx']]['detdatas'][] = $chg_log;
            }
        }
        $count = count($datas);
        
        return ['datas'=>$datas,'count'=>$count];
    }

    public function setLogForm( Array $data ){
        $chg_log = "";
        switch( $data['log_type'] ){
            case '1' : //작업등록
                $chg_log = "신규 작업 추가";
                break;
            case '2' : //작업전체수정 
            case '3' : //작업일부수정
                switch( $data['l_field'] ){
                    case 'w_type' : //작업타입
                        $chg_log = $data['l_field_text']." 변경 :  => ";
                        break;
                    case 'state' : //작업상태
                        $chg_log = $data['l_field_text']." 변경 : ";
                        if(isset($this->w_state_arr[$data['lb_field']])){
                            $chg_log .= "<span style=\"color:".$this->w_state_arr[$data['lb_field']]['color']."\">".$this->w_state_arr[$data['lb_field']]['name']."</span>";
                        }
                        $chg_log .= "=>";
                        if(isset($this->w_state_arr[$data['la_field']])){
                            $chg_log .= "<span style=\"color:".$this->w_state_arr[$data['la_field']]['color']."\">".$this->w_state_arr[$data['la_field']]['name']."</span>";
                        }
                        break;
                    case 'content' : //본문
                        //$chg_log = "본문 변경 : 이전본문 <span class=\"btn00\"><a href=\"javascript:void(0);\" onclick=\"workspace.actLogConView('".$log_data['idx']."')\">본문보기</a></span> <div class=\"log-det-content\" code=".$log_data['idx'].">".$loglong['log_content']."</div>";
                        break;
                    default :
                        $chg_log = $data['l_field_text']." 변경 : ".$data['lb_field']." => ".$data['la_field'];
                        break;
                }
                break;
                
            case '4' : //파일추가
                $chg_log = "파일추가 : ".$data['la_field'];
                break;
            case '5' : //파일삭제          
                $chg_log = "파일삭제 : ".$data['la_field'];                          
                break;

                
            case '6' : //담당자추가                    
                $chg_log = "담당자추가 : ".$data['lb_field'];                    
                break;
            case '7' : //담당자 내용수정
                
                switch( $data['l_field'] ){
                    case 'state' : 
                        $chg_log = $data['l_field_text']." 변경 : ";
                        $chg_log .= "<span style=\"color:".$this->Worker_model->w_state_arr[$data['lb_field']]['color']."\">".$this->Worker_model->w_state_arr[$data['lb_field']]['name']."</span>";
                        $chg_log .= " => ";
                        $chg_log .= "<span style=\"color:".$this->Worker_model->w_state_arr[$data['la_field']]['color']."\">".$this->Worker_model->w_state_arr[$data['la_field']]['name']."</span>";
                        break;
                    default :
                        $chg_log = $data['l_field_text']." 변경 : ".$data['lb_field']." => ".$data['la_field'];
                        break;
                }
                
                break;
            case '8' : //담당자 작업시간입력
                $chg_log ="";
                /*
                $chg_log = $log_data['l_field_text']." : ".$log_data['lb_field']." => ".$log_data['la_field'];
                */
                break;
            case '9' : //담당자 삭제
                $chg_log ="";
                /*
                $chg_log = $log_data['l_field_text']." : ".$log_data['lb_field'];
                */
                break;
            default : $chg_log =""; break;
        }
        return $chg_log;
    }
    
}