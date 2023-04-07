<?php namespace App\Models\Search;

use CodeIgniter\Model;

/**
 * Class Account
 *
 * @package App\Models
 */
class Search_model extends Model
{

    private $intraDB;
    
    public function __construct()
    {
        $this->intraDB  = db_connect();
    }

    public function setPermissionSql( String $key, String $hidden_field, String $idx_field, String $cate, String $uno_field ){
        $sql = " ( 
                    ( 
                        ( ".$key.".".$hidden_field." = 1 
                            and 
                            (
                                (SELECT COUNT(idx) FROM ".DB_T_bbs_hidshow." WHERE ".$key.".".$idx_field." = bbs_uid AND cate = '".$cate."' AND mtype = 'p' AND muid = '".USER_INFO['memberuid']."') > 0 
                                OR 
                                (SELECT COUNT(idx) FROM ".DB_T_bbs_hidshow." WHERE ".$key.".".$idx_field." = bbs_uid AND cate = '".$cate."' AND mtype = 'g' AND muid in (".implode(",",USER_INFO['pergroups']).")) > 0 
                                OR ".$key.".".$uno_field." = '".USER_INFO['memberuid']."') > 0
                            )  
                        ) 
                        OR ".$key.".".$hidden_field." = 0 )";

        return $sql;
    }
   
    public function getBoard( Int $page = 1 , Int $pagesize = 10, String $selector = "*", Array $where_arr = [] ){
        $limit_start= ( $page-1 ) * $pagesize;

        $datas_build = $this->intraDB->table(DB_T_bbs_data." as bd");
        $datas_build->select($selector);
        $datas_build->join(DB_T_bbs_list." as bl","bd.bbs = bl.uid");
        $datas_build->join(DB_T_s_mbrdata." as m", 'bd.mbruid = m.memberuid','left');
        $datas_build->join(DB_T_s_menu." as me", "me.module = CONCAT('bbs/',bd.bbsid)",'left');
        $datas_build->where( $this->setPermissionSql( 'bd', 'hidden', 'uid', 'bbs', 'mbruid' ) );
        if( count($where_arr) > 0  ){
            $where = implode( " AND ", $where_arr);
            $datas_build->where( $where );
        }
        $datas_build->orderBy('bd.uid','DESC');
        $datas_build->limit($pagesize,$limit_start);
        $res = $datas_build->get();

        $datas = $res->getResultArray();

        return $datas;
    }

    public function getComment( Int $page = 1 , Int $pagesize = 10, String $selector = "*", Array $where_arr = [] ){
        $limit_start= ( $page-1 ) * $pagesize;

        $qry = "SELECT c.*,bd.subject as psubject,m.uid as menu_key FROM ".DB_T_s_comment." c 
        LEFT JOIN ".DB_T_bbs_data." bd ON bd.uid = c.`parent_uid` 
        LEFT JOIN ".DB_T_s_menu." m ON  m.module = CONCAT('bbs/',bd.bbsid)
        WHERE c.parent_type = 'bbs' AND c.parentmbr = '".USER_INFO['memberuid']."'
        UNION ALL
        SELECT c.*,wp.subject as psubject,m.uid as menu_key FROM ".DB_T_s_comment." c 
        LEFT JOIN ".DB_T_wp_plist." wp ON wp.pj_idx = c.parent_uid 
        LEFT JOIN ".DB_T_s_menu." m ON  m.module = CONCAT('bbs/',wp.p_type)
        WHERE c.parent_type = 'ws' AND c.parentmbr = '".USER_INFO['memberuid']."'
        ORDER BY d_regis DESC LIMIT ".$limit_start.", ".$pagesize;
        $res = $this->intraDB->query($qry);
        $datas = $res->getResultArray();

        foreach( $datas as $key => $data ){
            $data['bbs_link'] = "#";
            switch( $data['parent_type'] ){
                case 'bbs' :
                    $data['bbs_link'] = "/site/".$data['menu_key']."/bbs/view/".$data['parent_uid']."#comment_".$data['uid']."";
                    break;
                case 'ws' :
                    $data['bbs_link'] = "/site/".$data['menu_key']."/workspace/view/".$data['parent_uid']."#comment_".$data['uid']."";
                    break;
            }
            $datas[$key] = $data;
        }

        return $datas;
    }
}