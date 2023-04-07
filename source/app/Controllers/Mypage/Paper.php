<?php namespace App\Controllers\Mypage;

use App\Controllers\BaseController;

use App\Models\Common\Paper_model;

class Paper extends BaseController
{
    private $view_datas;
    private $Paper;

	public function __construct()
	{
        $this->Paper = new Paper_model();

        $this->view_datas = [
			'css_cell' => [
				['link'=>'_assets/css/views/mypage/paper.css']
			],
			'script_cell' => [
			//	['link'=>'_assets/js/views/board/board.js']
			]

        ];
	}

    public function index(  ){

    }

    public function lists(){
        

		$ptype = $this->SiteModel->getParam('ptype','','GET',false,'');
		$sec_type = $this->SiteModel->getParam('sec_type','','GET',false,'');
		$sec_key = $this->SiteModel->getParam('sec_key','','GET',false,'');
		$sec_val = $this->SiteModel->getParam('sec_val','','GET',false,'');

        $pager = \Config\Services::pager();
        
		$page = $this->SiteModel->getParam('page_','','GET',false,'1');
        $pagesize = 10;

        $where_arr = [];
        $where_arr[] = " p.my_mbruid = '".USER_INFO['memberuid']."' ";

        if( $sec_key && $sec_val ){
            switch( $sec_key ){
                case 'by_mbruid' :
                    $where_arr[] = " bm.name like '%".$sec_val."%'";
                    break;
                case 'content' :
                    $where_arr[] = " p.content like '%".$sec_val."%'";
                    break;
            }
        }
        $lists = $this->Paper->getLists( $page , $pagesize, "p.uid,p.type,p.content,p.bbs_uid,p.d_regis,p.upload,p.d_read_flag,bm.memberuid,bm.name", $where_arr );
        $total = $this->Paper->getCount( $where_arr );
        
		$pager = $pager->makeLinks( $page, $pagesize, $total ,'default_full',0,'');

        $this->view_datas['lists'] = $lists;
        return $this->view( 'mypage/paper/list',$this->view_datas );
    }

}
