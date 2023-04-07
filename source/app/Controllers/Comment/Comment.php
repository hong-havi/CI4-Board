<?php namespace App\Controllers\Comment;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Comment\Comment_model;
use App\Models\Site\Permission_model;
use App\Models\Common\Paper_model;
use App\Models\Board\Board_model;
use App\Models\Workspace\Workspace_model;

class Comment extends BaseController
{
	
    use ResponseTrait;
	
	public $Comment_model;

	public function __construct()
	{
		$this->Comment_model = new Comment_model();
	}

    public function init(){
        helper('number');

		$uptype = $this->SiteModel->getParam('uptype','','GET');
		$parent = $this->SiteModel->getParam('parent','','GET');

		$list_datas = $this->Comment_model->getDatas($uptype,$parent);
		$view_datas['comment_lists'] = $list_datas;

        $view_datas['comment_data']['template'] = 'default';
		$view_datas['write_form']['sub_class'] = "cmt";
		$view_datas['write_form']['action'] = '/site/'.MENU_INFO['uid'].'/comment/write';
		$view_datas['write_form']['uptype'] = $uptype;
		$view_datas['write_form']['parent'] = $parent;
		$view_datas['write_form']['depth'] = 1;
		$view_datas['wform_data'] = [
			'upload'	=> "",
			'content'	=> ""
		];	
		
		return $this->view('comment/default/full',$view_datas);
    }

	public function replayForm(){
		
		$uptype = $this->SiteModel->getParam('uptype','','GET');
		$parent = $this->SiteModel->getParam('parent','','GET');
		$depth = $this->SiteModel->getParam('depth','','GET');

        $view_datas['comment_data']['template'] = 'default';
        $view_datas['write_form']['sub_class'] = "cmtc";
		$view_datas['write_form']['action'] = '/site/'.MENU_INFO['uid'].'/comment/reply';
		$view_datas['write_form']['uptype'] = $uptype;
		$view_datas['write_form']['parent'] = $parent;
		$view_datas['write_form']['depth'] = $depth;
		$view_datas['wform_data'] = [
			'upload'	=> "",
			'content'	=> ""
		];;
		return $this->view('comment/default/write',$view_datas);
	}
	
	public function modifyForm(){
		
		$uptype = $this->SiteModel->getParam('uptype','','GET');
		$parent = $this->SiteModel->getParam('parent','','GET');
		$depth = $this->SiteModel->getParam('depth','','GET');

		switch( $depth ){
			case '1' :
				$wform_data = $this->Comment_model->getComment($parent);
				break;
			case '2' :
				$wform_data = $this->Comment_model->getOne($parent);
				break;
		}

        $view_datas['comment_data']['template'] = 'default';
        $view_datas['write_form']['sub_class'] = "cmtc";
		$view_datas['write_form']['action'] = '/site/'.MENU_INFO['uid'].'/comment/modify';
		$view_datas['write_form']['uptype'] = $uptype;
		$view_datas['write_form']['parent'] = $parent;
		$view_datas['write_form']['depth'] = $depth;
		$view_datas['wform_data'] = [
			'upload'	=> (isset($wform_data['upload'])) ? $wform_data['upload'] :"",
			'content'	=> $wform_data['content']
		];;
		return $this->view('comment/default/write',$view_datas);
	}

	public function list(){
        helper('number');
		$uptype = $this->SiteModel->getParam('uptype','','GET');
		$parent = $this->SiteModel->getParam('parent','','GET');


		$list_datas = $this->Comment_model->getDatas($uptype,$parent);
		$view_datas['comment_lists'] = $list_datas;
        $view_datas['comment_data']['template'] = 'default';
		return $this->view('comment/default/list',$view_datas);

	}

