<?php namespace App\Models\Accounts;

use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\Array_;

/**
 * Class Account
 *
 * @package App\Models
 */
class Register_model extends Model
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



    public function insertID( Array $datas = [] ){
        $this->id_build->insert($datas);

        return $this->intraDB->insertID();
    }
    
    public function insertData( Array $datas = [] ){
        $this->data_build->insert($datas);

        return $this->intraDB->insertID();

    }

    public function insertDataSub( Array $datas= [] ){
        $this->datasub_build->insert($datas);

        return $this->intraDB->insertID();

    }


    public function updatePassword( string $password, int $uno ){        
        
        $Password_model = new \App\Models\Accounts\Password_model();
        
        $npw = $Password_model->getPassword($password,$uno);

        $this->id_build->where('uid',$uno);
        $this->id_build->update([ 'npw'=>$npw ]);

    }


    
    public function checkID( string $userid){
        $check_id_array = ['admin','administer','adm','oper','root','webmaster'];

        if( in_array($userid,$check_id_array) ){
            return 1;
        }

        $this->id_build->where('id',$userid);
        $res = $this->id_build->get();

        $datas = $res->getResult();

        return count($datas);

    }

}