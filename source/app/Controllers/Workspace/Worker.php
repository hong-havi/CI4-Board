<?php namespace App\Controllers\Workspace;

use App\Controllers\BaseController;
use App\Models\Workspace\Workspace_model;
use App\Models\Workspace\Worker_model;
use App\Models\Site\Permission_model;
use App\Models\Common\Paper_model;
use CodeIgniter\API\ResponseTrait;
use App\Models\Accounts\Group_model;

class Worker extends BaseController
{
	
    use ResponseTrait;
    
    private $Worker_model;
    private $Workspace;
	private $Permission_model;
	private $Board_info = [];

	public function __construct()
	{
        $this->Worker_model = new Worker_model();
        $this->Workspace = new Workspace_model();
		$this->Permission_model = new Permission_model();
	}

    public function add(){
        $paper = new Paper_model();

        if( !$this->request->isAJAX() ){
            return $this->fail('잘못된 요청입니다.', 401);
        }

        
        $pj_idx = $this->SiteModel->getParam('pj_idx','','POST');        
        $type = $this->SiteModel->getParam('type','','POST');       
        $lists = $this->SiteModel->getParam('lists','','POST',false,'');      
        
        $udatas = [];
        switch( $type ){
            case 'my' :
                $udatas[] = USER_INFO['memberuid'];
                break;
            case 'lists' :
                $lists_arr = explode(",",$lists);
                $rlists_arr = ['p'=>[],'g'=>[]];
                foreach( $lists_arr as $udata ){
                    $tmp_ = explode("_",$udata);
                    $rlists_arr[$tmp_[0]][] = $tmp_[1];
                }
                $group = new Group_model();
                $udatas = $group->setUnoList($rlists_arr);
                break;
        }

        $this->Worker_model->addUser($pj_idx,$udatas);

        $this->Permission_model->setDatas('ws',1,$pj_idx,$lists); //수신
        $wp_data = $this->Workspace->getPJData( $pj_idx ,"wp.subject,wp.p_type");
        
        $subject = "#".$pj_idx." ".$wp_data['subject'];
        $workspace_lists = $this->Workspace->getInfolists( 0, 0);
        $link = "/site/".$workspace_lists[$wp_data['p_type']]['cinfo']."/workspace/view/".$pj_idx;
        $memo_1 = $paper->makeTpl( '담당자지정', $subject, "", $link);
        $paper->send( USER_INFO['memberuid'], ['p'=>$udatas], $memo_1, 'text' ,$link , $pj_idx, 14 );

        
        $this->SiteModel->setResStatus(1,'');
        return $this->respondCreated($this->SiteModel->response_data,200);
       
    }

    public function wlist(){
        $pj_idx = $this->SiteModel->getParam('pj_idx','','GET');  
        $view_datas['wp_data']['pj_idx'] = $pj_idx;
		$view_datas['worker_datas'] = [
			'ulists' =>$this->Worker_model->getWorkers( $pj_idx ),
			'form' => [
				'w_state_arr' => $this->Worker_model->w_state_arr,
				'w_type_arr' => $this->Worker_model->wk_type_arr,
			]	
		];
		return $this->View( 'workspace/worker/def_list' , $view_datas );  
    }

    public function addform(){
        $pj_idx = $this->SiteModel->getParam('pj_idx','','GET');  

        return $this->View('workspace/modal/worker_addform',['pj_idx'=>$pj_idx]);
    }

    public function timeform(){
        helper('numberv2');

        $wt_idx = $this->SiteModel->getParam('wt_idx','','GET');
        $wtdate = $this->SiteModel->getParam('wtdate','','GET',false,date("Y-m-d"));
        
        
        if( !$wt_idx ){
            return $this->fail('잘못된 요청입니다.', 401);
        }
        

        $wtdata = $this->Worker_model->getInfo($wt_idx,'wt.*,wp.subject');
        $wtdata['wt_time_arr'] = number_to_time($wtdata['wt_time']);
        $wtd_data = $this->Worker_model->getWorked($wt_idx,$wtdate);
        if( !isset($wtd_data['wtd_idx']) ){
            $wtd_data['w_time'] = 0;
            $wtd_data['memo'] = '';
        }
        $wtd_time = number_to_time($wtd_data['w_time']);
        if( !isset($wtdata['wt_idx']) ){
            return $this->fail('잘못된 요청입니다.', 401);
        }
        
        if( $wtdata['uno'] != USER_INFO['memberuid'] ){
            return $this->fail('본인만 수정이 가능합니다.', 401);
        }

        
        $view_datas = [
            'wtdate'=>$wtdate,
            'wtdata'=>$wtdata,
            'wtd_data'=>[
                'wt_hour'   => $wtd_time['hour'],
                'wt_min'    => $wtd_time['min'],
                'memo'      => $wtd_data['memo']
            ]
        ];
        return $this->View('workspace/modal/worker_timeform',$view_datas);
    }

