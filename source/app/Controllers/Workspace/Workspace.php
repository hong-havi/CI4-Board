<?php namespace App\Controllers\Workspace;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Accounts\User_model;
use App\Models\Workspace\Workspace_model;
use App\Models\Workspace\Worker_model;
use App\Models\Site\Permission_model;

class Workspace extends BaseController
{
    use ResponseTrait;
	
	public $view_datas;
	public $workspace;
	public $Worker_model;
	public $workspace_info;
	public $workspace_lists;

	public function __construct()
	{
		$this->workspace = new Workspace_model();
		$this->Worker_model = new Worker_model();
		$this->workspace_lists = $this->workspace->getInfolists( 0, 0);
		$this->workspace->workspace_lists  = $this->workspace_lists;
            
		$MENU_MODULES = explode("/",MENU_INFO['module']); 
		switch( $MENU_MODULES['1'] ){
			case 'dashboard' :
				break;
			default : 			
				$this->workspace_info = $this->workspace_lists[$MENU_MODULES['1']];

				$this->view_datas = [
					'view_mode'=>'list',
					'Breadcrumb'=> '워크시트 - '.$this->workspace_info['name'],
					'Container' => ['class'=> 'container project_wrap'],
					'css_cell' => [
						['link'=>'_assets/css/views/workspace/workspace.css']
					],
					'script_cell' => [
						['link'=>'_assets/js/views/workspace/workspace.js']
					]
				];
			break;
		}
	}

