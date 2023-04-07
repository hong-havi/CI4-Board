<?php namespace App\Models\Common;

use CodeIgniter\Model;



/**
 * Class common sitemodel
 *
 * @package App\Models
 */
class SiteModel extends Model
{
	
    public $response_data = [
                'status'    => 1,
                'error'     => 0,
                'messages'  => '',
                'data'      => []
			];
			
  
    public function __construct()
    {        
		helper('html');
    }

	/**
	 *
	 * getParam
	 *
	 * 파라메터 체크 컨트롤러
	 *
	 * @param String or Array	$data		확인할 데이터키 또는 배열
	 * @param string			$format		데이터포맷
	 * @param string			$essential	필수여부
	 * @param string			$default	필수가 아닐시 기본값
	 *
	 */
	public function getParam($field,$value,$format,$essential = false,$default = ""){
        $request = \Config\Services::request();
        
		$Check_Flag = true;
		switch( $format ){
			case 'JSON' :
				$input = file_get_contents('php://input');
				if( !$input ){
                    return $this->fail('Undefind_Data('.$field.')', 400);
				}
				$Check_Data = json_decode($input,true);
				$Check_Data = (isset($Check_Data[$field])) ? $Check_Data[$field] :"";
				if( !$Check_Data ){
                    return $this->fail('Undefind_Data('.$field.')', 400);
				}
				$errcode = 10003;
				break;
			case 'FIELD' :
				$Check_Data = $value;
				if( $essential == true ){
					if( !$Check_Data ){
                        return $this->fail('Undefind_Data('.$field.')', 400);
					}
				}else{
                    if( !$Check_Data ){
                        $Check_Data = $default;
                    }
				}
				break;
			case 'POST' :
                $Check_Data = $request->getPost($field);
				if( $essential == true ){
					if( !$Check_Data ){
                        return $this->fail('Undefind_Data('.$field.')', 400);
					}
				}else{
                    if( !$Check_Data ){
                        $Check_Data = $default;
                    }
				}
				break;
			case 'GET' :
				$Check_Data = $request->getGet($field);
				if( $essential == true ){
					if( !$Check_Data ){
                        return $this->fail('Undefind_Data('.$field.')', 400);
					}
				}else{
                    if( !$Check_Data ){
                        $Check_Data = $default;
                    }
				}
				break;
			case 'FILE' :
				$Check_Data = $request->getFile($field);
				if( $essential == true ){
					if( !$Check_Data ){
						return $this->fail('Undefind_Data('.$field.')', 400);
					}
				}else{
					if( !$Check_Data ){
						$Check_Data = $default;
					}
				}
				break;				
			case 'HEADER' :
				$header = $this->headers;
				$lower_field = strtolower($field);
				if( isset($header[$field]) ){
					$Check_Data = $header[$field];
				}else if( isset($header[$lower_field]) ){
					$Check_Data = $header[$lower_field];
				}else{
					$Check_Data = "";
				}
	
				if( $essential == true ){
					if( !$Check_Data ){
                        return $this->fail('Undefind_Data('.$field.')', 400);
					}
				}else{
                    if( !$Check_Data ){
                        $Check_Data = $default;
                    }
				}
				break;
			case 'SEGMENT' :
				$Check_Data = $this->uri->segment($field, null);
				if( $essential == true ){
					if( !$Check_Data ){
                        return $this->fail('Undefind_Data('.$field.')', 400);
					}
				}else{
                    if( !$Check_Data ){
                        $Check_Data = $default;
                    }
				}
				break;
			case 'SESSION' :
				$session = \Config\Services::session();
				$Check_Data = $session->get($field);
				if( $essential == true ){
					if( !$Check_Data ){
						return $this->fail('Undefind_Data('.$field.')', 400);
					}
				}else{
					if( !$Check_Data ){
						$Check_Data = $default;
					}
				}
				break;
		}
	
		return $Check_Data;
	}


    public function setResStatus( int $status, String $message = '', int $errcode = 0){
        $this->response_data['status'] = $status;
        $this->response_data['errcode'] = $errcode;
        $this->response_data['message'] = $message;
    }

    public function setResData( String $key,$val){
        $this->response_data['data'][$key] = $val;
    }


	public function getReturn( String $ReturnType, String $status, String $message = '', Array $retdata = [] ){

		switch( $ReturnType ){
			case 'AJAX' : 
				$this->setResStatus( $status, $message, 0);
				foreach( $retdata as $k => $v ){
					$this->setResData( $k, $v );
				}

				//$this->respond($this->response_data,200);
				break;
			case 'PAGE' : default : 
				$response = \Config\Services::response(null, true);
				switch( $status ){
					case 'direct_move' :
						return redirect()->to($retdata['url']);
						break;
					case 'move' :
						return redirect($retdata['url']);	
						break;
					case 'reload' :
						return redirect('/','refresh')->to($retdata['url']);	
						break;	
				}
				break;
		}
	}

}