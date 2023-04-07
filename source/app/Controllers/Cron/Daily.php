<?php

namespace App\Controllers\Cron;

use App\Controllers\CronController;
use App\Models as Model;

class Daily extends CronController
{

  public function __construct()
  {
  }

  public function index( String $choice = "" )
  {
    $this->Cron->start('데일리 크론');

    //메뉴 셋팅 프로세스
    if( $choice == '' || $choice == 'menu' ){
      $this->setUserMenu();
    }
    
    //IP 셋팅 프로세스
    if( $choice == '' || $choice == 'ip' ){
      $this->setIp();
    }

    //IP 멘션 셋팅
    if( $choice == '' || $choice == 'umention' ){
      $this->setUMentionList();
    }

  }

  public function setUserMenu()
  {
    $Menu = new Model\Site\Menu_model();
    $User = new Model\Accounts\User_model();
    $user_lists = $User->getUserList("m.memberuid,m.admin,m.site", ['m.auth = 1']);
 
    foreach ($user_lists as $user_info) {
      $Menu_datas = $Menu->getUserMenu( $user_info['memberuid'],$user_info['site'], $user_info['admin'], 1, 0 );
      $Menu->setUserMenu( $user_info['memberuid'] , $Menu_datas );
    }    
  }

  public function setIp(){
    $IP = new Model\Site\Ip_model();

    $IP->setDisable();
    $IP->setSave();
  }

  public function setUMentionList(){
    $User = new Model\Accounts\User_model();
    $where_arr = [];
    $where_arr[] = "m.auth = '1'";
    $user_lists = $User->getUserList( " concat('p_',m.memberuid) as uno,m.name as name , concat('@',m.name,' ',l.name) as id ,g.name as sosok_name ", $where_arr);

    $cache = \Config\Services::cache();

    $cache->save('umention',$user_lists,172800);
  }

}