	public function lists()
	{
		
		$pager = \Config\Services::pager();
		$page = $this->SiteModel->getParam('page_','','GET',false,'1');
		$pagesize = 20;


		$wp_where = [];

		$wp_where[] = " wp.p_type = '".$this->workspace_info['idx']."' ";


		$sec_favorit = $this->SiteModel->getParam('sec_favorit','','GET',false,'0');
		if( $sec_favorit ){			
			$wp_where[] = "(SELECT count(idx) FROM ".DB_T_wp_favorit." WHERE pj_idx = wp.pj_idx AND uno = '".USER_INFO['memberuid']."') > 0";
		}
		$sec_mywork = $this->SiteModel->getParam('sec_mywork','','GET',false,'0');
		if( $sec_mywork ){			
			$wp_where[] = "(SELECT count(wt_idx) FROM ".DB_T_wp_time." WHERE pj_idx = wp.pj_idx AND uno = '".USER_INFO['memberuid']."') > 0";
		}

		$cate1 = $this->SiteModel->getParam('cate1','','GET',false,'0');
		$cate1_nm = $this->SiteModel->getParam('cate1_nm','','GET',false,'');
		$cate2 = $this->SiteModel->getParam('cate2','','GET',false,'0');
		$cate2_nm = $this->SiteModel->getParam('cate2_nm','','GET',false,'');
		
		if( $cate1 > 0 && $cate2 > 0){
			if( $cate2 > 0 ){
				$wp_where[] = "wp.cate2 = '".$cate2_nm."' ";
			}else{
				$wp_where[] = "wp.cate1 = '".$cate1_nm."' ";
			}
		}

		$sec_wtype = $this->SiteModel->getParam('sec_wtype','','GET',false,'');
		if( $sec_wtype ){
			$wp_where[] = "wp.w_type = '".$sec_wtype."' ";
		}
		$sec_state = $this->SiteModel->getParam('sec_state','','GET',false,'');
		if( $sec_wtype ){
			$wp_where[] = "wp.state = '".$sec_state."' ";
		}

		
		$sec_key = $this->SiteModel->getParam('sec_key','','GET',false,'');
		$sec_val = $this->SiteModel->getParam('sec_val','','GET',false,'');
		if( $sec_key && $sec_val ){
			switch( $sec_key ){
				case 'pj_name' : 
					$wp_where[] = "wp.subject like '%".$sec_val."%'";
				break;
				case 'pj_idx' : 
					$wp_where[] = "wp.pj_idx = '".$sec_val."'";
				break;
				case 'wname' : 
					$wp_where[] = "m.name = '".$sec_val."'";
				break;
				case 'worker' : 
					$wp_where[] = "(SELECT count(wt_idx) FROM ".DB_T_wp_time." wt LEFT JOIN ".DB_T_s_mbrdata." wm ON wt.uno = wm.memberuid WHERE wt.pj_idx = wp.pj_idx AND wm.name LIKE '%".$sec_val."%') > 0";
				break;
			}
		}

		
		$sec_service = $this->SiteModel->getParam('sec_service','','GET',false,[]);
		$sec_servicetype = $this->SiteModel->getParam('sec_servicetype','','GET',false,[]);

		if( count($sec_service) > 0 ){
			$sec_service_tmp = [];
			foreach( $sec_service as $k => $v ){
				$sec_service_tmp[$k] = "'".$v."'";
			}
			$wp_where[] = "(SELECT count(idx) FROM ".DB_T_wp_pcate." WHERE pj_idx = wp.pj_idx AND type ='service' AND value in (".implode(",",$sec_service_tmp).") ) > 0";
		}
		
		if( count($sec_servicetype) > 0 ){
			$sec_servicetype_tmp = [];
			foreach( $sec_servicetype as $k => $v ){
				$sec_servicetype_tmp[$k] = "'".$v."'";
			}
			$wp_where[] = "(SELECT count(idx) FROM ".DB_T_wp_pcate." WHERE pj_idx = wp.pj_idx AND type ='sv_type' AND value in (".implode(",",$sec_servicetype_tmp).") ) > 0";
		}



		$selector = "wp.*,m.name as uname,
		(SELECT COUNT(idx) FROM ".DB_T_wp_favorit." WHERE uno = '".USER_INFO['memberuid']."' AND wp.pj_idx = pj_idx) AS favorit,
		IF( hidden = 1 , 
			IF( 
				((SELECT COUNT(idx) FROM ".DB_T_bbs_hidshow." WHERE wp.pj_idx = bbs_uid AND cate = 'ws' AND mtype = 'p' AND muid = '".USER_INFO['memberuid']."') > 0 
				OR (SELECT COUNT(idx) FROM ".DB_T_bbs_hidshow." WHERE wp.pj_idx = bbs_uid AND cate = 'ws' AND mtype = 'g' AND muid in (".implode(",",USER_INFO['pergroups']).")) > 0 
				OR wp.uno = '".USER_INFO['memberuid']."')
				, 
				1 ,
				0
			) 
			, 0
		) AS view_per
		";
		$datas = $this->workspace->getDatas( $page , $pagesize,$selector , $wp_where );
		$total = $this->workspace->getDataCount( $wp_where );

		
		$pagers = $pager->makeLinks( $page, $pagesize, $total ,'default_full',0,'');

		$this->view_datas['wpdatas'] = [
			'datas'=>$datas,
			'total'=>$total,
			
			'pager'=>$pagers,
			'page'=>$page
		];
		$this->view_datas['search']['form'] = [
			'group_lists' 	=> [],
			'work_type'		=> $this->workspace_info['cate'],
			'state'			=> $this->workspace->w_state_arr,
			'service_lists' => $this->workspace->service_arr,
			'service_type'	=> $this->workspace->sv_type_arr,
			'worker_state'	=> $this->Worker_model->w_state_arr,
		];		
		$this->view_datas['search']['value'] = [
			'sec_favorit'		=> $sec_favorit,
			'sec_mywork'		=> $sec_mywork,
			'cate1' 			=> $cate1,
			'cate1_nm' 			=> $cate1_nm,
			'cate2' 			=> $cate2,
			'cate2_nm' 			=> $cate2_nm,
			'sec_wtype' 		=> $sec_wtype,
			'sec_state' 		=> $sec_state,
			'sec_key' 			=> $sec_key,
			'sec_val' 			=> $sec_val,
			'sec_service' 		=> $sec_service,
			'sec_servicetype' 	=> $sec_servicetype,
		];

		$this->view_datas['view_mode'] = 'list';
		$this->view_datas['list_size'] = (MENU_INFO['uid'] == '644') ? 'list-big' : 'list-sm';
		$this->view_datas['script_cell'] = [
			["link"=>"_assets/vendors/js/jquery.serialize-object.min.js",'data'=>[]],
			['link'=>'_assets/js/views/workspace/workspace.js'],
			['link'=>'_assets/js/views/workspace/list.js']			
		];		
		return $this->View( 'workspace/list' , $this->view_datas );  
	}
	
