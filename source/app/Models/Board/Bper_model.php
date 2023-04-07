<?php namespace App\Models\Board;

use CodeIgniter\Model;

/**
 * Class Account
 *
 * @package App\Models
 */
class Bper_model extends Model
{
    protected $intraDB;


    public function __construct()
    {
        $this->intraDB  = db_connect();
        
    }
}