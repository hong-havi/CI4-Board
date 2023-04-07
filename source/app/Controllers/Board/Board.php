<?php namespace App\Controllers\Board;

use App\Controllers\BaseController;
use App\Models\Accounts\Group_model;
use App\Models\Board\Board_model;
use App\Models\Accounts\User_model;
use App\Models\Site\Permission_model;
use CodeIgniter\API\ResponseTrait;

class Board extends BaseController
{
	
	private $view_datas = [
		'bbs_template'=>'default',
		'view_mode'=>'list',
		'Breadcrumb'=>'공지사항',
		'Container'=> ['class'=>'container'],
		'css_cell' => [
			'_assets/css/views/board/board.css'
		]
	];

    use ResponseTrait;
    
	private $Board_model;
	private $Permission_model;
	private $Board_info = [];

	public function __construct()
	{
		$this->Board_model = new Board_model();
		$this->Permission_model = new Permission_model();

		$this->view_datas = [
			'bbs_template'=> BOARD_INFO['skin'],
			'view_mode'=>'list',
			'Breadcrumb'=>MENU_INFO['name'],
			'Container'=> ['class'=>'container'],
			'css_cell' => [
				['link'=>'_assets/css/views/board/'.BOARD_INFO['skin'].'/board.css']
			],
			'script_cell' => [
				['link'=>'_assets/js/views/board/board.js']
			]
		];

	}
	public function index()
	{

    }

    public function Check(){
		
		if( !isset(BOARD_INFO['uid']) ){			
            return ['state' => false];
		}

		return ['state'=>true];
    }
    
	public function blist()
	{   
		$this->cachePage(PAGE_CACHE_TIME);

		$pager = \Config\Services::pager();

		$check = $this->Check();
		if( $check['state'] == false ){
			return $this->SiteModel->getReturn( $this->request->isAJAX() ,'direct_move','잘못된 접근입니다..',['url'=>'/errors/404/40001']);
		}

		$page = $this->SiteModel->getParam('page_','','GET',false,'1');
		$pagesize = BOARD_INFO['recnum'];
		$board_where = [];

		
		$sec_key = $this->SiteModel->getParam('sec_key','','GET',false,'');
		$sec_val = $this->SiteModel->getParam('sec_val','','GET',false,'');

		if( $sec_key ){
			switch( $sec_key ){
				case 'subject' :
					$board_where[] = "bd.subject like '%".esc($sec_val)."%' ";
					break;
				case 'content' :
					$board_where[] = "bd.content like '%".esc($sec_val)."%' ";
					break;
				case 'sub_con':
					$board_where[] = "bd.subject like '%".esc($sec_val)."%' or bd.content like '%".esc($sec_val)."%'";
					break;
				case 'name' :
					$board_where[] = "bd.name like '%".esc($sec_val)."%' ";
					break;
			}

		}


		$selector = "bd.*,SUBSTR(m.tel1,-4) AS intel,	
					(SELECT COUNT(uid) FROM ".DB_T_bbs_scrap." WHERE mbruid = '".USER_INFO['memberuid']."' AND bd.uid = buid) AS favorit,
					IF( hidden = 1 , 
						IF( 
							((SELECT COUNT(idx) FROM ".DB_T_bbs_hidshow." WHERE bd.uid = bbs_uid AND cate = 'bbs' AND mtype = 'p' AND muid = '".USER_INFO['memberuid']."') > 0 
							OR (SELECT COUNT(idx) FROM ".DB_T_bbs_hidshow." WHERE bd.uid = bbs_uid AND cate = 'bbs' AND mtype = 'g' AND muid in (".implode(",",USER_INFO['pergroups']).")) > 0 
							OR bd.mbruid = '".USER_INFO['memberuid']."')
							, 
							1 ,
							0
						) 
						, 0
					) AS view_per
		";
		$datas = $this->Board_model->getDatas( BOARD_INFO['uid'] , $page , $pagesize,$selector , $board_where );
		$total = $this->Board_model->getDataCount( BOARD_INFO['uid'] , $board_where);
		
		$notice_where = [];
		$notice_where[] = 'notice = 1';
		$notice_datas = $this->Board_model->getDatas( BOARD_INFO['uid'] , $page , $pagesize,$selector , $notice_where );

		$article_num = $total - ( $page-1 ) * $pagesize;

		$pagers = $pager->makeLinks( $page, $pagesize, $total ,'default_full',0,'');

		$this->view_datas['bbs'] = [
			'datas'=>$datas,'notice_datas'=>$notice_datas,'total'=>$total,'pager'=>$pagers,'page'=>$page,'article_num'=>$article_num
		];
		$this->view_datas['search'] = [
			'sec_val' =>$sec_val,'sec_key'=>$sec_key
		];

		$this->view_datas['view_mode'] = 'list';
		$this->view_datas['script_cell'] = [
			['link'=>'_assets/js/common/Favorit.js'],
		];
		return $this->View( 'board/board' , $this->view_datas );  
	}

    
	public function bwrite()
	{
		if( MENU_PERMISSION['write'] != '1' ){
            return $this->SiteModel->getReturn( $this->request->isAJAX() ,'direct_move','접근이불가능합니다.',['url'=>'/errors/500/50002']);
		}

		$bdata = [
			'uid'			=> '',
			'subject'		=> '',
			'category'		=> [
								'category' => '',
								'category2' => '',
								'category3' => '',
								'category4' => '',
							],
			'sender_lists'	=> ['1'=>[],'2'=>[]],
			'opt' 			=> [
								'notice' => 0,
								'hidden' => 0
							],
			'upload'		=> '',
			'content'		=> ''			
		];


		$this->view_datas['view_mode'] = 'write';
		$this->view_datas['mode'] = 'write';
		$this->view_datas['script_cell'] = [
			['link'=>'_assets/vendors/ckeditor5/build/ckeditor.js'],
			['link'=>'_assets/js/common/Editor.js'],
			['link'=>'_assets/js/common/Sender.js'],
			['link'=>'_assets/js/common/Attach.js'],			
			["link"=>"_assets/vendors/js/jquery.serialize-object.min.js",'data'=>[]],
			['link'=>'_assets/js/views/board/write.js']			
		];

		

		$this->view_datas['board_info'] = BOARD_INFO;
		$this->view_datas['board_data'] = $bdata;
		$this->view_datas['action_url'] = '/site/'.MENU_INFO['uid'].'/bbs/proc/write';
		return $this->View( 'board/board' , $this->view_datas ); 
	}

	
	public function bmodify( Int $bbs_uid )
	{
		if( MENU_PERMISSION['write'] != '1' ){
            return $this->SiteModel->getReturn( $this->request->isAJAX() ,'direct_move','접근이불가능합니다.',['url'=>'/errors/500/50002']);
		}


		$selector = "bd.*,SUBSTR(m.tel1,-4) AS intel,	(SELECT COUNT(uid) FROM ".DB_T_bbs_scrap." WHERE mbruid = '".USER_INFO['memberuid']."' AND bd.uid = buid) AS favorit";

		$board_data = $this->Board_model->getData( BOARD_INFO['uid'] , $bbs_uid ,$selector);


		if( $board_data['mbruid'] != USER_INFO['memberuid'] ){
            return $this->SiteModel->getReturn( $this->request->isAJAX() ,'direct_move','접근이불가능합니다.',['url'=>'/errors/500/50003']);	
		}

		$sender_lists = $this->Permission_model->getList( 'bbs', $bbs_uid );

		$bdata = [
			'uid'			=> $board_data['uid'],
			'subject'		=> $board_data['subject'],
			'category'		=> [
								'category' => $board_data['category'],
								'category2' => $board_data['category2'],
								'category3' => $board_data['category3'],
								'category4' => $board_data['category4'],
							],
			'sender_lists'	=> $sender_lists,
			'opt' 			=> [
								'notice' => $board_data['notice'],
								'hidden' => $board_data['hidden']
							],
			'upload'		=> $board_data['upload'],
			'content'		=> $board_data['content']
		];

		$this->view_datas['view_mode'] = 'write';
		$this->view_datas['mode'] = 'modify';
		$this->view_datas['script_cell'] = [
			['link'=>'_assets/vendors/ckeditor5/build/ckeditor.js'],
			['link'=>'_assets/js/common/Editor.js'],
			['link'=>'_assets/js/common/Sender.js'],
			['link'=>'_assets/js/common/Attach.js'],
			["link"=>"_assets/vendors/js/jquery.serialize-object.min.js",'data'=>[]],
			['link'=>'_assets/js/views/board/write.js']
		];

		

		$this->view_datas['board_info'] = BOARD_INFO;
		$this->view_datas['board_data'] = $bdata;
		$this->view_datas['action_url'] = '/site/'.MENU_INFO['uid'].'/bbs/proc/modify';
		return $this->View( 'board/board' , $this->view_datas ); 
	}
    
