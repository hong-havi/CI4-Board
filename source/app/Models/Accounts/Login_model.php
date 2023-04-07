<?php namespace App\Models\Accounts;

use CodeIgniter\Model;

/**
 * Class Account
 *
 * @package App\Models
 */
class Login_model extends Model
{
    protected $intraDB;
    protected $DBTable;

    private $id_build;

    public function __construct()
    {
        $this->intraDB  = db_connect();
        
        $this->id_build = $this->intraDB->table(DB_T_s_mbrid);
        $this->data_build = $this->intraDB->table(DB_T_s_mbrdata);
        $this->datasub_build = $this->intraDB->table(DB_T_s_mbrdata_sub);
    }
    
    public function insertLog( string $id, Int $uno ,string $type , int $errcode , String $time = "" ){
        $request = \Config\Services::request();

        switch( $errcode ){
            case '1' : $reason = "Success"; break;
            case '2' : $reason = "No ID"; break;
            case '3' : $reason = "Wrong Password"; break;
            case '4' : $reason = "limit 5 over"; break;
            case '5' : $reason = "access denied"; break;
            case '6' : $reason = "Wrong Confirm Number"; break;
            
            default : $reason = ""; break;
        }

        $insertdata = [
            'id'        => $id,
            'uno'        => $uno,
            'addr_ip'   => $request->getServer('REMOTE_ADDR'),
            'type'      => $type,
            'state'     => $errcode,
            'reason'    => $reason,
            'agent'     => $request->getServer('HTTP_USER_AGENT'),
            'sec_flag'  => '0',
            'wdate'     => ($time) ? $time : date("Y-m-d H:i:s")
        ];
        $this->intraDB->table(DB_T_log_access)->insert($insertdata);
    }

    public function setSession( Int $uno ){
        $session = \Config\Services::session();

        $session->start();
        $session->set('site_uno',$uno);
    }

    public function setLogout(){
        $session = \Config\Services::session();
        $session->destroy();
    }

    public function setCnumber($uno){
        $Encrypt = new \App\Models\Common\Encrypt_model();
        
        $auth_ran = rand(100000,999999);
        $text = $uno."::".$auth_ran;

        $tokey = $Encrypt->Encrypt($text);

        $data = [
            'menuid'=> 0,
            'tokey' => $tokey,
            'muid'  => $uno,
            'rdate' => date("Y-m-d H:i:s")
        ];
        $this->intraDB->table(DB_T_s_toekn)->insert($data);

        return $auth_ran;
    }

    public function checkCnumber( int $uno, String $number){
        
        $Encrypt = new \App\Models\Common\Encrypt_model();

        $text = $uno."::".$number;
        $tokey = $Encrypt->Encrypt($text);

        $build = $this->intraDB->table(DB_T_s_toekn);
        $build->where('menuid','0');
        $build->where('muid',$uno);
        $build->where('tokey',$tokey);
        $build->where('rdate > DATE_ADD(NOW(),INTERVAL -3 MINUTE)');
        $res = $build->get();

        $datas = $res->getResult();
       
        if( count($datas) > 0 ){
            return true;
        }else{
            return false;
        }
        
    }
}