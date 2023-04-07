<?php namespace App\Models\Favorit;

use CodeIgniter\Model;

/**
 * Class common sitemodel
 *
 * @package App\Models
 */
class Favorit_model extends Model
{
    protected $intraDB;
  
    public function __construct()
    {
        $this->intraDB  = db_connect();
    }

    public function getInfo( String $type, Int $uid ){
        $builder = $this->intraDB->table(DB_T_bbs_scrap);
        $builder->where('buid',$uid);
        $builder->where('mbruid',USER_INFO['memberuid']);
        $builder->where('type',$type);
        $res = $builder->get();

        return $res->getRowArray();
    }

    public function insertFav( String $type, Int $uid ){
        $builder = $this->intraDB->table(DB_T_bbs_scrap);
        $set = [
            'mbruid' => USER_INFO['memberuid'],
            'type' => $type,
            'buid' => $uid
        ];
        $builder->set($set);
        return $builder->insert();
    }

    public function deleteFav( String $type, Int $uid ){
        $builder = $this->intraDB->table(DB_T_bbs_scrap);
        $builder->where('buid',$uid);
        $builder->where('mbruid',USER_INFO['memberuid']);
        $builder->where('type',$type);
        return $builder->delete();
    }
}