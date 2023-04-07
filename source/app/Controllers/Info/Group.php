<?php namespace App\Controllers\Info;

use App\Controllers\BaseController;
use App\Models\Accounts\Group_model;
use CodeIgniter\API\ResponseTrait;
class Group extends BaseController
{
	use ResponseTrait;
	
	public function __construct()
	{
		
	}


	public function lists(){
        $group = new Group_model();

		$depth = $this->SiteModel->getParam('depth','','GET',false,'');
		$pno = $this->SiteModel->getParam('pno','','GET',false,'0');

		if( $depth == '3' && $pno == 0 ){
			$glists = [];
		}else{
			$glists = $group->getGroupOnlyIist($pno,$depth);
		}

		$this->SiteModel->setResStatus(1,'');
		$this->SiteModel->setResData( 'glists',$glists);
		return $this->respondCreated($this->SiteModel->response_data,200);
	}
}
