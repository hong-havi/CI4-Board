<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

use App\Models\Site as Site ;
use App\Models\Common\SiteModel;


class Menuinfo implements FilterInterface
{
    public function before(RequestInterface $request)
    {
        $uri = service('uri');
        $Menu = new Site\Menu_model();
        $Permission_model = new Site\Permission_model();
        $SiteModel = new SiteModel();


        $uno = session('site_uno');
        $muid = $uri->getSegment(2);
        $menu_info = $Menu->getMenuInfo( $muid );
        $menu_info['module_arr'] = explode("/",$menu_info['module']);
        if( !isset($menu_info) ){
            return $SiteModel->getReturn( $request->isAJAX() ,'direct_move','로그인 후 이용가능합니다.',['url'=>'/errors/404/40001']);
        }

        $permission = $Permission_model->getMenu( $uno, $muid );

        if( $permission['read'] == '0' && $permission['write'] == '0' && $permission['manager'] =='0' ){
            return $SiteModel->getReturn( $request->isAJAX() ,'direct_move','로그인 후 이용가능합니다.',['url'=>'/errors/500/50001']);
        }
       
        define("MENU_INFO",$menu_info);
        define("MENU_PERMISSION",$permission);

    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response)
    {
        // Do something here
    }
}