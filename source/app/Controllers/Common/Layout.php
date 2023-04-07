<?php namespace App\Controllers\Common;

use App\Controllers\BaseController;

class Layout extends BaseController
{
	public function UserNavi()
	{	
		return view('layouts/default/navi_user.php');
	}

}
