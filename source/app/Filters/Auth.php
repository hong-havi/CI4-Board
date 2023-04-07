<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\Common\SiteModel;
use App\Models\Accounts\User_model;
use App\Models\Accounts\Group_model;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request)
    {
        $uri = service('uri');
        // Do something here
        $SiteModel = new SiteModel();
        $module = $uri->getSegment(1);

        $user_uno = $SiteModel->getParam('site_uno','','SESSION',false,"");
        
        if( $module == 'accounts'){
            
            define("USER_FLAG",false);
            define("USER_INFO",[]);
            define("USER_AFLAG",[]);

            return false;
        }

		if( $user_uno == 0 || !$user_uno ) {
			return $SiteModel->getReturn( $request->isAJAX() ,'direct_move','로그인 후 이용가능합니다.',['url'=>'/accounts/login']);
		}
       
        
        $User_model = new User_model();
        $Group_model = new Group_model();
        $user_data = $User_model->getUserInfo($user_uno);
        if( isset($user_data['memberuid']) ){
            //$user_data = $User_model->getAllInfo($user_data);
            $user_data = $User_model->getUserInfoDetail($user_uno,'m.*,m.name as name,g.name as gname, l.name as lname, gp.name as gpname, gp.uid as gpuid');
            $user_id = $User_model->getUserID('uno',$user_uno);
            $user_data['id'] = $user_id->id;

            $user_data['pergroups'] = $Group_model->getGrouplistReverse( $user_data['sosok'] , ['userflag'=>false,'onlyuid'=>true] );

            define("USER_FLAG",true);
            define("USER_INFO",$user_data);
            define("USER_AFLAG",$user_data['admin']);
            define("TEMPCODE",'T'.time().'_'.USER_INFO['memberuid']);
        }else{            
		    return $SiteModel->getReturn( $request->isAJAX() ,'direct_move','로그인 후 이용가능합니다.',['url'=>'/accounts/login']);
        }


    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response)
    {
        // Do something here
    }
}