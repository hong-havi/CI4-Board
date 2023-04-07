<?php namespace App\Models\Unit;

use CodeIgniter\Model;

/**
 * Class Account
 *
 * @package App\Models
 */
class MigDB_model extends Model
{
    
    public function __construct()
    {
        $this->intraDB  = db_connect();
        
    }

    public function setdb(){   
       
    }
}