<?php namespace App\Controllers\Accounts;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\I18n\Time;


class Register extends BaseController
{
    public $validation;
    use ResponseTrait;

    public function __construct()
    {
    }

	public function index()
	{	

        if( IP['PLACE'] != 'IN' ){
            return $this->SiteModel->getReturn( $this->request->isAJAX() ,'direct_move','잘못된 접근입니다..',['url'=>'/accounts/login']);
        }
        
		if( isset($_SESSION['site_uno']) ){	
            return $this->SiteModel->getReturn( $this->request->isAJAX() ,'direct_move','잘못된 접근입니다..',['url'=>'/']);
		}

        helper(['form','url']);
        $script_cell = array(
                        ["link"=>"_assets/vendors/js/jquery.validate.min.js",'data'=>[]],
                        ["link"=>"_assets/vendors/js/jquery.maskedinput.min.js",'data'=>[]],
                        ["link"=>"_assets/js/accounts/register.js",'data'=>[]],
                        ["link"=>"_assets/js/lib/postnum-Js.js",'data'=>[]],
                        ["link"=>"_assets/vendors/js/jquery.serialize-object.min.js",'data'=>[]],
                        ["link"=>"https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js?t=200423",'data'=>[]],
                        );
                        
		$cell = [
            'validation' => $this->validation,
            'script_cell' => $script_cell
        ];
		return $this->View('accounts/register',$cell);

	}

	public function setRegister(){
        
        if( IP['PLACE'] != 'IN' ){
            return $this->SiteModel->getReturn( $this->request->isAJAX() ,'direct_move','잘못된 접근입니다..',['url'=>'/accounts/login']);
        }
        
        $time = Time::now();

        $regi_model = new \App\Models\Accounts\Register_model();

        $this->validation =  \Config\Services::validation();

        if( !$this->request->isAJAX() ){
            return $this->fail('잘못된 요청입니다.', 401);
        }


        $this->validation->setRules([
            'uname' => [
                'label' => 'uanme', 'rules' => 'required', 'errors' =>[
                    'required' => '이름을 입력해 주세요.'
                ]
            ],
            'birth' => [
                'label' => 'birth', 'rules' => 'required', 'errors' =>[
                    'required' => '생일을 입력해 주세요.'
                ]
            ],
            'sex' => [
                'label' => 'sex', 'rules' => 'required', 'errors' =>[
                    'required' => '성별을 선택해 주세요.'
                ]
            ],
            'userid' => [
                'label' => 'userid', 'rules' => 'required|min_length[8]|max_length[20]|regex_match[/^[a-z0-9+]*$/]', 'errors' =>[
                    'required' => '아이디를 입력해 주세요.',
                    'min_length' => '아이디는 8~20자의 영문(소문자)과 숫자만 사용할 수 있습니다.',
                    'max_length' => '아이디는 8~20자의 영문(소문자)과 숫자만 사용할 수 있습니다.',
                    'regex_match'=> '아이디는 영문(소문자)과 숫자만 사용할 수 있습니다.',
                ]
            ],
            'upassword' => [
                'label' => 'upassword', 'rules' => 'required|min_length[8]|max_length[20]|regex_match[/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*()_-])[A-Za-z\d!@#$%^&*()_-].{8,20}$/]', 'errors' =>[
                  'required' => '비밀번호를 입력해 주세요.',
                  'min_length' => '비밀번호는 영문,숫자,특수문자(!@#$%^&*()_-)포함 8~20자만 사용할 수 있습니다.',
                  'max_length' => '비밀번호는 영문,숫자,특수문자(!@#$%^&*()_-)포함 8~20자만 사용할 수 있습니다.',
                  'regex_match'=> '비밀번호는 영문,숫자,특수문자(!@#$%^&*()_-)포함 사용할 수 있습니다.',
                ]
            ],
            'upassword_confirm' => [
                'label' => 'upassword_confirm', 'rules' => 'required|matches[upassword]', 'errors' =>[
                    'required' => '비밀번호 확인을 입력해 주세요.',
                    'matches'=>'입력한 비밀번호와 비밀번호 확인이 맞지 않습니다.'
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

        $site = 1;
        $userid = $this->SiteModel->getParam('userid','','POST');
        $upassword = $this->SiteModel->getParam('upassword','','POST');
        
        $uname = $this->SiteModel->getParam('uname','','POST');
        $birth = $this->SiteModel->getParam('birth','','POST');
        $birth_arr = explode("-",$birth);
        $birth1 = $birth_arr[0];
        $birth2 = $birth_arr[1].$birth_arr[2];
        $birthtype = $this->SiteModel->getParam('birthtype','','POST',false,'0');
        $sex = $this->SiteModel->getParam('sex','','POST');
        $email = $this->SiteModel->getParam('email','','POST');
        $tel1 = $this->SiteModel->getParam('tel1','','POST');
        $tel2 = $this->SiteModel->getParam('tel2','','POST');
        $zip = $this->SiteModel->getParam('postnum','','POST');
        $addr1 = $this->SiteModel->getParam('addr1','','POST');
        $addr2 = $this->SiteModel->getParam('addr2','','POST');

        if( $regi_model->checkID($userid) > 0 ){
            return $this->fail("사용할 수 없는 아이디입니다.", 400);
        }

        $uno = $regi_model->insertID([
            'site'  => $site,
            'id'    => $userid,
            'npw'   => ''
        ]);
        
        if( !$uno ){
            return $this->fail("아이디 생성도중 오류가 발생했습니다. 다시 시도해 주시기 바랍니다.", 400);
        }
        
        $regi_model->updatePassword( $upassword, $uno );

        $regi_model->insertData([
            'memberuid' => $uno,
            'site'      => $site,
            'auth'      => 3,
            'sosok'     => 0,
            'level'     => 0,
            'level_det' => 0,
            'email'     => $email,
            'name'      => $uname,
            'nic'       => $uname,
            'sex'       => $sex,
            'birth1'    => $birth1,
            'birth2'    => $birth2,
            'birthtype' => $birthtype,
            'tel1'      => $tel1,
            'tel2'      => $tel2,
            'zip'       => $zip,
            'addr0'     => '',
            'addr1'     => $addr1,
            'addr2'     => $addr2,
            'num_login' => 0,
            'last_pw'   => $time->toLocalizedString('yMd'),
            'is_paper'  => 1,
            'd_regis'   => $time->toLocalizedString('yMdHms'),
            'refresh_YN'=> 'N',
            'tflag'     => 'N',
            'crm_auth_level' => 1
        ]);

        $regi_model->insertDataSub([
            'uno'   => $uno
        ]);
        
		
        $this->SiteModel->setResStatus(1,'회원가입 신청서가 접수되었습니다. 관리자 승인후 이용하실 수 있습니다.');
        return $this->respondCreated($this->SiteModel->response_data,200);
	}
}
