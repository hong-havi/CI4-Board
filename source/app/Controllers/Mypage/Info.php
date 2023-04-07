<?php namespace App\Controllers\Mypage;

use App\Controllers\BaseController;

class Info extends BaseController
{
	public function __construct()
	{
		
	}

	public function info()
	{		
                helper(['form','url']);
                $script_cell = array(
                        ["link"=>"_assets/vendors/js/jquery.validate.min.js",'data'=>[]],
                        ["link"=>"_assets/vendors/js/jquery.maskedinput.min.js",'data'=>[]],
                        ["link"=>"_assets/js/lib/postnum-Js.js",'data'=>[]],
                        ["link"=>"_assets/vendors/js/jquery.serialize-object.min.js",'data'=>[]],
                        ["link"=>"https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js?t=200423",'data'=>[]],
                        );
		$datas = ['script_cell'=>$script_cell,'USER_INFO'=>USER_INFO];
		return $this->View( 'mypage/info' , $datas );
	}

}
