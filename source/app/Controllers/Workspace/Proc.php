<?php namespace App\Controllers\Workspace;

use App\Controllers\BaseController;
use App\Models\Workspace\Workspace_model;
use App\Models\Site\Permission_model;
use App\Models\Common\Paper_model;
use CodeIgniter\API\ResponseTrait;

class Proc extends BaseController
{
	
    use ResponseTrait;
    
	private $Workspace_model;
	private $Permission_model;
	private $Board_info = [];

	public function __construct()
	{
		$this->Workspace_model = new Workspace_model();
		$this->Permission_model = new Permission_model();
	}


    public function write(){

        $validation =  \Config\Services::validation();

        if( !$this->request->isAJAX() ){
            return $this->fail('잘못된 요청입니다.', 401);
        }


        $validation->setRules([
            'subject' => [
                'label' => 'subject', 'rules' => 'required', 'errors' =>[
                    'required' => '제목을 입력해 주세요.'
                ]
            ],
            'cate1' => [
                'label' => 'cate1', 'rules' => 'required', 'errors' =>[
                    'required' => '본부를 선택해 주세요.'
                ]
            ],
            'cate2' => [
                'label' => 'cate2', 'rules' => 'required', 'errors' =>[
                    'required' => '사업부/팀을 선택해 주세요.'
                ]
            ],
            'service' => [
                'label' => 'service', 'rules' => 'required', 'errors' =>[
                    'required' => '서비스를 선택해 주세요.'
                ]
            ],
            'p_type' => [
                'label' => 'p_type', 'rules' => 'required', 'errors' =>[
                    'required' => '작업유형을 선택해 주세요.'
                ]
            ],
            'w_type' => [
                'label' => 'w_type', 'rules' => 'required', 'errors' =>[
                    'required' => '작업유형을 선택해 주세요.'
                ]
            ],
            'start_date' => [
                'label' => 'start_date', 'rules' => 'required', 'errors' =>[
                    'required' => '시작일을 선택해 주세요.'
                ]
            ],
            'due_date' => [
                'label' => 'due_date', 'rules' => 'required', 'errors' =>[
                    'required' => '완료예정일을 선택해 주세요.'
                ]
            ],
            'content' => [
                'label' => 'content', 'rules' => 'required', 'errors' =>[
                    'required' => '내용을 입력해 주세요.'
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


        $depth = $this->SiteModel->getParam('depth','','POST',false,0);
        $parentmbr = $this->SiteModel->getParam('parentmbr','','POST',false,0);
        
        $subject = $this->SiteModel->getParam('subject','','POST');
        $content = $this->SiteModel->getParam('content','','POST');
        
        $cate1 = $this->SiteModel->getParam('cate1','','POST',false);
        $cate2 = $this->SiteModel->getParam('cate2','','POST',false);
        $cate3 = "";

        $service = $this->SiteModel->getParam('service','','POST',false,[]);
        $sv_type = $this->SiteModel->getParam('sv_type','','POST',false,[]);
        $sv_link = $this->SiteModel->getParam('sv_link','','POST',false,'');
        $sv_links_link = $this->SiteModel->getParam('sv_links_link','','POST',false,[]);
        $sv_links_pj = $this->SiteModel->getParam('sv_links_pj','','POST',false,[]);
            
        $p_type = $this->SiteModel->getParam('p_type','','POST',false);
        $sosok_key = $this->SiteModel->getParam('sosok_key','','POST',false);
        $w_type = $this->SiteModel->getParam('w_type','','POST',false);
        $state = $this->SiteModel->getParam('state','','POST',false,1);
        
        $start_date = $this->SiteModel->getParam('start_date','','POST',false,'0000-00-00');
        $due_date = $this->SiteModel->getParam('due_date','','POST',false,'0000-00-00');
        $end_date = $this->SiteModel->getParam('end_date','','POST',false,'0000-00-00');
        $percent = $this->SiteModel->getParam('percent','','POST',false,'0');
        $percent = ($percent > 100 ) ? 100 : $percent;

        
        $opt_secret = $this->SiteModel->getParam('opt_secret','','POST',false,0);
        $opt_teams = $this->SiteModel->getParam('opt_teams','','POST',false,0);
        $opt_mail = $this->SiteModel->getParam('opt_mail','','POST',false,0);
        $opt_emergency = $this->SiteModel->getParam('opt_emergency','','POST',false,0);
        $importance = "";

        $sender_list_1 = $this->SiteModel->getParam('sender_list_1','','POST',false,'');
        $sender_list_2 = $this->SiteModel->getParam('sender_list_2','','POST',false,'');
        

        $bbs_upload = $this->SiteModel->getParam('bbs_upload','','POST',false,'');

        $set_data = [
            'uno'           => USER_INFO['memberuid'],
            'p_type'        => $p_type,
            'sosok'         => USER_INFO['sosok'],
            'usosok'        => $sosok_key,
            'cate1'         => $cate1,
            'cate2'         => $cate2,
            'cate3'         => $cate3,
            'w_type'        => $w_type,
            'sv_link'       => $sv_link,
            'importance'    => $importance,
            'subject'       => $subject,
            'content'       => $content,
            'state'         => $state,
            'hidden'        => $opt_secret,
            'start_date'    => $start_date,
            'due_date'      => $due_date,
            'end_date'      => $end_date,
            'percent'       => $percent,
            'uploads'       => $bbs_upload,
            'hit'           => 0,
            'cmt_cnt'       => 0,
            'reg_date'      => date("Y-m-d H:i:s"),
            'mod_date'      => date("Y-m-d H:i:s")

        ];
        $pj_idx = $this->Workspace_model->setInsert($set_data);

        if( !$pj_idx ){
            return $this->fail('데이터 처리도중 오류가 발생했습니다.');
        }

        $this->Workspace_model->setPcate($pj_idx,'service',$service);
        $this->Workspace_model->setPcate($pj_idx,'sv_type',$sv_type);
        $this->Workspace_model->setPcate($pj_idx,'l_link',$sv_links_link);
        $this->Workspace_model->setPcate($pj_idx,'l_pj_idx',$sv_links_pj);

        $add_sender_1 = $this->Permission_model->setDatas('ws',1,$pj_idx,$sender_list_1); //수신
        $add_sender_2 = $this->Permission_model->setDatas('ws',2,$pj_idx,$sender_list_2); //참조

        $link = "/site/".MENU_INFO['uid']."/workspace/view/".$pj_idx;

        $paper = new Paper_model();
        $subject = "#".$pj_idx." ".$subject;
        $memo_1 = $paper->makeTpl( '수신', $subject, MENU_INFO['name']." 게시판에서 글이 작성 되었습니다.", $link);
        $paper->send( USER_INFO['memberuid'], $add_sender_1, $memo_1, 'text' ,$link , $pj_idx, 14 );
        $memo_2 = $paper->makeTpl( '참조', $subject, MENU_INFO['name']." 게시판에서 글이 작성 되었습니다.", $link);
        $paper->send( USER_INFO['memberuid'], $add_sender_2, $memo_2, 'text' ,$link , $pj_idx, 15 );

        $log_datas = [['log_type'=>'1','l_type'=>'pj','l_field'=>'new','l_field_text'=>'신규 작업 추가','lb_field'=>'','la_field'=>'']];
        $this->Workspace_model->setLog( $pj_idx, $log_datas );
    
        $this->SiteModel->setResStatus(1,'');
        $this->SiteModel->setResData('link',$link);
        return $this->respondCreated($this->SiteModel->response_data,200);
    }

    

    public function modify(){

        $validation =  \Config\Services::validation();

        if( !$this->request->isAJAX() ){
            return $this->fail('잘못된 요청입니다.', 401);
        }


        $validation->setRules([
            'subject' => [
                'label' => 'subject', 'rules' => 'required', 'errors' =>[
                    'required' => '제목을 입력해 주세요.'
                ]
            ],
            'cate1' => [
                'label' => 'cate1', 'rules' => 'required', 'errors' =>[
                    'required' => '본부를 선택해 주세요.'
                ]
            ],
            'cate2' => [
                'label' => 'cate2', 'rules' => 'required', 'errors' =>[
                    'required' => '사업부/팀을 선택해 주세요.'
                ]
            ],
            'service' => [
                'label' => 'service', 'rules' => 'required', 'errors' =>[
                    'required' => '서비스를 선택해 주세요.'
                ]
            ],
            'p_type' => [
                'label' => 'p_type', 'rules' => 'required', 'errors' =>[
                    'required' => '작업유형을 선택해 주세요.'
                ]
            ],
            'w_type' => [
                'label' => 'w_type', 'rules' => 'required', 'errors' =>[
                    'required' => '작업유형을 선택해 주세요.'
                ]
            ],
            'start_date' => [
                'label' => 'start_date', 'rules' => 'required', 'errors' =>[
                    'required' => '시작일을 선택해 주세요.'
                ]
            ],
            'due_date' => [
                'label' => 'due_date', 'rules' => 'required', 'errors' =>[
                    'required' => '완료예정일을 선택해 주세요.'
                ]
            ],
            'content' => [
                'label' => 'content', 'rules' => 'required', 'errors' =>[
                    'required' => '내용을 입력해 주세요.'
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


        $depth = $this->SiteModel->getParam('depth','','POST',false,0);
        $parentmbr = $this->SiteModel->getParam('parentmbr','','POST',false,0);
        
        $pj_idx = $this->SiteModel->getParam('bbs_uid','','POST');

        $subject = $this->SiteModel->getParam('subject','','POST');
        $content = $this->SiteModel->getParam('content','','POST');
        
        $cate1 = $this->SiteModel->getParam('cate1','','POST',false);
        $cate2 = $this->SiteModel->getParam('cate2','','POST',false);
        $cate3 = "";

        $service = $this->SiteModel->getParam('service','','POST',false,[]);
        $sv_type = $this->SiteModel->getParam('sv_type','','POST',false,[]);
        $sv_link = $this->SiteModel->getParam('sv_link','','POST',false,'');
        $sv_links_link = $this->SiteModel->getParam('sv_links_link','','POST',false,[]);
        $sv_links_pj = $this->SiteModel->getParam('sv_links_pj','','POST',false,[]);
        
        $p_type = $this->SiteModel->getParam('p_type','','POST',false);
        $sosok_key = $this->SiteModel->getParam('sosok_key','','POST',false);
        $w_type = $this->SiteModel->getParam('w_type','','POST',false);
        $state = $this->SiteModel->getParam('state','','POST',false,1);
        
        $start_date = $this->SiteModel->getParam('start_date','','POST',false,'0000-00-00');
        $due_date = $this->SiteModel->getParam('due_date','','POST',false,'0000-00-00');
        $end_date = $this->SiteModel->getParam('end_date','','POST',false,'0000-00-00');
        $percent = $this->SiteModel->getParam('percent','','POST',false,'0');
        $percent = ($percent > 100 ) ? 100 : $percent;

        
        $opt_secret = $this->SiteModel->getParam('opt_secret','','POST',false,0);
        $opt_teams = $this->SiteModel->getParam('opt_teams','','POST',false,0);
        $opt_mail = $this->SiteModel->getParam('opt_mail','','POST',false,0);
        $opt_emergency = $this->SiteModel->getParam('opt_emergency','','POST',false,0);
        $importance = "";

        $opt_sender_1 = $this->SiteModel->getParam('opt_sender_1','0','POST',false);
        $opt_sender_2 = $this->SiteModel->getParam('opt_sender_2','0','POST',false);
        
        $sender_list_1 = $this->SiteModel->getParam('sender_list_1','','POST',false,'');
        $sender_list_2 = $this->SiteModel->getParam('sender_list_2','','POST',false,'');
        

        $bbs_upload = $this->SiteModel->getParam('bbs_upload','','POST',false,'');



		$selector = "wp.*";
		$wp_data = $this->Workspace_model->getPJData( $pj_idx ,$selector);

		if( $wp_data['uno'] != USER_INFO['memberuid'] ){
            return $this->fail('작성자만 수정이 가능합니다.');
        }
        
        $set_data = [
            'p_type'        => $p_type,
            'sosok'         => USER_INFO['sosok'],
            'usosok'        => $sosok_key,
            'cate1'         => $cate1,
            'cate2'         => $cate2,
            'cate3'         => $cate3,
            'w_type'        => $w_type,
            'sv_link'       => $sv_link,
            'importance'    => $importance,
            'subject'       => $subject,
            'content'       => $content,
            'state'         => $state,
            'hidden'        => $opt_secret,
            'start_date'    => $start_date,
            'due_date'      => $due_date,
            'end_date'      => $end_date,
            'percent'       => $percent,
            'uploads'       => $bbs_upload,
            'mod_date'      => date("Y-m-d H:i:s")

        ];
        $updateLog = [];
        $check = 0;
        foreach( $set_data as $field => $value ){
            if( $wp_data[$field] != $value ){
                $updateLog['logs'][] = ['log_type'=>'3','l_type'=>'pj','l_field'=>$field,'l_field_text'=>$this->field_nm[$field],'lb_field'=>$wp_data[$field],'la_field'=>$value];
                $check++;
            }
        }
        
        if( $check > 0 ){
            $this->Workspace_model->setLog($pj_idx,$this->oneUpdata['logs']);
        }

        $pj_idx = $this->Workspace_model->setUpdate($pj_idx, $set_data);

        if( !$pj_idx ){
            return $this->fail('데이터 처리도중 오류가 발생했습니다.');
        }

        $this->Workspace_model->setPcate($pj_idx,'service',$service);
        $this->Workspace_model->setPcate($pj_idx,'sv_type',$sv_type);
        $this->Workspace_model->setPcate($pj_idx,'l_link',$sv_links_link);
        $this->Workspace_model->setPcate($pj_idx,'l_pj_idx',$sv_links_pj);

        $add_sender_1 = $this->Permission_model->setDatas('ws',1,$pj_idx,$sender_list_1); //수신
        $add_sender_2 = $this->Permission_model->setDatas('ws',2,$pj_idx,$sender_list_2); //참조

        $link = "/site/".MENU_INFO['uid']."/workspace/view/".$pj_idx;

        $rsend_list_1 = ( $opt_sender_1 == '1' ) ? $this->Permission_model->exLists($sender_list_1) : $add_sender_1;
        $rsend_list_2 = ( $opt_sender_2 == '1' ) ? $this->Permission_model->exLists($sender_list_2) : $add_sender_2;

        $paper = new Paper_model();
        $subject = "#".$pj_idx." ".$subject;
        $memo_1 = $paper->makeTpl( '수신-수정', $subject, MENU_INFO['name']." 게시판에서 글이 작성 되었습니다.", $link);
        $paper->send( USER_INFO['memberuid'], $rsend_list_1, $memo_1, 'text' ,$link , $pj_idx, 14 );
        $memo_2 = $paper->makeTpl( '참조-수정', $subject, MENU_INFO['name']." 게시판에서 글이 작성 되었습니다.", $link);
        $paper->send( USER_INFO['memberuid'], $rsend_list_2, $memo_2, 'text' ,$link , $pj_idx, 15 );

        $log_datas = [['log_type'=>'1','l_type'=>'pj','l_field'=>'new','l_field_text'=>'신규 작업 추가','lb_field'=>'','la_field'=>'']];
        $this->Workspace_model->setLog( $pj_idx, $log_datas );
    
        $this->SiteModel->setResStatus(1,'');
        $this->SiteModel->setResData('link',$link);
        return $this->respondCreated($this->SiteModel->response_data,200);
    }

    public function infosave(){
        
        $validation =  \Config\Services::validation();

        if( !$this->request->isAJAX() ){
            return $this->fail('잘못된 요청입니다.', 401);
        }

        $validation->setRules([
            'pj_idx' => [
                'label' => 'pj_idx', 'rules' => 'required', 'errors' =>[
                    'required' => '잘못된 접근입니다'
                ]
            ],
            'state' => [
                'label' => 'state', 'rules' => 'required', 'errors' =>[
                    'required' => '상태를 선택해 주세요.'
                ]
            ],
            'start_date' => [
                'label' => 'start_date', 'rules' => 'required', 'errors' =>[
                    'required' => '시작일을 선택해 주세요.'
                ]
            ],
            'due_date' => [
                'label' => 'due_date', 'rules' => 'required', 'errors' =>[
                    'required' => '완료 희망일을 선택해 주세요.'
                ]
            ],
        ]);

        $validation->withRequest($this->request)
        ->run();
        if( count($validation->getErrors()) > 0 ){
            foreach( $validation->getErrors() as $k => $error_message ){
                return $this->fail($error_message, 400);
            }
        }

        
        $pj_idx = $this->SiteModel->getParam('pj_idx','','POST');
        $state = $this->SiteModel->getParam('state','','POST',false);
        $start_date = $this->SiteModel->getParam('start_date','','POST',false,'0000-00-00');
        $end_date = $this->SiteModel->getParam('end_date','','POST',false,'0000-00-00');
        $due_date = $this->SiteModel->getParam('due_date','','POST',false,'0000-00-00');
        $percent = $this->SiteModel->getParam('percent','','POST',false);
        $percent = ($percent > 100) ? 100 : $percent;

        $this->Workspace_model->setOneUpdate($pj_idx,['state'=>$state,'start_date'=>$start_date,'end_date'=>$end_date,'due_date'=>$due_date,'percent'=>$percent]);

        
        $this->SiteModel->setResStatus(1,'');
        return $this->respondCreated($this->SiteModel->response_data,200);
    }

    public function setFile(){
        $attach = new \App\Models\Common\Attach\Info_model();
        
        $pj_idx = $this->SiteModel->getParam('pj_idx','','POST');
        $after_uploads_tmp = $this->SiteModel->getParam('filelists','','POST',false,'');
        if( $after_uploads_tmp ){
            $after_uploads = explode(",",$after_uploads_tmp);
        }else{
            $after_uploads = [];
        }

        $pj_info = $this->Workspace_model->getPJData( $pj_idx,'wp.uploads');

        if($pj_info['uploads']){
            $before_uploads = explode(",",$pj_info['uploads']);
        }else{
            $before_uploads = [];
        }
        
        $add_lists = [];
        foreach( $after_uploads as $value  ){
            if( !in_array($value,$before_uploads) ){
                $add_lists[] = $value;
            }
        }

        $del_lists = [];
        foreach( $before_uploads as $value ){
            if( !in_array($value,$after_uploads) ){
                $del_lists[] = $value;
            }
        }

        $log_datas = [];
        foreach( $add_lists as $value ){
            $finfo = $attach-> getFileInfo( $value );
            $fname = "#F".$finfo['uid']." [".$finfo['caption']."] ".$finfo['name'];
            $log_datas[] = ['log_type'=>'4','l_type'=>'file','l_field'=>'add','l_field_text'=>'파일추가','lb_field'=>$value,'la_field'=>$fname];
        }
        foreach( $del_lists as $value ){
            $finfo = $attach-> getFileInfo( $value );
            $fname = "#F".$finfo['uid']." [".$finfo['caption']."] ".$finfo['name'];
            $log_datas[] = ['log_type'=>'5','l_type'=>'file','l_field'=>'del','l_field_text'=>'파일삭제','lb_field'=>$value,'la_field'=>$fname];
        }

        $this->Workspace_model->setLog( $pj_idx, $log_datas );

        $updatas = ['uploads'=>$after_uploads_tmp];
        $this->Workspace_model->setUpdate( $pj_idx , $updatas );

        
        $this->SiteModel->setResStatus(1,'');
        return $this->respondCreated($this->SiteModel->response_data,200);
    }

    public function favorit(){


        $pj_idx = $this->SiteModel->getParam('pj_idx','','POST');
        
        $check = $this->Workspace_model->favoritInfo(  $pj_idx );

        if( !isset($check['idx']) ){
            $res = $this->Workspace_model->favoritInsert($pj_idx);
            $fav_state = '1';
        }else{
            $res = $this->Workspace_model->favoritDelete($pj_idx);
            $fav_state = '0';
        }

        if( $res ){                
            $this->SiteModel->setResStatus(1,'');
			$this->SiteModel->setResData('fav_state',$fav_state);
            return $this->respondCreated($this->SiteModel->response_data,200);
        }else{
            $this->fail('데이터 처리도중 오류가 발생했습니다.');
        }
    }
}

