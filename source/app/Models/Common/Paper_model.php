<?php namespace App\Models\Common;

use CodeIgniter\Model;
use App\Models\Accounts\Group_model;

/*
*	 Class SendPaper 
*	- uno_arr : 직원번호 UNO ARRAY
*	- memo : 글내용
*	- link : 글 링크
*	- bbs_no : 글 번호
*	- type : 
		일반 : 0:일반,1:전자결재,
        게시글 : 
            2:수신,3:참조,4:댓글,5:대댓글,
            6:관리자,
            7:채용-공고,8:채용-이력서,9:채용-면접,10채용-입사,
            11:근태알림,
            12:회의,
            13:메뉴권한요청,
            14:워크시트수신,15:워크시트참조,16:워크시트댓글,17워크시트대댓글
*
*/
class Paper_model extends Model
{
    protected $intraDB;
    
    public $paper_type = [
        'bbs'=> ['2'=>'수신','3'=>'참조','4'=>'댓글','5'=>'대댓글'],
        'ws'=> ['14'=>'수신','15'=>'참조','16'=>'댓글','17'=>'대댓글'],
        'edms'=> ['2'=>'수신','3'=>'참조','4'=>'댓글','5'=>'대댓글'],
        'system'=> ['6'=>'관리자','11'=>'근태알림','12'=>'회의','13'=>'메뉴권한요청'],
        'recruit'=> ['7'=>'공고','8'=>'이력서','9'=>'면접','10'=>'입사'],
    ];

    public function __construct()
    {
        $this->intraDB  = db_connect();
    }

    public function send( Int $senduno, Array $uno_arr, String $memo, String $html, String $link, String $bbs_no, Int $type=0, Array $opt = ['mysend'=>false] ){
        $group = new Group_model();

        if( !$senduno ){
            return false;
        }

        $user_lists = $group->setUnoList($uno_arr);
        $datas = [];
        $date = date("YmdHis");
        foreach( $user_lists as $uno ){
            if( $senduno == $uno && $opt['mysend'] == false) continue;

            $tmp_ = [
                'type'      => $type,
                'parent'    => '0',
                'my_mbruid' => $senduno,
                'by_mbruid' => $uno,
                'inbox'     => '1',
                'content'   => $memo,
                'html'      => $html,
                'upload'    => $link,
                'bbs_uid'   => $bbs_no,
                'd_regis'   => $date,
                'd_read'    => '0',
            ];
            $datas[] = $tmp_;
        }
        if( count($datas) > 0 ){
            $builder = $this->intraDB->table(DB_T_s_paper);
            $builder->insertBatch($datas);
        }
        
        return true;
    }


    public function makeTpl( String $type, String $subject, String $content = "", String $link = ""){
        $tpl = "[".$type."] ".$subject."";
        $tpl .= "\n \n";
        $tpl .= $content;
        $tpl .= "\n \n";
        if( $link ){
            $tpl .= "<a href=\"paper_move('".$link."')\">▶ 바로가기</a>";
        }        
        return $tpl;
    }

    public function getLists( Int $page = 1 , Int $pagesize = 10, String $selector = "*", Array $where_arr = [] ){
        
        $limit_start= ( $page-1 ) * $pagesize;

        $datas_build = $this->intraDB->table(DB_T_s_paper." as p");
        $datas_build->select($selector);
        $datas_build->join(DB_T_s_mbrdata." as bm", 'p.by_mbruid = bm.memberuid','left');

        if( count($where_arr) > 0  ){
            $where = implode( " AND ", $where_arr);
            $datas_build->where( $where );
        }

        $datas_build->orderBy('p.uid','DESC');
        $datas_build->limit($pagesize,$limit_start);
        $res = $datas_build->get();

        $datas = $res->getResultArray();

        return $datas;
    }

    public function getCount( Array $where_arr = array(1) ){
        $datas_build = $this->intraDB->table(DB_T_s_paper." as p");
        $datas_build->join(DB_T_s_mbrdata." as bm", 'p.by_mbruid = bm.memberuid','left');

        $datas_build->select("count(p.uid) as total");
        if( count($where_arr) > 0  ){
            $where = implode( " AND ", $where_arr);
            $datas_build->where( $where );
        }
        $res = $datas_build->get();

        $datas = $res->getRow();

        return $datas->total;
    }

    //안읽은 쪽지 갯수 카운트업
    public function noread( Array $uno_arr ){

    }

    //안읽은 쪽지 갯수 조정
    public function noread_fix(){

    }
}