	public function write_proc(){
		$Permission_model = new Permission_model();
		
		$uptype = $this->SiteModel->getParam('uptype','','POST',false);
		$parent = $this->SiteModel->getParam('parent','','POST',false);
		
        $cmt_upload = $this->SiteModel->getParam('cmt_upload','','POST',false);
		$cmt_content = $this->SiteModel->getParam('cmt_content','','POST',false);
		
        $cmt_opt_hidden = $this->SiteModel->getParam('cmt_opt_hidden','0','POST',false);
        $display = ($cmt_opt_hidden == 1) ? '0' : '1';
		$pw = ($cmt_opt_hidden == 1) ? USER_INFO['memberuid'] : '';
		
        $cmt_opt_sender_1 = $this->SiteModel->getParam('cmt_opt_sender_1','0','POST',false);
        $cmt_opt_sender_2 = $this->SiteModel->getParam('cmt_opt_sender_2','0','POST',false);
		$cmt_mentions = $this->SiteModel->getParam('cmt_mentions',[],'POST',false);
		$cmt_mentions = ($cmt_mentions) ? $cmt_mentions : [];
		
		if( !$cmt_content ){			
            return $this->fail('내용을 입력해 주세요.');
		}

		switch( $uptype ){
			case 'bbs' :
				$Board_model = new Board_model();
				$parent_info = $Board_model->getData( '' , $parent , "bd.uid,bd.mbruid as uno" );
				break;
			case 'ws' :
				$Workspace_model = new Workspace_model();
				$parent_info = $Workspace_model->getPJData( $parent , "wp.pj_idx as uid,wp.uno as uno" );
				break;
		}

		if( !isset($parent_info['uid']) ){
            return $this->fail('이미 삭제된 글에 작성중입니다.');
		}

		$set_data = [
			'site'			=> '1',
			'parent'		=> $uptype.$parent,
			'parent_type'	=> $uptype,
			'parent_uid'	=> $parent,
			'parentmbr'		=> $parent_info['uno'],
			'display'		=> $display,
			'hidden'		=> $cmt_opt_hidden,
			'notice'		=> '0',
			'name'			=> USER_INFO['name'],
			'nic'			=> USER_INFO['nic'],
			'mbruid'		=> USER_INFO['memberuid'],
			'id'			=> USER_INFO['id'],
			'pw'			=> $pw,
			'subject'		=> strip_tags($cmt_content),
			'content'		=> $cmt_content,
			'html'			=> 'HTML',
			'hit'			=> '0',
			'down'			=> '0',
			'oneline'		=> '0',
			'score1'		=> '0',
			'score2'		=> '0',
			'singo'			=> '0',
			'point'			=> '0',
			'd_regis'		=> date("YmdHis",time()),
			'd_modify'		=> '',
			'd_oneline'		=> '',
			'upload'		=> $cmt_upload,
            'ip'        	=> $this->request->getServer('REMOTE_ADDR'),
            'agent'     	=> $this->request->getServer('HTTP_USER_AGENT'),
			'cync'			=> '',
			'sns'			=> '',
			'adddata'		=> ''
		];
        $cmtsno = $this->Comment_model->setInsert($set_data);

        if( !$cmtsno ){
            return $this->fail('데이터 처리도중 오류가 발생했습니다.');
		}
		

		$parent_info = $this->Comment_model->parent_info($uptype,$parent);

		$sender_lists = ['p'=>[],'g'=>[]];
		$sender_lists['p'][] = $parent_info['muno']; //글 작성자에게 기본 발송
		if( $cmt_opt_sender_1 == '1' || $cmt_opt_sender_2 == '1'){
			$parent_lists = $Permission_model->getList($uptype, $parent);
			if( $cmt_opt_sender_1 == '1' ){
				$sender_lists['p'] = array_merge($sender_lists['p'],$parent_lists['full_uid']['1']['p']);
				$sender_lists['g'] = array_merge($sender_lists['g'],$parent_lists['full_uid']['1']['g']);
			}
			if( $cmt_opt_sender_2 == '1' ){
				$sender_lists['p'] = array_merge($sender_lists['p'],$parent_lists['full_uid']['2']['p']);
				$sender_lists['g'] = array_merge($sender_lists['g'],$parent_lists['full_uid']['2']['g']);
			}
		}

		if( count($cmt_mentions) > 0 ){
			$mention_lists = $Permission_model->exLists(implode(",",$cmt_mentions));
			$sender_lists['p'] = array_merge($sender_lists['p'],$mention_lists['p']);
			$sender_lists['g'] = array_merge($sender_lists['g'],$mention_lists['g']);
		}


		$paper = new Paper_model();
		if( $cmt_opt_hidden =='1'){
			$send_content = "비밀 댓글은 글에서 확인 가능합니다.";
		}else{
			$send_content = $cmt_content;
		}
		
		$parent_info['link'] = $parent_info['link']."#comment_".$cmtsno;
        $paper_memo = $paper->makeTpl( '댓글', $parent_info['subject'], MENU_INFO['name']." 에서 댓글이 작성 되었습니다.\n\n ".$send_content, $parent_info['link']);
       	$paper->send( USER_INFO['memberuid'], $sender_lists, $paper_memo, 'text' ,$parent_info['link'] , $parent, $parent_info['sendtype']);


		$this->Comment_model->setStatus($uptype,$parent);
		
		$this->SiteModel->setResStatus(1,'');
		return $this->respondCreated($this->SiteModel->response_data,200);   
	}
	
