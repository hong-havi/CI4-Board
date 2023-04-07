<?php namespace App\Models\Accounts;

use CodeIgniter\Model;

/**
 * Class Account
 *
 * @package App\Models
 */
class Password_model extends Model
{
    
    public function __construct()
    {
        $this->intraDB  = db_connect();

        $this->data_build = $this->intraDB->table(DB_T_s_mbrdata);
    }

    public function getPassword(string $password, int $uno ){

        $Encrypt = new \App\Models\Common\Encrypt_model();

        $full_pw = md5($password)."_".$uno;
        $pwd = $Encrypt->Encrypt($full_pw);

        return $pwd;
      
    }

    public function setPwfail_cnt( Int $uno){
        $this->data_build->where('memberuid',$uno);
        $this->data_build->set('pwfail_cnt','pwfail_cnt + 1',false);
        $this->data_build->update();
    }

    public function setFailreset( Int $uno ){
        $this->data_build->where('memberuid',$uno);
        $this->data_build->set('pwfail_cnt',0);
        $this->data_build->update();
    }
    
}