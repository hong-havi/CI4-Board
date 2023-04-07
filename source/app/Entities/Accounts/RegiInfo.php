<?php namespace App\Entities\Accounts;

use CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

class Regiinfo extends Entity
{
    protected $attributes = [
        'site'=> '1',
        'id'=> null,
        'npw'=> null,
    ];

    public function setnpw(string $pass,int $uno){
        $regi_model = new \App\Models\Accounts\Register_model();
        
        $pass_string = md5($pass)."_".$uno;
        $regi_model->getPassword($pass_string);
        $npass = $pass;
        $this->attributes['npw'] = $npass;
    }
}