	public function reply_proc(){
		$Permission_model = new Permission_model();
		
		$uptype = $this->SiteModel->getParam('uptype','','POST',false);
		$parent = $this->SiteModel->getParam('parent','','POST',false);
		$depth = $this->SiteModel->getParam('depth','','POST',false);
		
		$cmt_content = $this->SiteModel->getParam('cmt_content','','POST',false);
		
        $cmt_opt_hidden = $this->SiteModel->getParam('cmt_opt_hidden','0','POST',false);
		
        $cmt_opt_sender_1 = $this->SiteModel->getParam('cmt_opt_sender_1','0','POST',false);
        $cmt_opt_sender_2 = $this->SiteModel->getParam('cmt_opt_sender_2','0','POST',false);
		$cmt_mentions = $this->SiteModel->getParam('cmt_mentions',[],'POST',false);
		$cmt_mentions = ($cmt_mentions) ? $cmt_mentions : [];
		
		if( !$cmt_content ){			
            return $this->fail('내용을 입력해 주세요.');
		}

		$set_data = [
			'site'		=> '1',
			'parent'	=> $parent,
			'parentmbr'	=> '0',
			'hidden'	=> $cmt_opt_hidden,
			'name'		=> USER_INFO['name'],
			'nic'		=> USER_INFO['nic'],
			'mbruid'	=> USER_INFO['memberuid'],
			'id'		=> USER_INFO['id'],
			'content'	=> $cmt_content,
			'html'		=> 'HTML',
			'singo'		=> '0',
			'point'		=> '0',
			'd_regis'	=> date("YmdHis",time()),
			'd_modify'	=> '',
            'ip'        => $this->request->getServer('REMOTE_ADDR'),
            'agent'     => $this->request->getServer('HTTP_USER_AGENT'),
			'adddata'	=> ''
		];
        $cmtsno = $this->Comment_model->setOneInsert($set_data);

        if( !$cmtsno ){
            return $this->fail('데이터 처리도중 오류가 발생했습니다.');
		}
		
		
		$pcomment_info = $this->Comment_model->getComment($parent);	
		$bbs_uid = str_replace($uptype,'',$pcomment_info['parent']);
		$parent_info = $this->Comment_model->parent_info($uptype,$bbs_uid);

		$sender_lists = ['p'=>[],'g'=>[]];
		
		//댓글 작성자,댓글 내에 있는 모든 작성자에게 알림
		$sender_lists['p'][] = $pcomment_info['mbruid'];
		$sender_lists['p'] = array_merge($sender_lists['p'],$this->Comment_model->getOneUserlist( $parent ));


		if( $cmt_opt_sender_1 == '1' || $cmt_opt_sender_2 == '1'){
			$parent_lists = $Permission_model->getList($uptype, $bbs_uid);
			if( $cmt_opt_sender_1 == '1' ){
				$sender_lists['p'] = array_merge($sender_lists['p'],$parent_lists['full_uid']['1']['p']);
				$sender_lists['g'] = array_merge($sender_lists['g'],$parent_lists['full_uid']['1']['g']);
			}
			if( $cmt_opt_sender_2 == '1' ){
				$sender_lists['p'] = array_merge($sender_lists['p'],$parent_lists['full_uid']['2']['p']);
				$sender_lists['g'] = array_merge($sender_lists['g'],$parent_lists['full_uid']['2']['g']);
			}
		}

		if( count($cmt_mentions) > 0 ){
			$mention_lists = $Permission_model->exLists(implode(",",$cmt_mentions));
			$sender_lists['p'] = array_merge($sender_lists['p'],$mention_lists['p']);
			$sender_lists['g'] = array_merge($sender_lists['g'],$mention_lists['g']);
		}

		$paper = new Paper_model();
		if( $cmt_opt_hidden =='1'){
			$send_content = "비밀 댓글은 글에서 확인 가능합니다.";
		}else{
			$send_content = $cmt_content;
		}
		
		$parent_info['link'] = $parent_info['link']."#comment_".$cmtsno;
        $paper_memo = $paper->makeTpl( '댓글', $parent_info['subject'], MENU_INFO['name']." 에서 댓글이 작성 되었습니다.\n\n ".$send_content, $parent_info['link']);
       	$paper->send( USER_INFO['memberuid'], $sender_lists, $paper_memo, 'text' ,$parent_info['link'] , $parent, 5);


		$this->Comment_model->setStatusOne($uptype,$bbs_uid);
		
		$this->SiteModel->setResStatus(1,'');
		return $this->respondCreated($this->SiteModel->response_data,200);   
	}


