<?php namespace App\Models\Site;

use CodeIgniter\Model;

/**
 * Class common sitemodel
 *
 * @package App\Models
 */
class Ip_model extends Model
{
    protected $intraDB;
  
    public function __construct()
    {
        $this->intraDB  = db_connect();
    }

    public function setMenu( Int $uno, Int $site , Int $admin , Int $depth, Int $parent ){
        $menu_datas = $this->getUserMenu( $uno, $site , $admin , $depth, $parent );
        $this->setUserMenu( $uno , $menu_datas );
    }

    /**
     * Function setDisalbe
     *
     * @description 유효기간 만료된 IP는 사용차단
     */
    public function setDisable(){
        $ip_build = $this->intraDB->table(DB_T_s_mbrip);
        $ip_build->set( 'useYN', 'N' );
        $ip_build->where( 'sdate >= NOW() OR edate <= NOW()' );
        $ip_build->Update();
    }


    /**
     * Function setSave
     *
     * @description 사용가능한 IP Redis 저장
     */
    public function setSave(){
        $iplists = $this->getIpDBlist("uno,type,ipaddr,useYN,cmut_flag,adm_flag");
        foreach( $iplists as $data ){
            
            $save_key = $data['uno'].":".$data['ipaddr'];

            if( $data['uno'] > 0 ){
                $ip_arr['each'][$save_key] = $data;
            }

            if( $data['type'] == 'server' ){
                $ip_arr['server'][$data['ipaddr']] = $data;
            }else{
                $ip_arr['all'][$data['ipaddr']] = $data;
            }

        }

        $this->setCacheSave($ip_arr);
    }

    public function getIpDBlist($selector = "*"){
        $ip_build = $this->intraDB->table(DB_T_s_mbrip);
        $ip_build->select($selector);
        $query = $ip_build->get();

        return $query->getResult('array');
    }

    public function getCacheData(){
        $cache = \Config\Services::cache();
        
        $datas = $cache->get('ipdatas');        

        if( isset($datas) ){
            return $datas;
        }else{
            return [];
        }
        
    }

    public function setCacheSave( Array $ip_arr){        
        $cache = \Config\Services::cache();
        
        $cache->save('ipdatas',$ip_arr,345600);
    }
}