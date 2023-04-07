<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\Site\Ip_model;

class Ip implements FilterInterface
{
    public function before(RequestInterface $request)
    {
        
        $session = \Config\Services::session();
        $IP = new Ip_model();

        $iplists = $IP->getCacheData();
        $remote_ip = $request->getServer('REMOTE_ADDR');
        $uno = $session->get('site_uno');        

        $IP_Stat = ['PLACE'=>'OUT','CMUT'=>'N','ADM'=>'N','INFO'=>[]];

        
        //유저별 IP 등록 여부 확인
        if( isset($uno) ){ //유저 로그인 상태
            
        }else{ //비로그인 상태
           if( isset($iplists['all'][$remote_ip]) ){
                $tmp_ip = $iplists['all'][$remote_ip];
                if( in_array($tmp_ip['type'],array('vpn','inpc','com','server')) ){
                    $IP_Stat = array(
                        'PLACE' => "IN", // 내부,외부 접속 여부
                        'CMUT' => $tmp_ip['cmut_flag'], // 출퇴근 기능 여부
                        'ADM' => $tmp_ip['adm_flag'], //관리자 접속 가능여부
                        'INFO' => $tmp_ip
                    );
                }

           }
        }

       define( 'IP', $IP_Stat );
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response)
    {
        // Do something here
    }
}