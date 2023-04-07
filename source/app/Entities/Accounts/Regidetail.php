<?php namespace App\Entities\Accounts;

use CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

class Regidetail extends Entity
{
    protected $attributes = [
        'site'=> '1',
        'auth'=> '3',
        'sosok'=>'0',
        'level'=>'0',
        'level_det'=>'0',
        'email'=>'',
        'name'=>'',
        'nic'=>'',
        'sex'=>'',
        'birth1'=>'',
        'birth2'=>'',
        'birthtype'=>'',
        'tel1'=>'',
        'tel2'=>'',
        'zip'=>'',
        'addr0'=>'',
        'addr1'=>'',
        'addr2'=>'',
        'last_pw'=>'',
        'd_regis'=>''
    ];

    protected $datamap = [
        'full_name' => 'name'
    ];
    
}