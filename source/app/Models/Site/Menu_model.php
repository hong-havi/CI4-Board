<?php namespace App\Models\Site;

use CodeIgniter\Model;

/**
 * Class common sitemodel
 *
 * @package App\Models
 */
class Menu_model extends Model
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

    public function getUserMenu( Int $uno, Int $site , Int $admin , Int $depth, Int $parent )
    {

        $perm_build = $this->intraDB->table(DB_T_s_permission);
        $perm_build->select('uid,mid,read,write,manager');
        $perm_build->where("uid",$uno)
        ->where(" (read = 1 or write = 1 or manager = 1) ");

        $perm_query = $perm_build->getCompiledSelect();

        $build = $this->intraDB->table(DB_T_s_menu." as m");
        $build->select('m.uid,m.gid,m.site,m.isson,m.parent,m.depth,m.id,m.menutype,m.mobile,m.hidden,m.hidden_main,m.reject,m.name,m.target,m.redirect,m.joint,m.module,m.module_type,m.imghead,m.addinfo ,per.read AS p_read,per.write AS p_write,per.manager AS p_manager');
        $build->join("(".$perm_query.") as per","m.uid = per.mid","left");
        if( $admin == 0 ){
            $build->where('per.mid is not null');
        }
        $build->where('m.reject','0');
        $build->where('m.site',$site);
        $build->where('m.depth',$depth);
        $build->where('m.parent',$parent);
        $build->orderBy("depth ASC, parent ASC, gid ASC");
        $res = $build->get();
        $datas = $res->getResultArray();
        $resdata = [];
        foreach( $datas as $key => $data ){
            $data['p_read'] = ($data['p_read']) ? $data['p_read'] : 0;
            $data['p_write'] = ($data['p_write']) ? $data['p_write'] : 0;
            $data['p_manager'] = ($data['p_manager']) ? $data['p_manager'] : 0;

            if( $data['isson'] == '1'){
                $data['child_datas'] = $this->getUserMenu( $uno, $site, $admin , ($data['depth']+1), $data['uid'] );
            }

            if( $depth == 1 ){
                $resdata[$data['hidden_main']][$data['uid']] = $data;
            }else{
                $resdata[$data['uid']] = $data;
            }
        }
        

        return $resdata;
    }

    public function setUserMenu( Int $uno, Array $menu_datas ){
        $cache = \Config\Services::cache();

        $menu_json = json_encode($menu_datas);
        $insert_data = [
            'uno'=>$uno,
            'menu_json'=>$menu_json
        ];
        $build = $this->intraDB->table(DB_T_s_menu_user);
        $build->replace($insert_data);

        $cache->save('user_menu::'.$uno,$menu_json,172800);
    }

    public function getUserRedis( Int $uno){
        $cache = \Config\Services::cache();
        
        $key = 'user_menu::'.$uno;
        $menu_json = $cache->get($key);
    
        $menu_arr = json_decode($menu_json,true);

        return $menu_arr;
    }

    public function getMenuInfo( Int $muid ){
        $info_build = $this->intraDB->table(DB_T_s_menu);
        $info_build->where('uid',$muid);
        $info_build->where('hidden','0');
        $info_build->where('reject','0');
        $res = $info_build->get();
        $info = $res->getRowArray();

        return $info;
    }

}