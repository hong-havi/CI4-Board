<?php namespace App\Models\Common\Attach;

use CodeIgniter\Model;

/**
 * Class Account
 *
 * @package App\Models
 */
class Download_model extends Model
{

    private $intraDB;
    
    public function __construct()
    {
        $this->intraDB  = db_connect();
    }

   
    
}