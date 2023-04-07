<?php namespace App\Controllers\Workspace;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

use App\Models\Workspace\Workspace_model;
use App\Models\Workspace\Worker_model;
use App\Models\Accounts\User_model;

class Dashboard extends BaseController
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
            
	}


	public function views( $page_type = 'my'){
		
		$view_datas = [
			'Breadcrumb'=> '워크시트 - Dashboard',
			'Container' => ['class'=> 'container project_wrap'],
			'BredTab'=>[
				['name'=>'내 작업','link'=>'/site/677/workspace/dashboard/view/my','active_flag'=>(($page_type == 'my') ? true : false)],
				['name'=>'내 부서','link'=>'/site/677/workspace/dashboard/view/group','active_flag'=>(($page_type == 'group') ? true : false)],
				['name'=>'즐겨찾기','link'=>'/site/677/workspace/dashboard/view/fav','active_flag'=>(($page_type == 'fav') ? true : false)],
				['name'=>'담당자조회','link'=>'/site/677/workspace/dashboard/view/people','active_flag'=>(($page_type == 'people') ? true : false)]
			],
			'css_cell' => [
				['link'=>'_assets/css/views/workspace/workspace.css'],
				['link'=>'_assets/vendors/fullcalendar/main.css']
			],
			'script_cell' => [
				['link'=>'_assets/js/views/workspace/workspace.js'],
				['link'=>'_assets/js/views/workspace/dashboard.js'],
				['link'=>'_assets/vendors/js/Nwagon.js'],
				['link'=>'_assets/vendors/fullcalendar/main.min.js']
			]
		];
		
		$view_datas['page_type'] = $page_type;
		$view_datas['forms'] = [
			'wp_state_arr' => $this->workspace->w_state_arr,
			'worker_state_arr' => $this->Worker_model->w_state_arr,
		];

		
		switch( $page_type ){
			case 'people' :
				$view_datas['script_cell'][] = ['link'=>'_assets/js/views/workspace/dashboard_people.js'];
				$view_file = 'workspace/dashboard/people';
				break;
			default : 
				$view_datas['script_cell'][] = ['link'=>'_assets/js/views/workspace/dashboard_main.js'];
				$view_file = 'workspace/dashboard';
				break;	
		}

		return $this->View($view_file,$view_datas);
	}

	public function getDList(){
		$pager = \Config\Services::pager();
        
		$pagetype = $this->SiteModel->getParam('pagetype','','GET',false);		
        $state_arr = $this->SiteModel->getParam('state_arr','','GET',false,[]);        
		$page = $this->SiteModel->getParam('page','','GET',false,'1');
		$pagesize = 5;

		$where_arr = array();
		
		if( count($state_arr) > 0 ){
			$sub_where = " AND state IN (".implode(",",$state_arr).") ";
		}else{
			$sub_where = "";
		}

        switch( $pagetype ){
            case 'my' :
                $where_arr[] = " (SELECT COUNT(wt_idx) FROM ".DB_T_wp_time." WHERE pj_idx = wp.pj_idx AND uno = '".USER_INFO['memberuid']."' ".$sub_where.") > 0 ";                
				break;
			case 'group' :
				$where_arr[] = " wp.usosok = '".USER_INFO['sosok']."' ";
				break;
			case 'fav' :
				$where_arr[] = " (SELECT COUNT(idx) FROM ".DB_T_wp_favorit." WHERE uno = '".USER_INFO['memberuid']."' AND wp.pj_idx = pj_idx) > 0 ";
				break;
			default :
				return "";
				break;
        }


        $selector = "wp.*,(SELECT COUNT(idx) FROM ".DB_T_wp_favorit." WHERE uno = '".USER_INFO['memberuid']."' AND wp.pj_idx = pj_idx) AS favorit ";
		$datas = $this->workspace->getDatas( $page , $pagesize, $selector,  $where_arr );
		$total = $this->workspace->getDataCount( $where_arr );

		$pagers = $pager->makeLinks( $page, $pagesize, $total ,'default_full',0,'');
		$view_datas = [
			'lists' => $datas,
			'worker_state_arr' => $this->Worker_model->w_state_arr,
			'workspace_lists' => $this->workspace_lists,
			'pager_data'		=> $pagers,
		];
		
		return $this->View('workspace/dashboard/proj-list',$view_datas);
		
	}

	public function getCalendar(){
		
		helper('vdatacheck');
		
		$pagetype = $this->SiteModel->getParam('pagetype','','POST',false);		
		$sec_ptype = $this->SiteModel->getParam('sec_ptype','','POST',false,'');
		if( $sec_ptype ){
			$sec_ptype = explode(",",$sec_ptype);
		}else{
			$sec_ptype = [];
		}

		
		$sec_start = $this->SiteModel->getParam('start','','POST',false);
		$sec_end = $this->SiteModel->getParam('end','','POST',false);
		
		$where_arr = array();
        switch( $pagetype ){
			case 'my' :				
                $where_arr[] = " wt.uno = '".USER_INFO['memberuid']."' ";                
                break;
			case 'group' :				
				$where_arr[] = " wp.usosok = '".USER_INFO['memberuid']."' ";                
				break;
			case 'fav' :
				$where_arr[] = " (SELECT COUNT(idx) FROM ".DB_T_wp_favorit." WHERE uno = '".USER_INFO['memberuid']."' AND wp.pj_idx = pj_idx) > 0 ";
				break;
			case 'people' :
				$uno = $this->SiteModel->getParam('uno','','POST',false);		
				$where_arr[] = " wt.uno = '".$uno."' "; 
				break;
		}

		if( count($sec_ptype) > 0 ){
			$where_arr[] = " wp.p_type in (".implode(",",$sec_ptype).") ";
		}
		
		$where_arr[] = "(('".$sec_start."' <= wp.start_date AND '".$sec_end."' >= wt.start_date) OR ('".$sec_start."' <= wt.due_date AND '".$sec_end."' >= wt.due_date) 
		OR (wt.start_date <= '".$sec_end."' AND wt.due_date >= '".$sec_end."') OR ('".$sec_start."' <= wt.end_date AND '".$sec_end."' >= wt.end_date) 
		OR (wt.start_date <= '".$sec_end."' AND wt.end_date >= '".$sec_end."')) ";

        $selector = "wt.pj_idx,wt.wtype,wp.p_type,wp.subject,wt.start_date,wt.end_date,wt.due_date";
		$datas = $this->Worker_model->getWorkerSec( $selector,  $where_arr );

		$make_json = [];
		foreach( $datas as $data ){

			$start_date = VDataCheck( $data['start_date'], 'date' );
			$end_date = VDataCheck( $data['end_date'], 'date' );
			$due_date = VDataCheck( $data['due_date'], 'date' );

			if ( !$start_date ){
				continue;
			}
			
			if ( !$end_date && !$due_date ){
				continue;
			}

			$_tmp = [];
			$_tmp['pj_idx'] = $data['pj_idx'];
			$_tmp['title'] = (($data['wtype']) ? "[".$data['wtype']."] " : "").$data['subject'];
			$_tmp['start'] = $start_date;
			$_tmp['end'] = ($end_date) ? $end_date : $due_date;
			$_tmp['color'] = $this->workspace_lists[$data['p_type']]['color'];
			$_tmp['url'] = '/site/'.$this->workspace_lists[$data['p_type']]['cinfo']."/workspace/view/".$data['pj_idx'];
			$make_json[] = $_tmp;
		}

		echo json_encode($make_json);
		exit;
	}

	function getHistory(){
		
		$pagetype = $this->SiteModel->getParam('pagetype','','GET',false);	
		$page = $this->SiteModel->getParam('page','','GET',false,1);
		$pagesize = $this->SiteModel->getParam('pagesize','','GET',false,10);


		$where_arr = [];

        switch( $pagetype ){
			case 'my' :				
                $where_arr[] = " (SELECT COUNT(wt_idx) FROM ".DB_T_wp_time." WHERE pj_idx = wp.pj_idx AND uno = '".USER_INFO['memberuid']."' ) > 0 ";                           
                break;
			case 'group' :
				$where_arr[] = " wp.usosok = '".USER_INFO['sosok']."' ";
				break;
			case 'fav' :
				$where_arr[] = " (SELECT COUNT(idx) FROM ".DB_T_wp_favorit." WHERE uno = '".USER_INFO['memberuid']."' AND wp.pj_idx = pj_idx) > 0 ";
				break;
		}

		$log_datas = $this->workspace->getLogAll( $page, $pagesize, "log.*,wp.subject", $where_arr );

		$view_datas['log_datas'] = $log_datas['datas'];
		$view_datas['log_datas_cnt'] = $log_datas['count'];
		$view_datas['log_type_arr'] = $this->workspace->log_type_arr;

		$template = $this->View('workspace/list-log-det',$view_datas);

		$this->SiteModel->setResStatus(1,'');
		$this->SiteModel->setResData( 'template',$template);
		$this->SiteModel->setResData( 'count',$log_datas['count']);
		return $this->respondCreated($this->SiteModel->response_data,200);

	}

	function findPeople(){

		$user = new User_model();
		$name = $this->SiteModel->getParam('name','','POST',true);	

		$where_arr = [" m.name like '%".$name."%' "," m.auth = 1 "];
		$ulists = $user->getUserList( "m.memberuid,m.name,l.name as lname,g.name as gname", $where_arr );

		$view_datas = [];
		$view_datas['ulists'] = $ulists;
		$template = $this->View('workspace/modal/list_user',$view_datas);

		$this->SiteModel->setResStatus(1,'');
		$this->SiteModel->setResData( 'template',$template);
		return $this->respondCreated($this->SiteModel->response_data,200);

	}
 }
