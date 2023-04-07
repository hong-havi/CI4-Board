<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

use App\Models\Common\SiteModel;
use App\Models\Board\Board_model;

class Board_check implements FilterInterface
{
    public function before(RequestInterface $request)
    {

        $Board_model = new Board_model();
        $Site_model = new SiteModel();
         
        $MENU_INFO = MENU_INFO;

        $MENU_MODULES = explode("/",$MENU_INFO['module']); 
        if( $MENU_MODULES['0'] != 'bbs' ){
            return $Site_model->getReturn( 'PAGE' ,'direct_move','잘못된 접근입니다.',['url'=>'/errors/404/40001']);
        }

        $BOARD_ID = (isset($MENU_MODULES['1']) ) ? $MENU_MODULES['1'] : "";
        if( !$BOARD_ID ){            
            return $Site_model->getReturn( 'PAGE' ,'direct_move','잘못된 접근입니다.',['url'=>'/errors/404/40001']);
        }

        $BOARD_INFO = $Board_model->getBoardInfo( 'id' , $BOARD_ID );

        if( !isset($BOARD_INFO['uid']) ){
            return $Site_model->getReturn( 'PAGE' ,'direct_move','잘못된 접근입니다.',['url'=>'/errors/404/40001']);
        }
        

        define('BOARD_INFO',$BOARD_INFO);


    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response)
    {

    }
}