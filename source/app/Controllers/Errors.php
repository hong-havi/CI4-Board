<?php namespace App\Controllers;
use App\Controllers\BaseController;

class Errors extends BaseController
{
	public function __construct()
	{

	}
	public function index( $status ="", $err_code ="" )
	{
		$err_btn = ['text'=>'돌아가기','link'=>'javascript:history.back(-1);'];

		switch( $err_code ){
			case '10001' :
				$err_message = "존재하지 않는 페이지 입니다.";
				$err_detmessage = "";
				break;
			case '40001' :
				$err_message = "잘못된 접근입니다.";
				$err_detmessage = "The wrong approach.";
				break;


			case '50001' :
				$err_message = "접근 권한이 없습니다.";
				$err_detmessage = "";
				break;
			case '50002' :
				$err_message = "작성 권한이 없습니다.";
				$err_detmessage = "";
				break;
			case '50003' :
				$err_message = "작성자 본인만 수정이 가능합니다.";
				$err_detmessage = "";
				break;


			case '60001' :
				$err_message = "존재하지 않는 파일입니다.";
				$err_detmessage = "";
				break;
			case '60002' :
				$err_message = "파일 다운로드 권한이 없습니다.";
				$err_detmessage = "";
				break;
			default :
				$err_message = "잘못된 접근입니다.";
				$err_detmessage = "The wrong approach.";
				break;	
		}

		$return = [
			'status'=>$status,
			'err_code'=>$err_code,
			'err_message'=>$err_message,
			'err_detmessage' =>$err_detmessage,
			'err_btn'=>$err_btn				
		];

		return $this->View( 'errors\html\error_general', $return );  
	}

	//--------------------------------------------------------------------

}