	public function write(){		
		$group = new \App\Models\Accounts\Group_model();
		$glists = $group->getGrouplistReverse( USER_INFO['sosok'] );
		foreach( $glists as $gdata ){
			$gdatas[$gdata['depth']] = $gdata;
		}
		
		$ws_data = [
			'cate1' => $gdatas['2']['name'],
			'cate2' => $gdatas['3']['name'],
			'p_type' => $this->workspace_info['idx'],
			'w_type' => '',
			'pcate' => [],
			'sender_lists'=> [
					'1'=>[],
					'2'=>[]
				]
		];
		$this->view_datas['view_mode'] = 'write';
		$this->view_datas['mode'] = 'write';
		$this->view_datas['form_data'] = [
			'service_arr' => $this->workspace->service_arr,
			'sv_type_arr' => $this->workspace->sv_type_arr,
			'wtype_arr' => $this->workspace->workspace_lists,
			'w_state_arr' => $this->workspace->w_state_arr,
			'wlist' => $this->workspace_lists,
		];
		$this->view_datas['script_cell'] = [
			['link'=>'_assets/vendors/ckeditor5/build/ckeditor.js'],
			['link'=>'_assets/js/common/Editor.js'],
			['link'=>'_assets/js/common/Sender.js'],
			['link'=>'_assets/js/common/Attach.js'],			
			["link"=>"_assets/vendors/js/jquery.serialize-object.min.js",'data'=>[]],
			['link'=>'_assets/js/views/workspace/workspace.js'],
			['link'=>'_assets/js/views/workspace/write.js']			
		];		

		$this->view_datas['ws_data'] = $ws_data;
		$this->view_datas['action_url'] = '/site/'.MENU_INFO['uid'].'/workspace/proc/write';
		return $this->View( 'workspace/write' , $this->view_datas );  

	}

	public function modify( int $pj_idx ){
		$Permission_model = new Permission_model();

		$selector = "wp.*,	(SELECT COUNT(idx) FROM ".DB_T_wp_favorit." WHERE uno = '".USER_INFO['memberuid']."' AND wp.pj_idx = pj_idx) AS favorit";
		$wp_data = $this->workspace->getPJData( $pj_idx ,$selector);

		if( !isset($wp_data) ){
			return $this->SiteModel->getReturn( $this->request->isAJAX() ,'direct_move','잘못된 접근입니다..',['url'=>'/errors/404/40001'] );
		}

		if( $wp_data['uno'] != USER_INFO['memberuid'] ){
            return $this->SiteModel->getReturn( $this->request->isAJAX() ,'direct_move','접근이불가능합니다.',['url'=>'/errors/500/50003']);	
		}

		$group = new \App\Models\Accounts\Group_model();
		$glists = $group->getGrouplistReverse( USER_INFO['sosok'] );
		foreach( $glists as $gdata ){
			$gdatas[$gdata['depth']] = $gdata;
		}
		

		$sender_lists = $Permission_model->getList( 'ws', $pj_idx );
		$ws_data = $this->workspace->getPjDetail( $wp_data );

		$ws_data['sender_lists'] = $sender_lists;

		$this->view_datas['view_mode'] = 'write';
		$this->view_datas['mode'] = 'modify';
		$this->view_datas['form_data'] = [
			'service_arr' => $this->workspace->service_arr,
			'sv_type_arr' => $this->workspace->sv_type_arr,
			'wtype_arr' => $this->workspace->workspace_lists,
			'w_state_arr' => $this->workspace->w_state_arr,
			'wlist' => $this->workspace_lists,
		];
		$this->view_datas['script_cell'] = [
			['link'=>'_assets/vendors/ckeditor5/build/ckeditor.js'],
			['link'=>'_assets/js/common/Editor.js'],
			['link'=>'_assets/js/common/Sender.js'],
			['link'=>'_assets/js/common/Attach.js'],			
			["link"=>"_assets/vendors/js/jquery.serialize-object.min.js",'data'=>[]],
			['link'=>'_assets/js/views/workspace/workspace.js'],
			['link'=>'_assets/js/views/workspace/write.js']			
		];		

		$this->view_datas['ws_data'] = $ws_data;
		$this->view_datas['action_url'] = '/site/'.MENU_INFO['uid'].'/workspace/proc/modify';
		return $this->View( 'workspace/write' , $this->view_datas );  

	}
	
