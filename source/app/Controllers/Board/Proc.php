<?php namespace App\Controllers\Board;

use App\Controllers\BaseController;
use App\Models\Board\Board_model;
use App\Models\Site\Permission_model;
use App\Models\Common\Paper_model;
use CodeIgniter\API\ResponseTrait;

class Proc extends BaseController
{
	
    use ResponseTrait;
    
	private $Board_model;
	private $Permission_model;
	private $Board_info = [];

	public function __construct()
	{
		$this->Board_model = new Board_model();
		$this->Permission_model = new Permission_model();
	}

    public function Check(){
		
		if( !isset(BOARD_INFO['uid']) ){			
            return ['state' => false];
		}

		return ['state'=>true];
    }

    public function write(){

        $validation =  \Config\Services::validation();

        if( !$this->request->isAJAX() ){
            return $this->fail('잘못된 요청입니다.', 401);
        }


        $validation->setRules([
            'subject' => [
                'label' => 'subject', 'rules' => 'required', 'errors' =>[
                    'required' => '제목을 입력해 주세요.'
                ]
            ],
            'content' => [
                'label' => 'content', 'rules' => 'required', 'errors' =>[
                    'required' => '내용을 입력해 주세요.'
                ]
            ]
        ]);

        $validation->withRequest($this->request)
        ->run();
        if( count($validation->getErrors()) > 0 ){
            foreach( $validation->getErrors() as $k => $error_message ){
                return $this->fail($error_message, 400);
            }
        }


        $depth = $this->SiteModel->getParam('depth','0','POST',false);
        $parentmbr = $this->SiteModel->getParam('parentmbr','0','POST',false);
        
        $subject = $this->SiteModel->getParam('subject','','POST');
        $content = $this->SiteModel->getParam('content','','POST');
        $html = $this->SiteModel->getParam('html','HTML','POST',false);
        
        $category = $this->SiteModel->getParam('category','','POST',false);
        $category2 = $this->SiteModel->getParam('category2','','POST',false);
        $category3 = $this->SiteModel->getParam('category3','','POST',false);
        $category4 = $this->SiteModel->getParam('category4','','POST',false);
        
        $opt_notice = $this->SiteModel->getParam('opt_notice','0','POST',false);
        $opt_secret = $this->SiteModel->getParam('opt_secret','0','POST',false);
        $opt_teams = $this->SiteModel->getParam('opt_teams','0','POST',false);
        $opt_mail = $this->SiteModel->getParam('opt_mail','0','POST',false);
        $opt_emergency = $this->SiteModel->getParam('opt_emergency','0','POST',false);
        
        $sender_list_1 = $this->SiteModel->getParam('sender_list_1','','POST',false);
        $sender_list_2 = $this->SiteModel->getParam('sender_list_2','','POST',false);
        
        $display = ($opt_secret == 1) ? '0' : '1';
        $pw = ($opt_secret == 1) ? USER_INFO['memberuid'] : '';

        $bbs_upload = $this->SiteModel->getParam('bbs_upload','','POST',false);
        $adddata = $this->SiteModel->getParam('adddata','','POST',false);

        $set_data = [
            'site'          => '1',
            'gid'           => '0',
            'bbs'           => BOARD_INFO['uid'],
            'bbsid'         => BOARD_INFO['id'],
            'depth'         => $depth,
            'parentmbr'     => $parentmbr,
            'display'       => $display,
            'hidden'        => $opt_secret,
            'notice'        => $opt_notice,
            'emergency'     => $opt_emergency,
            'name'          => USER_INFO['name'],
            'nic'           => USER_INFO['nic'],
            'mbruid'        => USER_INFO['memberuid'],
            'id'            => USER_INFO['id'],
            'pw'            => $pw,
            'category'      => $category,
            'category2'     => $category2,
            'category3'     => $category3,
            'category4'     => $category4,
            'subject'       => $subject,
            'content'       => $content,
            'html'          => $html,
            'tag'           => '',
            'hit'           => 0,
            'down'          => 0,
            'comment'       => 0,
            'trackback'     => 0,
            'score1'        => 0,
            'score2'        => 0,
            'singo'         => 0,
            'point1'        => 0,
            'point2'        => 0,
            'point3'        => 0,
            'point4'        => 0,
            'oneline'       => 0,
            'd_regis'       => date("YmdHis",time()),
            'd_modify'      => '',
            'd_delete'      => '',
            'd_comment'     => '',
            'd_trackback'   => '',
            'd_tmp'         => '',
            'upload'        => $bbs_upload,
            'ip'            => $this->request->getServer('REMOTE_ADDR'),
            'agent'         => $this->request->getServer('HTTP_USER_AGENT'),
            'sns'           => '',
            'adddata'       => $adddata
        ];
        $bbsno = $this->Board_model->setInsert($set_data);

        if( !$bbsno ){
            return $this->fail('데이터 처리도중 오류가 발생했습니다.');
        }

        $add_sender_1 = $this->Permission_model->setDatas('bbs',1,$bbsno,$sender_list_1); //수신
        $add_sender_2 = $this->Permission_model->setDatas('bbs',2,$bbsno,$sender_list_2); //참조

        $link = "/site/".MENU_INFO['uid']."/bbs/view/".$bbsno;

        $paper = new Paper_model();
        $memo_1 = $paper->makeTpl( '수신', $subject, MENU_INFO['name']." 게시판에서 글이 작성 되었습니다.", $link);
        $paper->send( USER_INFO['memberuid'], $add_sender_1, $memo_1, 'text' ,$link , $bbsno, 2 );
        $memo_2 = $paper->makeTpl( '참조', $subject, MENU_INFO['name']." 게시판에서 글이 작성 되었습니다.", $link);
        $paper->send( USER_INFO['memberuid'], $add_sender_2, $memo_2, 'text' ,$link , $bbsno, 3 );


    
        $this->SiteModel->setResStatus(1,'');
        $this->SiteModel->setResData('link',$link);
        return $this->respondCreated($this->SiteModel->response_data,200);
    }