	public function delete_proc(){
		
		$uptype = $this->SiteModel->getParam('uptype','','POST');
		$cuid = $this->SiteModel->getParam('cuid','','POST');
		$depth = $this->SiteModel->getParam('depth','','POST');

		switch( $depth ){
			case '1' : //comment
				$cdata = $this->Comment_model->getComment( $cuid );
				if( !isset($cdata['uid']) ){
					return $this->fail('이미 삭제된 댓글 입니다.');		
				}
				if( $cdata['mbruid']  != USER_INFO['memberuid'] ){
					return $this->fail('작성자만 삭제가 가능합니다.');					
				} 
				
				$onedatas = $this->Comment_model->getOneDatas( $cuid );
				if( count($onedatas) > 0 ){
					return $this->fail('대댓글이 있는 댓글은 삭제할 수 없습니다.');					
				}
				
				$this->Comment_model->deleteComment($cuid);
				$bbs_uid = str_replace($uptype,'',$cdata['parent']);
				$this->Comment_model->setStatus($uptype,$bbs_uid,'-1');
				break;
			case '2' : default :  //oneline
				$odata = $this->Comment_model->getOne( $cuid );
				if( !isset($odata['uid']) ){
					return $this->fail('이미 삭제된 대댓글 입니다.');		
				}
				if( $odata['mbruid']  != USER_INFO['memberuid'] ){
					return $this->fail('작성자만 삭제가 가능합니다.');					
				} 

				$pcomment_info = $this->Comment_model->getComment($odata['parent']);	
				$bbs_uid = str_replace($uptype,'',$pcomment_info['parent']);

				$this->Comment_model->deleteOneline($cuid);				
				$this->Comment_model->setStatusOne( $uptype,$bbs_uid ,'-1');

				break;
		}

		
		$this->SiteModel->setResStatus(1,'');
		return $this->respondCreated($this->SiteModel->response_data,200);   
	}

