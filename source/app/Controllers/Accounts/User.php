<?php namespace App\Controllers\Accounts;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

use App\Models\Accounts\User_model;

class User extends BaseController
{
	use ResponseTrait;
	
	public function Profile( int $uno )
	{	

		$User_model = new User_model();

		$userinfo = $User_model->getUserInfoDetail($uno,'m.name,m.tel1,m.tel2,m.email,m.job_det,m.job_info,g.name as gname, l.name as lname, gp.name as gpname');

		$view_data['user_info'] = $userinfo;

		return $this->View('common/profile_pop',$view_data);

	}

	public function SearchPeople(){

		$User_model = new User_model();

        $sec_key = $this->SiteModel->getParam('sec_key','','POST',false,'');
		$sec_val = $this->SiteModel->getParam('sec_val','','POST',false,'');

		$where_arr = [' m.auth = 1'];
		$where_arr[] = "m.tflag = 'n'";
		if( $sec_key && $sec_val ){
			switch( $sec_key ){
				case 'name' :
					$where_arr[] = " m.name like '%".$sec_val."%'";
				break;
				case 'tel1' :
					$where_arr[] = " m.tel1 like '%".$sec_val."%'";
				break;
				case 'tel2' :
					$where_arr[] = " m.tel2 like '%".$sec_val."%'";
				break;

			}
		}
		
		
		$user_lists = $User_model->getUserList( "m.memberuid,m.name,m.tel1,m.tel2,m.stateText,g.name as gname,l.name as lname", $where_arr );

		$view_datas = ['ulists'=>$user_lists];
		$view_datas['state_color'] = $User_model->state_color;
		$view_datas['sec_data'] = [
			'sec_key' => $sec_key,
			'sec_val' => $sec_val
		];
		$template = $this->View('accounts/modal/search_people',$view_datas);

		$this->SiteModel->setResStatus(1,'');
		$this->SiteModel->setResData( 'template',$template);
		return $this->respondCreated($this->SiteModel->response_data,200);

	}
}
