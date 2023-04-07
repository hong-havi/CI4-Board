<?php namespace App\Models\Accounts;

use CodeIgniter\Model;

/**
 * Class Account
 *
 * @package App\Models
 */
class Auth_model extends Model
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

    
    public function getUserID( string $type, $value ){
        switch( $type ){
            case 'id' :
                $this->id_build->where('id',$value);
                break;
            case 'uno' :
                $this->id_build->where('uid',$value);
                break;
            default :
                return [];
                break;
        }
        $res = $this->id_build->get();
        $data = $res->getRow();

        return $data;

    }

    
}