	public function modify_proc(){
		$Permission_model = new Permission_model();
		
		$depth = $this->SiteModel->getParam('depth','','POST',false);
		$uptype = $this->SiteModel->getParam('uptype','','POST');		
		$uid = $this->SiteModel->getParam('parent','','POST');		
        $cmt_upload = $this->SiteModel->getParam('cmt_upload','','POST',false);
		$cmt_content = $this->SiteModel->getParam('cmt_content','','POST',false);
		$cmt_opt_hidden = $this->SiteModel->getParam('cmt_opt_hidden','0','POST',false);
        $display = ($cmt_opt_hidden == 1) ? '0' : '1';
		$pw = ($cmt_opt_hidden == 1) ? USER_INFO['memberuid'] : '';
		
		$cmt_opt_sender_1 = $this->SiteModel->getParam('cmt_opt_sender_1','','POST',false);
		$cmt_opt_sender_2 = $this->SiteModel->getParam('cmt_opt_sender_2','','POST',false);
		$cmt_mentions = $this->SiteModel->getParam('cmt_mentions',[],'POST',false);
		$cmt_mentions = ($cmt_mentions) ? $cmt_mentions : [];

		if( !$cmt_content ){			
            return $this->fail('내용을 입력해 주세요.');
		}


		$sender_lists = ['p'=>[],'g'=>[]];

		switch( $depth ){
			case '1' : //comment
				$cdata = $this->Comment_model->getComment( $uid );
				if( !isset($cdata['uid']) ){
					return $this->fail('이미 삭제된 댓글 입니다.');		
				}
				if( $cdata['mbruid']  != USER_INFO['memberuid'] ){
					return $this->fail('작성자만 수정이 가능합니다.');					
				}
				
				$setdatas = [
					'display'	=> $display,
					'hidden'	=> $cmt_opt_hidden,
					'upload'	=> $cmt_upload,
					'content'	=> $cmt_content,
					'd_modify'	=> date("YmdHis")
				];
				$this->Comment_model->modifyComment($setdatas,$uid);
				$bbs_uid = str_replace($uptype,'',$cdata['parent']);
						
				$parent_info = $this->Comment_model->parent_info($uptype,$bbs_uid);
				$sender_lists['p'][] = $parent_info['muno']; //글 작성자에게 기본 발송
				if( $cmt_opt_sender_1 == '1' || $cmt_opt_sender_2 == '1'){
					$parent_lists = $Permission_model->getList($uptype, $bbs_uid);
					if( $cmt_opt_sender_1 == '1' ){
						$sender_lists['p'] = array_merge($sender_lists['p'],$parent_lists['full_uid']['1']['p']);
						$sender_lists['g'] = array_merge($sender_lists['g'],$parent_lists['full_uid']['1']['g']);
					}
					if( $cmt_opt_sender_2 == '1' ){
						$sender_lists['p'] = array_merge($sender_lists['p'],$parent_lists['full_uid']['2']['p']);
						$sender_lists['g'] = array_merge($sender_lists['g'],$parent_lists['full_uid']['2']['g']);
					}
				}

				if( count($cmt_mentions) > 0 ){
					$mention_lists = $Permission_model->exLists(implode(",",$cmt_mentions));
					$sender_lists['p'] = array_merge($sender_lists['p'],$mention_lists['p']);
					$sender_lists['g'] = array_merge($sender_lists['g'],$mention_lists['g']);
				}

				$paper_type = 4;
				$paper_name ="댓글-수정";

				break;
			case '2' : default :  //oneline
				$odata = $this->Comment_model->getOne( $uid );
				if( !isset($odata['uid']) ){
					return $this->fail('이미 삭제된 대댓글 입니다.');		
				}
				if( $odata['mbruid']  != USER_INFO['memberuid'] ){
					return $this->fail('작성자만 삭제가 가능합니다.');					
				} 

				$pcomment_info = $this->Comment_model->getComment($odata['parent']);	
				$bbs_uid = str_replace($uptype,'',$pcomment_info['parent']);
				$parent_info = $this->Comment_model->parent_info($uptype,$bbs_uid);

				$setdatas = [
					'content'	=> $cmt_content,
					'd_modify'	=> date("YmdHis")
				];
				$this->Comment_model->modifyOneline($setdatas,$uid);			

				//댓글 작성자,댓글 내에 있는 모든 작성자에게 알림
				$sender_lists['p'][] = $pcomment_info['mbruid'];
				$sender_lists['p'] = array_merge($sender_lists['p'],$this->Comment_model->getOneUserlist( $uid ));


				if( $cmt_opt_sender_1 == '1' || $cmt_opt_sender_2 == '1'){
					$parent_lists = $Permission_model->getList($uptype, $bbs_uid);
					if( $cmt_opt_sender_1 == '1' ){
						$sender_lists['p'] = array_merge($sender_lists['p'],$parent_lists['full_uid']['1']['p']);
						$sender_lists['g'] = array_merge($sender_lists['g'],$parent_lists['full_uid']['1']['g']);
					}
					if( $cmt_opt_sender_2 == '1' ){
						$sender_lists['p'] = array_merge($sender_lists['p'],$parent_lists['full_uid']['2']['p']);
						$sender_lists['g'] = array_merge($sender_lists['g'],$parent_lists['full_uid']['2']['g']);
					}
				}

				if( count($cmt_mentions) > 0 ){
					$mention_lists = $Permission_model->exLists(implode(",",$cmt_mentions));
					$sender_lists['p'] = array_merge($sender_lists['p'],$mention_lists['p']);
					$sender_lists['g'] = array_merge($sender_lists['g'],$mention_lists['g']);
				}

				$paper_type = 5;
				$paper_name ="대댓글-수정";
				break;
		}

		
		$paper = new Paper_model();
		if( $cmt_opt_hidden =='1'){
			$send_content = "비밀 댓글은 글에서 확인 가능합니다.";
		}else{
			$send_content = $cmt_content;
		}
		$parent_info['link'] = $parent_info['link']."#comment_".$uid;
        $paper_memo = $paper->makeTpl($paper_name, $parent_info['subject'], MENU_INFO['name']." 에서 댓글이 작성 되었습니다.\n\n ".$send_content, $parent_info['link']);
		$paper->send( USER_INFO['memberuid'], $sender_lists, $paper_memo, 'text' ,$parent_info['link'] , $bbs_uid, $paper_type);
		   

		$this->SiteModel->setResStatus(1,'');
		return $this->respondCreated($this->SiteModel->response_data,200);   
	}
}