	public function bview( Int $bbs_uid )
	{
		$user = new User_model();

		$selector = "bd.*,SUBSTR(m.tel1,-4) AS intel,	(SELECT COUNT(uid) FROM ".DB_T_bbs_scrap." WHERE mbruid = '".USER_INFO['memberuid']."' AND bd.uid = buid) AS favorit";
		$board_data = $this->Board_model->getData( BOARD_INFO['uid'] , $bbs_uid ,$selector);

		if( !isset($board_data) ){
			return $this->SiteModel->getReturn( $this->request->isAJAX() ,'direct_move','잘못된 접근입니다..',['url'=>'/errors/404/40001'] );
		}

		//비밀글 접근제한
		if( $board_data['hidden'] == '1' ){
			$hidden_check = $this->Permission_model->getDetail( 'bbs' , USER_INFO['memberuid'] , $bbs_uid );
			if( !isset($hidden_check['idx']) && $board_data['mbruid'] != USER_INFO['memberuid'] && MENU_PERMISSION['manager'] != '1'){
				return $this->SiteModel->getReturn( $this->request->isAJAX() ,'direct_move','접근이불가능합니다.',['url'=>'/errors/500/50001']);
			}
		}
		
		$user_data = $user->getUserInfoDetail($board_data['mbruid'],"m.*,g.name as gname ,l.name as lname,gp.name as gpname");


		$sender_lists = $this->Permission_model->getList( 'bbs', $bbs_uid );


		$this->Permission_model->setLog('bbs',$bbs_uid);
		$this->Board_model->viewLog( $bbs_uid );

		$comment_data['template'] = 'default';

		$this->view_datas['view_mode'] = 'view';
		$this->view_datas['board_data'] = $board_data;
		$this->view_datas['user_data'] = $user_data;
		$this->view_datas['sender_lists'] = $sender_lists;
		$this->view_datas['comment_data'] = $comment_data;
		$this->view_datas['script_cell'] = [
			["link"=>"_assets/vendors/js/jquery.serialize-object.min.js",'data'=>[]],
			['link'=>'_assets/vendors/ckeditor5/build/ckeditor.js'],
			['link'=>'_assets/js/common/Editor.js'],
			['link'=>'_assets/js/views/comment/Comment.js'],
			['link'=>'_assets/js/views/board/view.js'],
			['link'=>'_assets/js/common/Attach.js'],			
			['link'=>'_assets/js/common/Favorit.js'],
			['link'=>'_assets/js/common/Sender.js'],
		];
		return $this->View( 'board/board' , $this->view_datas );  

	}


	//--------------------------------------------------------------------

}