    public function modify(){
        
        $validation =  \Config\Services::validation();

        if( !$this->request->isAJAX() ){
            return $this->fail('잘못된 요청입니다.', 401);
        }


        $validation->setRules([
            'bbs_uid' => [
                'label' => 'bbs_uid', 'rules' => 'required', 'errors' =>[
                    'required' => '잘못된 접근입니다.'
                ]
            ],
            'subject' => [
                'label' => 'subject', 'rules' => 'required', 'errors' =>[
                    'required' => '제목을 입력해 주세요.'
                ]
            ],
            'content' => [
                'label' => 'content', 'rules' => 'required', 'errors' =>[
                    'required' => '내용을 입력해 주세요.'
                ]
            ]
        ]);

        $validation->withRequest($this->request)
        ->run();
        if( count($validation->getErrors()) > 0 ){
            foreach( $validation->getErrors() as $k => $error_message ){
                return $this->fail($error_message, 400);
            }
        }


        $bbs_uid = $this->SiteModel->getParam('bbs_uid','0','POST');
        $depth = $this->SiteModel->getParam('depth','0','POST',false);
        $parentmbr = $this->SiteModel->getParam('parentmbr','0','POST',false);
        
        $subject = $this->SiteModel->getParam('subject','','POST');
        $content = $this->SiteModel->getParam('content','','POST');
        $html = $this->SiteModel->getParam('html','HTML','POST',false);
        
        $category = $this->SiteModel->getParam('category','','POST',false);
        $category2 = $this->SiteModel->getParam('category2','','POST',false);
        $category3 = $this->SiteModel->getParam('category3','','POST',false);
        $category4 = $this->SiteModel->getParam('category4','','POST',false);
        
        $opt_notice = $this->SiteModel->getParam('opt_notice','0','POST',false);
        $opt_secret = $this->SiteModel->getParam('opt_secret','0','POST',false);
        $opt_teams = $this->SiteModel->getParam('opt_teams','0','POST',false);
        $opt_mail = $this->SiteModel->getParam('opt_mail','0','POST',false);
        $opt_emergency = $this->SiteModel->getParam('opt_emergency','0','POST',false);
        
        $opt_sender_1 = $this->SiteModel->getParam('opt_sender_1','0','POST',false);
        $opt_sender_2 = $this->SiteModel->getParam('opt_sender_2','0','POST',false);
        
        $sender_list_1 = $this->SiteModel->getParam('sender_list_1','','POST',false);
        $sender_list_2 = $this->SiteModel->getParam('sender_list_2','','POST',false);
        
        $display = ($opt_secret == 1) ? '0' : '1';
        $pw = ($opt_secret == 1) ? USER_INFO['memberuid'] : '';

        $bbs_upload = $this->SiteModel->getParam('bbs_upload','','POST',false);
        $adddata = $this->SiteModel->getParam('adddata','','POST',false);


		$selector = "bd.uid,bd.mbruid";
		$board_data = $this->Board_model->getData( BOARD_INFO['uid'] , $bbs_uid ,$selector);


		if( $board_data['mbruid'] != USER_INFO['memberuid'] ){
            return $this->fail('작성자만 수정이 가능합니다.');
        }
        
        
        $set_data = [
            'site'          => '1',
            'gid'           => '0',
            'bbs'           => BOARD_INFO['uid'],
            'bbsid'         => BOARD_INFO['id'],
            'depth'         => $depth,
            'parentmbr'     => $parentmbr,
            'display'       => $display,
            'hidden'        => $opt_secret,
            'notice'        => $opt_notice,
            'emergency'     => $opt_emergency,
            'pw'            => $pw,
            'category'      => $category,
            'category2'     => $category2,
            'category3'     => $category3,
            'category4'     => $category4,
            'subject'       => $subject,
            'content'       => $content,
            'html'          => $html,
            'tag'           => '',
            'd_modify'      => date("YmdHis",time()),
            'upload'        => $bbs_upload,
            'ip'            => $this->request->getServer('REMOTE_ADDR'),
            'agent'         => $this->request->getServer('HTTP_USER_AGENT'),
            'sns'           => '',
            'adddata'       => $adddata
        ];

        $bbsno = $this->Board_model->setUpdate($bbs_uid,$set_data);

        if( !$bbsno ){
            return $this->fail('데이터 처리도중 오류가 발생했습니다.');
        }

        $add_sender_1 = $this->Permission_model->setDatas('bbs',1,$bbsno,$sender_list_1); //수신
        $add_sender_2 = $this->Permission_model->setDatas('bbs',2,$bbsno,$sender_list_2); //참조
        $link = "/site/".MENU_INFO['uid']."/bbs/view/".$bbsno;

        $rsend_list_1 = ( $opt_sender_1 == '1' ) ? $this->Permission_model->exLists($sender_list_1) : $add_sender_1;
        $rsend_list_2 = ( $opt_sender_2 == '1' ) ? $this->Permission_model->exLists($sender_list_2) : $add_sender_2;

        $paper = new Paper_model();
        $memo_1 = $paper->makeTpl( '수신-수정', $subject, MENU_INFO['name']." 에서 글이 작성 되었습니다.", $link);
        $paper->send( USER_INFO['memberuid'], $rsend_list_1, $memo_1, 'text' ,$link , $bbsno, 2 );
        $memo_2 = $paper->makeTpl( '참조-수정', $subject, MENU_INFO['name']." 에서 글이 작성 되었습니다.", $link);
        $paper->send( USER_INFO['memberuid'], $rsend_list_2, $memo_2, 'text' ,$link , $bbsno, 3 );


    
        $this->SiteModel->setResStatus(1,'');
        $this->SiteModel->setResData('link',$link);
        return $this->respondCreated($this->SiteModel->response_data,200);

    }

    public function delete(){
        $bbs_uid = $this->SiteModel->getParam('buid','','POST');
        
		$board_data = $this->Board_model->getData( BOARD_INFO['uid'] , $bbs_uid );
        if( $board_data['memberuid'] != USER_INFO['memberuid'] ){
            return $this->fail('작성자만 삭제가 가능합니다.');
        }

        $this->Board_model->deleteData($bbs_uid);


        $this->SiteModel->setResStatus(1,'');
        return $this->respondCreated($this->SiteModel->response_data,200);
    }


}

