<?php namespace App\Models\Common;

use CodeIgniter\Model;
use App\Models\Board\Board_model;
use App\Models\Common\Attach\Info_model;
use App\Models\Workspace\Worker_model;
use App\Models\Workspace\Workspace_model;
use App\Models\Search\Search_model;

/**
 * Class common sitemodel
 *
 * @package App\Models
 */
class Main_model extends Model
{

    private $uno;

    public function __construct()
    {        
    }

    public function getMainData( $uno ){

        $this->uno = $uno;
        
		$main_datas = [
			'notice' 	=> $this->getNotice(),
			'workspace' => $this->getWorkspace(),
			'board' 	=> $this->getBoard(),
			'gallery'	=> $this->getGallery(),
        ];
        
        return $main_datas;
    }

    public function getNotice(){

        $Board_model = new Board_model();
        
        $selector = "bd.uid,bd.subject,bd.mbruid,bd.name,bd.d_regis";
        $board_where = [];
        $board_where[] = "( ( (bd.hidden = 1 and ((SELECT COUNT(idx) FROM ".DB_T_bbs_hidshow." WHERE bd.uid = bbs_uid AND cate = 'bbs' AND mtype = 'p' AND muid = '".USER_INFO['memberuid']."') > 0 
        OR (SELECT COUNT(idx) FROM ".DB_T_bbs_hidshow." WHERE bd.uid = bbs_uid AND cate = 'bbs' AND mtype = 'g' AND muid in (".implode(",",USER_INFO['pergroups']).")) > 0 
        OR bd.mbruid = '".USER_INFO['memberuid']."') > 0)  ) OR bd.hidden = 0 )";

        $datas = $Board_model->getDatas( '1' , 1 , 5 ,$selector , $board_where );

        return $datas;
    }

    
    public function getBoard(){

        $datas = ['board'=>[], 'comment'=>[],'oneline'=>[]];

        $Search_model = new Search_model();

        $selector = "bd.uid,bd.subject,bd.mbruid,bd.name,bd.d_regis,bl.name as bbs_name,me.uid as menu_key";
        $where_arr = [];
        $datas['board'] = $Search_model->getBoard(1, 5 ,$selector,$where_arr);

        
        $selector = "bd.uid,bd.subject,bd.mbruid,bd.name,bd.d_regis";
        $where_arr = [];
        $datas['comment'] = $Search_model->getComment(1, 5 ,$selector,$where_arr);

        
        $selector = "bd.uid,bd.subject,bd.mbruid,bd.name,bd.d_regis";
        $where_arr = [];
        //$datas['board'] = $Search_model->getBoard(1, 5 ,$selector,$where_arr);


        return $datas;
    }

    
    public function getGallery(){

        $Board_model = new Board_model();
        $Finfo = new Info_model();
        
        $selector = "bd.uid,bd.subject,bd.mbruid,bd.name,bd.d_regis,bd.upload";
        $board_where = [];
        $board_where[] = "( ( (bd.hidden = 1 and ((SELECT COUNT(idx) FROM ".DB_T_bbs_hidshow." WHERE bd.uid = bbs_uid AND cate = 'bbs' AND mtype = 'p' AND muid = '".USER_INFO['memberuid']."') > 0 
        OR (SELECT COUNT(idx) FROM ".DB_T_bbs_hidshow." WHERE bd.uid = bbs_uid AND cate = 'bbs' AND mtype = 'g' AND muid in (".implode(",",USER_INFO['pergroups']).")) > 0 
        OR bd.mbruid = '".USER_INFO['memberuid']."') > 0)  ) OR bd.hidden = 0 )";

        $datas = $Board_model->getDatas( '40' , 1 , 5 ,$selector , $board_where );
        foreach( $datas as $key => $data ){
            if( $data['upload'] ){
                $upload_arr = explode(",",$data['upload']);
                $info = $Finfo->getFileInfo( $upload_arr[0] );
                $data['img_url'] = $info['url'].$info['folder'].$info['tmpname'];
            }else{
                $upload_arr = [];
                $data['img_url'] = "";
            }

            $datas[$key] = $data;
        }
        return $datas;
    }

    public function getWorkspace(){

        $datas = ['ing'=>[], 'lastst'=>[]];

        $Worker_model = new Worker_model();
        $Workspace = new Workspace_model();
        
        $where_arr = [];
        $where_arr[] = " wt.uno = '".USER_INFO['memberuid']."' ";
        $where_arr[] = " wt.state in (1,2) ";
        $datas['ing'] = $Worker_model->getWorkerSec( "wp.pj_idx, wp.p_type, wp.subject, wp.percent, wt.state" , $where_arr, 1, 5);
        foreach( $datas['ing'] as $key => $data ){
            $data['p_type_nm'] = $Workspace->w_ptype_nm_arr[$data['p_type']];
            $data['state_nm'] = $Worker_model->w_state_arr[$data['state']];

            $datas['ing'][$key] = $data;
        }

        $where_arr = [];
        $where_arr[] = " uno = '".USER_INFO['memberuid']."' 
                        OR 
                    ( SELECT count(idx) FROM ".DB_T_bbs_hidshow." WHERE cate = 'ws' AND wp.pj_idx = bbs_uid AND mtype='p' AND muid = '".USER_INFO['memberuid']."' ) 
                    OR
                    ( SELECT count(idx) FROM ".DB_T_bbs_hidshow." WHERE cate = 'ws' AND wp.pj_idx = bbs_uid AND mtype='g' AND muid in (".implode(",",USER_INFO['pergroups']).") ) 
                    ";        
        $datas['lastst'] = $Workspace->getDatas( 1 , 5, "wp.pj_idx,wp.subject,wp.state,wp.p_type,wp.percent", $where_arr , ['worker'=>false,'pcate'=>false]);
        foreach( $datas['lastst'] as $key => $data ){
            $data['p_type_nm'] = $Workspace->w_ptype_nm_arr[$data['p_type']];
            $data['state_nm'] = $Workspace->w_state_arr[$data['state']];

            $datas['lastst'][$key] = $data;
        }

        return $datas;

    }

}