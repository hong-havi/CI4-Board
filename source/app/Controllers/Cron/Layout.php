<?php namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\Accounts\User_model;

use App\Models\Common\Main_model;

class Main extends BaseController
{
	public function __construct()
	{

	}

	public function setMaindata()
	{

		$main = new Main_model();
        $User = new User_model();
        $user_lists = $User->getUserList("m.memberuid,m.admin,m.site", ['m.auth = 1']);

		//$main_datas = $main->getMainData();
	}



	//--------------------------------------------------------------------

}