    public function views( int $pj_idx){
		$user = new User_model();
		$Permission_model = new Permission_model();

		$selector = "wp.*,	(SELECT COUNT(idx) FROM ".DB_T_wp_favorit." WHERE uno = '".USER_INFO['memberuid']."' AND wp.pj_idx = pj_idx) AS favorit";
		$wp_data = $this->workspace->getPJData( $pj_idx ,$selector);

		if( !isset($wp_data) ){
			return $this->SiteModel->getReturn( $this->request->isAJAX() ,'direct_move','잘못된 접근입니다..',['url'=>'/errors/404/40001'] );
		}

		$user_data = $user->getUserInfoDetail($wp_data['uno'],"m.*,g.name as gname ,l.name as lname,gp.name as gpname");
		
		$sender_lists = $Permission_model->getList( 'ws', $pj_idx );


		$wp_data = $this->workspace->getPjDetail( $wp_data );

		$this->view_datas['wp_data'] = $wp_data;
		$this->view_datas['user_data'] = $user_data;
		$this->view_datas['sender_lists'] = $sender_lists;
		$this->view_datas['worker_datas'] = [
			'ulists' =>$this->Worker_model->getWorkers( $pj_idx ),
			'form' => [
				'w_state_arr' => $this->Worker_model->w_state_arr,
				'w_type_arr' => $this->Worker_model->wk_type_arr,
			]	
		];
		$this->view_datas['form_data'] = [
			'w_state_arr' => $this->workspace->w_state_arr,
			'wlist' => $this->workspace_lists,
		];
		
		$this->view_datas['script_cell'] = [
			["link"=>"_assets/vendors/js/jquery.serialize-object.min.js",'data'=>[]],
			['link'=>'_assets/vendors/ckeditor5/build/ckeditor.js'],
			['link'=>'_assets/js/common/Editor.js'],
			['link'=>'_assets/js/views/comment/Comment.js'],
			['link'=>'_assets/js/views/workspace/workspace.js'],
			['link'=>'_assets/js/views/workspace/view.js'],
			['link'=>'_assets/js/common/Attach.js'],		
			['link'=>'_assets/js/common/Sender.js'],
			['link'=>'_assets/js/common/ImgResize.js'],
		];

		

		return $this->View( 'workspace/view' , $this->view_datas );  
	}
	
	


	public function getPwtype(){

		$wlist = $this->workspace_lists;
		
		$depth = $this->SiteModel->getParam('depth','','GET',false,'0');
		$pno = $this->SiteModel->getParam('pno','','GET',false,'0');
		
		if( $depth == '1' ){
			$wlist = $this->workspace_lists[$pno]['cate'];
		}

		$this->SiteModel->setResStatus(1,'');
		$this->SiteModel->setResData( 'wlist',$wlist);
		return $this->respondCreated($this->SiteModel->response_data,200);
	}

	public function formFindwork(){
		$pager = \Config\Services::pager();

		$sec_key = $this->SiteModel->getParam('sec_key','','GET',false,'');
		$sec_val = $this->SiteModel->getParam('sec_val','','GET',false,'');
		$page = $this->SiteModel->getParam('page','','GET',false,'1');
		$pagesize = 5;
		
		$pj_lists = [];
		$total = 0;

		if( $sec_key != '' && $sec_val != ''){
			$wp_where = [];
			switch( $sec_key ){
				case 'subject' :
					$wp_where[] = " wp.subject like '%".$sec_val."%' ";
					break;
				case 'pj_idx':
					$wp_where[] = " wp.pj_idx = '".$sec_val."' ";				
					break;
			}

			$wp_where[] = " ( (wp.hidden = 1 and ((SELECT COUNT(idx) FROM ".DB_T_bbs_hidshow." WHERE wp.pj_idx = bbs_uid AND cate = 'ws' AND mtype = 'p' AND muid = '".USER_INFO['memberuid']."') > 0 
			OR (SELECT COUNT(idx) FROM ".DB_T_bbs_hidshow." WHERE wp.pj_idx = bbs_uid AND cate = 'ws' AND mtype = 'g' AND muid in (".implode(",",USER_INFO['pergroups']).")) > 0 
			OR wp.uno = '".USER_INFO['memberuid']."') > 0) or wp.uno = '".USER_INFO['memberuid']."' )";

			$selector = "wp.pj_idx,wp.subject,wp.cate1,wp.cate2,wp.reg_date,wp.state,m.name as uname";
			$pj_lists = $this->workspace->getDatas( $page , $pagesize , $selector , $wp_where ,['worker'=>false,'pcate'=>false]);
			$total = $this->workspace->getDataCount( $wp_where );
			
		}

		
		$pagers = $pager->makeLinks( $page, $pagesize, $total ,'default_full',0,'');

		$view_datas['lists'] = $pj_lists;
		$view_datas['sec_arr'] = [
			'sec_key'	=> $sec_key,
			'sec_val'	=> $sec_val,
			'state'		=> $this->workspace->w_state_arr,
			'pager'		=> $pagers,
		];
		return $this->View( 'workspace/modal/findwork' ,$view_datas);  
	}

	public function loglist(){
		
		$pj_idx = $this->SiteModel->getParam('pj_idx','','GET',true);
		
		$log_datas = $this->workspace->getLog($pj_idx,'log.*,p.subject');

		$view_datas['log_datas'] = $log_datas;
		$view_datas['log_type_arr'] = $this->workspace->log_type_arr;

		return $this->View('workspace/modal/log_modal',$view_datas);

	}

}
