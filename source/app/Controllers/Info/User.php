<?php namespace App\Controllers\Info;

use App\Controllers\BaseController;
use App\Models\Accounts\User_model;
use CodeIgniter\API\ResponseTrait;
class User extends BaseController
{
	use ResponseTrait;
	
	public function __construct()
	{
		
	}

	public function list()
	{	
        
	}

	public function listmention(){
		$cache = \Config\Services::cache();

		$search_string = $this->SiteModel->getParam( 'secstring','','POST',false,"" );
	
		$user_lists = $cache->get('umention');
		$this->SiteModel->setResStatus(1,'');
		$this->SiteModel->setResData( 'items',$user_lists);
		return $this->respondCreated($this->SiteModel->response_data,200);
	}
}
