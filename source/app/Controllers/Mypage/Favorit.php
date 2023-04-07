<?php namespace App\Controllers\Mypage;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Favorit\Favorit_model;

class Favorit extends BaseController
{
    use ResponseTrait;
    
	public function __construct()
	{
		
	}

	public function proc()
	{		
        $favorit = new Favorit_model();

		$type = $this->SiteModel->getParam('type','bbs','POST',);
        $uid = $this->SiteModel->getParam('uid','','POST');
        
        $check = $favorit->getInfo( $type, $uid );

        if( !isset($check['uid']) ){
            $res = $favorit->insertFav($type,$uid);
            $fav_state = '1';
        }else{
            $res = $favorit->deleteFav($type,$uid);
            $fav_state = '0';
        }

        if( $res ){                
            $this->SiteModel->setResStatus(1,'');
			$this->SiteModel->setResData('fav_state',$fav_state);
            return $this->respondCreated($this->SiteModel->response_data,200);
        }else{
            $this->fail('데이터 처리도중 오류가 발생했습니다.');
        }

	}

}
