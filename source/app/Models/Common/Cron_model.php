<?php namespace App\Models\Common;

use CodeIgniter\Model;

/**
 * Class common sitemodel
 *
 * @package App\Models
 */
class Cron_model extends Model
{
    private $start_time = 0;
    private $end_time = 0;

    public $cron_name = "";
    public $cron_memo = "";
  
    public function __construct()
    {        
        
    }

    public function start( string $name ){
        $this->start_time = microtime(true);
    }

    public function end( string $memo = "" ){

    }

}