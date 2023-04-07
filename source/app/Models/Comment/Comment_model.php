<?php namespace App\Models\Comment;

use CodeIgniter\Model;

/**
 * Class common sitemodel
 *
 * @package App\Models
 */
class Comment_model extends Model
{
    protected $intraDB;


    public function __construct()
    {
        $this->intraDB  = db_connect();
        
    }

    public function getDatas( String $uptype, int $parent ){
        $upload = new \App\Models\Common\Attach\Info_model();
        $builder = $this->intraDB->table(DB_T_s_comment);
        $builder->select('*');
        $builder->where('parent',($uptype.$parent));
        $builder->orderBy('uid','desc');
        $res = $builder->get();
        $datas = $res->getResultArray();
        foreach( $datas as $key=> $data ){

            if( $data['upload'] ){
                $data['upload_datas'] = $upload->getFdatas( explode(",",$data['upload']),'up.uid,up.name,up.size,up.down' );
            }else{
                $data['upload_datas'] = [];
            }

            $data['onelists'] = $this->getOneDatas( $data['uid'] );

            $datas[$key] = $data;
        }
        
        return $datas;
    
    }

    public function getOneDatas( Int $parent ){
        $builder = $this->intraDB->table(DB_T_s_oneline);
        $builder->where('parent',$parent);
        $builder->orderBy('uid','desc');
        $res = $builder->get();

        $datas = $res->getResultArray();
        return $datas;
    }
    
    public function getOne( int $uid ){
        $builder = $this->intraDB->table(DB_T_s_oneline);
        $builder->where('uid',$uid);
        $res = $builder->get();

        $data = $res->getRowArray();
        return $data;
    }
    
    public function getComment( int $uid ){
        $builder = $this->intraDB->table(DB_T_s_comment);
        $builder->where('uid',$uid);
        $res = $builder->get();

        $data = $res->getRowArray();
        return $data;
    }

    public function getOneUserlist( int $parent ){
        $builder = $this->intraDB->table(DB_T_s_oneline);
        $builder->select('mbruid');
        $builder->where('parent',$parent);
        $builder->groupBy('mbruid');
        $res = $builder->get();

        $datas = $res->getResultArray();
        $result = [];
        foreach( $datas as $data ){
            $result[] = $data['mbruid'];
        }
        return $result;
    }

    public function setInsert( Array $set_data ){
        $builder = $this->intraDB->table(DB_T_s_comment);
        $builder->set($set_data);
        $res = $builder->insert();
        
        if( $res ){
            $cno = $this->intraDB->insertID();
            return $cno;
        }else{
            return 0;
        }
    }

    public function setOneInsert( Array $set_data ){
        $builder = $this->intraDB->table(DB_T_s_oneline);
        $builder->set($set_data);
        $res = $builder->insert();
        
        if( $res ){
            $cno = $this->intraDB->insertID();
            return $cno;
        }else{
            return 0;
        }

    }

    public function setStatus( String $uptype, Int $parent ,String $type = '+1'){
        switch( $uptype ){
            case 'ws' : 
                $builder = $this->intraDB->table(DB_T_wp_plist);
                $builder->set('cmt_cnt','cmt_cnt'.$type,false);
                $builder->where('pj_idx',$parent);
                $builder->update();
                break;
            case 'bbs' : default :
                $builder = $this->intraDB->table(DB_T_bbs_data);
                $builder->set('comment','comment'.$type,false);
                $builder->where('uid',$parent);
                $builder->update();
            break;
        }
    }
    public function setStatusOne( String $uptype, Int $parent , String $type = '+1'){
        switch( $uptype ){
            case 'ws' : 
                $builder = $this->intraDB->table(DB_T_wp_plist);
                $builder->set('cmt_cnt','cmt_cnt'.$type,false);
                $builder->where('pj_idx',$parent);
                $builder->update();
                break;
            case 'bbs' : default :
                $builder = $this->intraDB->table(DB_T_bbs_data);
                $builder->set('oneline','oneline'.$type,false);
                $builder->where('uid',$parent);
                $builder->update();
            break;
        }
    }

    public function parent_info( $uptype, Int $parent ){
        $res = ['subject'=>'','link'=>''];
        switch( $uptype ){
            case 'ws' : 
                $ws_model = new \App\Models\Workspace\Workspace_model();
                $wp_data = $ws_model->getPJData($parent,"wp.subject,wp.uno");
                $res['subject'] = $wp_data['subject'];
                $res['muno'] = $wp_data['uno'];
                $res['link'] = "/site/".MENU_INFO['uid']."/workspace/view/".$parent;
                $res['sendtype'] = 16;
                break;
            case 'bbs' : default :
                $board_model = new \App\Models\Board\Board_model();
                $board_data = $board_model->getData( '', $parent , "bd.subject,bd.mbruid" );
                $res['subject'] = $board_data['subject'];
                $res['muno'] = $board_data['mbruid'];
                $res['link'] = "/site/".MENU_INFO['uid']."/bbs/view/".$parent;
                $res['sendtype'] = 4;
            break;
        }

        return $res;
        
    }

    public function deleteComment( int $uid ){
        $builder = $this->intraDB->table(DB_T_s_comment);
        $builder->where('uid',$uid);
        return $builder->delete();
    }

    public function deleteOneline( int $uid ){
        $builder = $this->intraDB->table(DB_T_s_oneline);
        $builder->where('uid',$uid);
        return $builder->delete();
    }

    public function modifyComment( Array $set_data, int $uid){
        $builder = $this->intraDB->table(DB_T_s_comment);
        $builder->set($set_data);
        $builder->where('uid',$uid);
        return $builder->update();
    }
    public function modifyOneline( Array $set_data, int $uid){
        $builder = $this->intraDB->table(DB_T_s_oneline);
        $builder->set($set_data);
        $builder->where('uid',$uid);
        return $builder->update();

    }
}