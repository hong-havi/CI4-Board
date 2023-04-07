<?php namespace App\Controllers\_Testing;

use App\Controllers\TestController;
use App\Models as Model;

class Setting extends TestController
{
	public function __construct()
	{
		
	}

	public function index()
	{		
        $this->setDB();
        $this->setIp();
        $this->setUMentionList();
	}

    private function setDB(){
        $Mig = new Model\Unit\MigDB_model();
        $Mig->setdb();
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