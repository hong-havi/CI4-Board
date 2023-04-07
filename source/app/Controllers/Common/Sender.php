<?php namespace App\Controllers\Common;

use App\Controllers\BaseController;
use App\Models\Accounts\Group_model;
use CodeIgniter\API\ResponseTrait;


class Sender extends BaseController
{
    use ResponseTrait;
	public function findform()
	{	
		$group_model = new Group_model();
		$group_datas = $group_model->getGrouplist( 1, 0, ['userflag'=>true] );
		$group_tree_tpl = $this->setTreeTpl($group_datas);
		$view_datas = ['group_tree'=>$group_tree_tpl];
		return $this->view('Common/sender_modal',$view_datas);
	}

	public function setTreeTpl( Array $group_datas ){
		$tpl = "";
		$tpl .= "<ul class=\"smd-tree-data\">";
		foreach( $group_datas as $group_data ){
				
            $tpl .= "<li class=\"smd-tree-data\">";
			$tpl .= "	<label class=\"group-link\"><input type=\"checkbox\" name=\"smd-target\" value=\"g_".$group_data['uid']."|".$group_data['name']."\" sec-data=\"".$group_data['name']."\" onclick=\"Sender.checked(this);\"/> ".$group_data['name']."</label>";
			$tpl .= "	<a href=\"javascript:;\" onclick=\"Sender.group_act($(this));\"><i class=\"sjwi-minus group-icon\"></i></a>";
			if( count($group_data['user_list']) > 0 ){
				$tpl .= $this->setTreeTplPeople($group_data['user_list']);
			}		
			if( count($group_data['childn_list']) > 0 ){
				$tpl .= $this->setTreeTpl($group_data['childn_list']);
			}
			$tpl .= "</li>";

		}
		$tpl .= "</ul>";
		

		return $tpl;
	}

	public function setTreeTplPeople( Array $user_lists ){
		$tpl = "<ul class=\"smd-tree-data\">";
		foreach( $user_lists as $user_data ){
			$tpl .= "<li class=\"smd-tree-data\">";
			$tpl .= "	<label class=\"group-link\"><input type=\"checkbox\" name=\"smd-target\" value=\"p_".$user_data['memberuid']."|".$user_data['name']." ".$user_data['lname']."\" sec-data=\"".$user_data['name']."\" onclick=\"Sender.checked(this);\"/> ".$user_data['name']." ".$user_data['lname']."".(($user_data['job_det']) ? " (".$user_data['job_det'].")" : "")."</label>";
			$tpl .= "</li>";
		}
		$tpl .= "</ul>";

		return $tpl;
	}

	public function list(){
		
		$cate = $this->SiteModel->getParam('cate','','GET',false);
		$bbs_uid = $this->SiteModel->getParam('bbs_uid','','GET',false);

		$permission = new \App\Models\Site\Permission_model();

		$lists = $permission->getList($cate,$bbs_uid );

		$view_datas = ['sender_lists'=>$lists];
		return $this->view('Common/senderlist_modal',$view_datas);

	}

	public function add(){
		$permission = new \App\Models\Site\Permission_model();
		$menu = new \App\Models\Site\Menu_model();
		


        $cate = $this->SiteModel->getParam('cate','','POST');
		$bbs_uid = $this->SiteModel->getParam('bbs_uid','','POST');
		$muid = $this->SiteModel->getParam('muid','','POST');
		
        $sender_list_1 = $this->SiteModel->getParam('sender_list_1','','POST',false);
		$sender_list_2 = $this->SiteModel->getParam('sender_list_2','','POST',false);
		

		
        $add_sender_1 = $permission->setDatas($cate,1,$bbs_uid,$sender_list_1); //수신
		$add_sender_2 = $permission->setDatas($cate,2,$bbs_uid,$sender_list_2); //참조
		
		
		$link = "/site/".$muid."/bbs/view/".$bbs_uid;
		
		$menu_info = $menu->getMenuInfo($muid);
		
		switch( $cate ){
			case 'bbs' :
				$board = new \App\Models\Board\Board_model();
				$board_info = $board->getData( '' , $bbs_uid, "subject" );
				$subject = $board_info['subject'];			
				break;
			case 'default' :
				$subject = "";
				break;
		}

        $paper = new \App\Models\Common\Paper_model();
        $memo_1 = $paper->makeTpl( '수신', $subject, $menu_info['name']." 게시판에서 글에 수신 추가되었습니다.", $link);
        $paper->send( USER_INFO['memberuid'], $add_sender_1, $memo_1, 'text' ,$link , $bbs_uid, 2 );
        $memo_2 = $paper->makeTpl( '참조', $subject, $menu_info['name']." 게시판에서 글에 참조 추가되었습니다.", $link);
        $paper->send( USER_INFO['memberuid'], $add_sender_2, $memo_2, 'text' ,$link , $bbs_uid, 3 );

        $this->SiteModel->setResStatus(1,'');
        return $this->respondCreated($this->SiteModel->response_data,200);
	}
}
