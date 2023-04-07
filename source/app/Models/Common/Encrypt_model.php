<?php namespace App\Models\Common;

use CodeIgniter\Model;

/**
 * Class Account
 *
 * @package App\Models
 */
class Encrypt_model extends Model
{

    public $enctype = "sha";
    public $length = 512;
    
    public function __construct()
    {
    }

    public function Encrypt($string){
        $encText = "";
        switch( $this->enctype ){
            case 'sha' :
                $encText = $this->setSha($string);
                break;
        }

        return $encText;
      
    }

    public function setSha( $string ){

        $intraDB  = db_connect();
        $string_esc = $intraDB->escapeString($string);
        $sql = "SELECT SHA2('".$string_esc."',".$this->length.") AS enctext";
        $query = $intraDB->query($sql);
        $data = $query->getRow();

        $encText = $data->enctext;

        return $encText;
    }

    
}