    public function savetime(){
        
        $validation =  \Config\Services::validation();

        if( !$this->request->isAJAX() ){
            return $this->fail('잘못된 요청입니다.', 401);
        }

        $validation->setRules([
            'w_date' => [
                'label' => 'w_date', 'rules' => 'required', 'errors' =>[
                    'required' => '작업일자를 선택해주세요.'
                ]
            ],
            'wt_idx' => [
                'label' => 'wt_idx', 'rules' => 'required', 'errors' =>[
                    'required' => '잘못된 접근입니다.'
                ]
            ]
        ]);

        $validation->withRequest($this->request)
        ->run();
        if( count($validation->getErrors()) > 0 ){
            foreach( $validation->getErrors() as $k => $error_message ){
                return $this->fail($error_message, 400);
            }
        }


        $wt_idx = $this->SiteModel->getParam('wt_idx','','POST');
        $w_date = $this->SiteModel->getParam('w_date','','POST');
        $w_hour = $this->SiteModel->getParam('w_hour','','POST',false,0);
        $w_min = $this->SiteModel->getParam('w_min','','POST',false,0);
        $w_time = ($w_hour*60)+$w_min;
        $w_percent = $this->SiteModel->getParam('w_percent','','POST',false,0);
        $w_percent = ($w_percent > 100) ? 100 : $w_percent;
        $w_content = $this->SiteModel->getParam('w_content','','POST',false,'');


        $wtdata = $this->Worker_model->getInfo($wt_idx,'wt.*');

        if( !isset($wtdata['wt_idx']) ){
            return $this->fail('잘못된 요청입니다.', 401);
        }
        
        if( $wtdata['uno'] != USER_INFO['memberuid'] ){
            return $this->fail('본인만 수정이 가능합니다.', 401);
        }

        
        $this->Worker_model->setWorkTime( $wtdata['pj_idx'], $wt_idx , $w_date , $w_time , $w_percent, $w_content );

        $this->SiteModel->setResStatus(1,'');
        return $this->respondCreated($this->SiteModel->response_data,200);
    }


    public function wdelete(){
        
        $wt_idx = $this->SiteModel->getParam('wt_idx','','POST');
        
        $wtdata = $this->Worker_model->getInfo($wt_idx,'wt.*');

        if( !isset($wtdata['wt_idx']) ){
            return $this->fail('잘못된 요청입니다.', 401);
        }
        
        if( $wtdata['uno'] != USER_INFO['memberuid'] ){
            return $this->fail('본인만 삭제가 가능합니다.', 401);
        }

        $res = $this->Worker_model->delWorker($wt_idx);
        if( $res ){
            $this->SiteModel->setResStatus(1,'');
            return $this->respondCreated($this->SiteModel->response_data,200);
        }else{
            return $this->fail('삭제도중 오류가 발생했습니다',401);
        }
    }

    public function wmodify(){
        
        $wt_idx = $this->SiteModel->getParam('wt_idx','','POST');
        $w_type = $this->SiteModel->getParam('w_type','','POST','');
        $start_date = $this->SiteModel->getParam('start_date','','POST','0000-00-00');
        $end_date = $this->SiteModel->getParam('end_date','','POST','0000-00-00');
        $due_date = $this->SiteModel->getParam('due_date','','POST','0000-00-00');
        $w_state = $this->SiteModel->getParam('w_state','','POST',1);
        $w_level = $this->SiteModel->getParam('w_level','','POST',1);
        $percent = $this->SiteModel->getParam('percent','','POST',0);
        $percent = ($percent > 100) ? 100 : $percent;


        $wtdata = $this->Worker_model->getInfo($wt_idx,'wt.*');

        if( !isset($wtdata['wt_idx']) ){
            return $this->fail('잘못된 요청입니다.', 401);
        }
        
        if( $wtdata['uno'] != USER_INFO['memberuid'] ){
            return $this->fail('본인만 수정이 가능합니다.', 401);
        }

        $updatas = [
            'wtype' => $w_type,
            'state' => $w_state,
            'start_date' => $start_date,
            'due_date' => $due_date,
            'end_date' => $end_date,
            'percent' => $percent,
            'level' => $w_level,
        ];
        
        $res = $this->Worker_model->updateWorker( $wt_idx , $updatas );
        if( $res ){
            $this->SiteModel->setResStatus(1,'');
            return $this->respondCreated($this->SiteModel->response_data,200);
        }else{
            return $this->fail('수정도중 오류가 발생했습니다',401);
        }
    }
}

