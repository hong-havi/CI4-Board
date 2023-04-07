<?php namespace App\Controllers\Accounts;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;


class Login extends BaseController
{
	use ResponseTrait;
	
	public function __construct()
	{
		
	}

	public function index()
	{	
		if( isset($_SESSION['site_uno']) ){	
            return $this->SiteModel->getReturn( $this->request->isAJAX() ,'direct_move','잘못된 접근입니다..',['url'=>'/']);
		}

		$script_cell = array(
							["link"=>"_assets/js/accounts/login.js",'data'=>[]]
						);
		$css_cell = [
						['link'=>"_assets/css/views/accounts/login.css"]
					];
		$cell = ['script_cell'=>$script_cell,'css_cell'=>$css_cell,'PLACE'=>IP['PLACE']];
		

		return $this->View('accounts/login',$cell);

	}

	public function procLogin(){	
		
		$Login_model = new \App\Models\Accounts\Login_model();
		$User_model = new \App\Models\Accounts\User_model();
		$Password_model = new \App\Models\Accounts\Password_model();

		$this->validation =  \Config\Services::validation();


        if( !$this->request->isAJAX() ){
            return $this->fail('잘못된 요청입니다.', 401);
		}
		
        $this->validation->setRules([
            'acc_id' => [
                'label' => 'acc_id', 'rules' => 'required', 'errors' =>[
                    'required' => '아이디를 입력해 주세요.'
                ]
            ],
            'acc_pw' => [
                'label' => 'acc_pw', 'rules' => 'required', 'errors' =>[
                    'required' => '비밀번호를 입력해 주세요.'
                ]
            ],
		]);
		

        $this->validation->withRequest($this->request)
        ->run();
        if( count($this->validation->getErrors()) > 0 ){
            foreach( $this->validation->getErrors() as $k => $error_message ){
                return $this->fail($error_message, 400);
            }
		}
		
        $acc_id = $this->SiteModel->getParam('acc_id','','POST');
        $acc_pw = $this->SiteModel->getParam('acc_pw','','POST');


		if( IP['PLACE'] != 'IN' ){			
			$acc_cnumber = $this->SiteModel->getParam('acc_cnumber','','POST');
			if( !$acc_cnumber ){				
				return $this->fail('인증번호를 입력해 주세요.');
			}
		}

		//user id info
		$useridinfo = $User_model->getUserID('id',$acc_id);

		if( !isset($useridinfo->uid) ){
			$Login_model->insertLog( $acc_id ,0 , 'login' , 2 , $this->nowtime );	
			return $this->fail('아이디 또는 비밀번호가 잘못되었습니다.');
		}
		

		$uno = $useridinfo->uid;

		//user info 
		$userinfo = $User_model->getUserInfo($uno);

		if( $userinfo['tflag'] == 'Y' ){
			// 테스트 계정 접속 보안 관련 처리
			if( !in_array($this->request->getServer('REMOTE_ADDR'),DEV_IP_ARR) ){
				return $this->fail('아이디 또는 비밀번호가 잘못되었습니다.');
			}
		}

		if( $userinfo['pwfail_cnt'] >= 5 ){
			$Login_model->insertLog( $acc_id ,$uno  , 'login' , 4 , $this->nowtime );	
			return $this->fail('로그인 횟수 5번 틀리셔서 로그인 제한이 되셨습니다. \n개발팀으로 문의주시기 바랍니다. \n(개발팀 홍성현 (내선 : 8264 또는 이메일 : hsw7336@siwonschool.com))');
		}

		$checkPw = $Password_model->getPassword( $acc_pw , $uno);
		if( $checkPw != $useridinfo->npw ){
			$Password_model->setPwfail_cnt($uno);
			$Login_model->insertLog( $acc_id ,$uno  , 'login' , 3 , $this->nowtime );	
			return $this->fail('아이디 또는 비밀번호가 잘못되었습니다.');
		}

		switch( $userinfo['auth'] ){
			case '1' : break;
			case '3' : 
				return $this->fail('인증 대기 상태입니다. 인사팀에 문의해 주시기 바랍니다.'); 
				break;
			case '2' : case '4' :
				$Login_model->insertLog( $acc_id ,$uno , 'login' , 5 , $this->nowtime );	
				return $this->fail('아이디 또는 비밀번호가 잘못되었습니다.');
				break;
			default :
				$Login_model->insertLog( $acc_id ,$uno , 'login' , 5 , $this->nowtime );	
				return $this->fail('아이디 또는 비밀번호가 잘못되었습니다.');
				break;
		}
		

		if( $useridinfo->id_flag == '1' ){
			// @todo 임시비밀번호 관련 프로세스 추가
		}
		
		// @todo : sms인증번호
		if( IP['PLACE'] != 'IN' ){
			
			$this->validation->setRules([
				'acc_cnumber' => [
					'label' => 'acc_cnumber', 'rules' => 'required', 'errors' =>[
						'required' => '인증번호를 입력해 주세요.'
					]
				]
			]);
			
	
			$this->validation->withRequest($this->request)
			->run();
			if( count($this->validation->getErrors()) > 0 ){
				foreach( $this->validation->getErrors() as $k => $error_message ){
					return $this->fail($error_message, 400);
				}
			}

			
			$acc_cnumber = $this->SiteModel->getParam('acc_cnumber','','POST');

			$accNum_check = $Login_model->checkCnumber($uno,$acc_cnumber);
			if( $accNum_check !== true ){
				$Login_model->insertLog( $acc_id ,$uno , 'login' , 6 , $this->nowtime );	
				return $this->fail('인증번호가 잘못되었습니다.');
			}

		}



		## Login Success ##
		$Password_model->setFailreset($uno);

		$Login_model->insertLog( $acc_id ,$uno , 'login' , 1 , $this->nowtime );	
		$Login_model->setSession( $uno );

		$Menu = new \App\Models\Site\Menu_model();		
		$Menu -> setMenu( $userinfo['memberuid'],$userinfo['site'], $userinfo['admin'], 1, 0 );
		
        $this->SiteModel->setResStatus(1,'');
        return $this->respondCreated($this->SiteModel->response_data,200);
	}

