<?php namespace App\Controllers\Workspace;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

use App\Models\Workspace\Workspace_model;
use App\Models\Workspace\Status_model;

class Status extends BaseController
{
    use ResponseTrait;
	
	public $workspace;

	public function __construct()
	{
		$this->workspace = new Workspace_model();
            
	}


	public function views( ){


        $status = new Status_model();
        
		$sec_view = $this->SiteModel->getParam('sec_view','','GET',false,'group');
		$sec_year = $this->SiteModel->getParam('sec_year','','GET',false,date("Y"));
        $sec_month = $this->SiteModel->getParam('sec_month','','GET',false,date("m"));
        
		$sec_service = $this->SiteModel->getParam('sec_service','','GET',false,[]);
        $sec_servicetype = $this->SiteModel->getParam('sec_servicetype','','GET',false,[]);

        $status_datas = $status->getStatus_group($sec_year,$sec_month,'p_type,cate1');
		$status_datas = $status->makeDatas($status_datas);

		$view_datas = [
			'Breadcrumb'=> '워크시트 - 작업통계',
			'Container' => ['class'=> 'container statistics_wrap'],
			'css_cell' => [
				['link'=>'_assets/css/views/workspace/status.css'],
				['link'=>'_assets/css/views/workspace/workspace.css'],
				['link'=>'_assets/vendors/fullcalendar/main.css']
			],
			'script_cell' => [
				['link'=>'_assets/js/views/workspace/status.js'],
				['link'=>'_assets/vendors/js/Nwagon.js'],
			]
		];
		
		$view_datas['search']['form'] = [
			'service_lists' => $this->workspace->service_arr,
			'service_type'	=> $this->workspace->sv_type_arr,
        ];		
		$view_datas['search']['value'] = [
			'sec_year'			=> $sec_year,
			'sec_month'			=> $sec_month,
			'sec_service' 		=> $sec_service,
			'sec_servicetype' 	=> $sec_servicetype,
		];

		$view_datas['status'] = [
			'datas' => $status_datas['datas'],
			'total' => $status_datas['total'],
		];

		return $this->View('workspace/status',$view_datas);
	}

 }