	public function procSendnumber(){
		$Login_model = new \App\Models\Accounts\Login_model();
		$User_model = new \App\Models\Accounts\User_model();
		$Send = new \App\Models\Common\Send_model();

        $this->validation =  \Config\Services::validation();


        if( !$this->request->isAJAX() ){
            return $this->fail('잘못된 요청입니다.', 401);
		}
		
        $this->validation->setRules([
            'acc_id' => [
                'label' => 'acc_id', 'rules' => 'required', 'errors' =>[
                    'required' => '아이디를 입력해 주세요.'
                ]
            ],
            'acc_pw' => [
                'label' => 'acc_pw', 'rules' => 'required', 'errors' =>[
                    'required' => '비밀번호를 입력해 주세요.'
                ]
            ],
		]);
		
        $this->validation->withRequest($this->request)
        ->run();
        if( count($this->validation->getErrors()) > 0 ){
            foreach( $this->validation->getErrors() as $k => $error_message ){
                return $this->fail($error_message, 400);
            }
        }
		
        $acc_id = $this->SiteModel->getParam('acc_id','','POST');

		//user id info
		$useridinfo = $User_model->getUserID('id',$acc_id);

		if( !isset($useridinfo->uid) ){
			$Login_model->insertLog( $acc_id ,0 , 'login' , 2 , $this->nowtime );	
			return $this->fail('아이디 또는 비밀번호가 잘못되었습니다.');
		}

		$userinfo = $User_model->getUserInfo($useridinfo->uid);

		if( !isset($userinfo['memberuid']) ){
			$Login_model->insertLog( $acc_id , $useridinfo['uid'] , 'login' , 2 , $this->nowtime );	
			return $this->fail('아이디 또는 비밀번호가 잘못되었습니다.');
		}

		if( $userinfo['tel2'] ){ 
			$auth_number = $Login_model->setCnumber($userinfo['memberuid']);
			

			$content_sms = "인증 코드는 [".$auth_number."] 입니다.";
			$send_data = ['content'=>$content_sms];
			$Send->Send('intra','sms',$userinfo['tel2'],1,$send_data);

			$this->SiteModel->setResStatus(1,'');
			return $this->respondCreated($this->SiteModel->response_data,200);
		}

		
		return $this->fail('아이디 또는 비밀번호가 잘못되었습니다.');
		
	}

	public function procLogout(){
		$Login_model = new \App\Models\Accounts\Login_model();
		$Login_model->setLogout();
		return $this->SiteModel->getReturn( 'PAGE','direct_move','',['url'=>'/accounts/login']);
	}
}
