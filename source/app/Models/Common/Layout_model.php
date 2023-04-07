<?php namespace App\Models\Common;

use CodeIgniter\Model;

use App\Models\Site\Menu_model;

/**
 * Class common sitemodel
 *
 * @package App\Models
 */
class Layout_model extends Model
{

    public function __construct()
    {        
		helper('html');
    }

    public function getJSscript(array $params = []){
        $script_tpl = "";
        if( count( $params ) > 0 ){
            foreach( $params as $data){
                $link = $data['link']."?ver=".JS_U_VERSION;
                $indexpage = (isset($data['indexpage'])) ? $data['indexpage'] : false;
                $script_tpl .= script_tag($link,$indexpage);
            }           
        }
        return $script_tpl;        
    }

    public function getCss(array $params = []){
        $css_tpl = "";
        if( count( $params ) > 0 ){
            foreach( $params as $data){

                $link = $data['link']."?ver=".CSS_U_VERSION;
                $rel = (isset($data['rel'])) ? $data['rel'] : 'stylesheet';
                $type = (isset($data['type'])) ? $data['type'] : 'text/css';
                $title = (isset($data['title'])) ? $data['title'] : '';
                $media = (isset($data['media'])) ? $data['media'] : '';
                $indexPage = (isset($data['indexPage'])) ? $data['indexPage'] : false;
                $css_tpl .= link_tag($link,$rel,$type,$title,$media,$indexPage);
            }           
        }
        return $css_tpl;        
    }
    
    public function getMenu( Int $uno ){
        
        $menu_model = new Menu_model();

        $menu_arr = $menu_model->getUserRedis( $uno );
        if( is_array($menu_arr) && count($menu_arr) > 0 ){
            $sidemenu_tpl = $this->getSideMenuTpl( $menu_arr[0] , 1 );
            $sitelink_tpl = $this->getSiteMenuTpl( $menu_arr[1]['79']['child_datas'] );
        }else{
            $sidemenu_tpl = "";
            $sitelink_tpl = "";
        }
        
        return ['sidemenu'=>$sidemenu_tpl,'sitelink'=>$sitelink_tpl];        
    }

    
    public function getSideMenuTpl( Array $menu_arr , Int $depth ){
        switch( $depth ){
            case '1' : $ul_class = "nav"; break;
            default : $ul_class = "nav-dropdown-items";  break;
        }
        $tpl = "<ul class=\"".$ul_class."\">";
        foreach( $menu_arr as $menu_data ){
            if( $menu_data['hidden_main'] == 1 ) continue;
            
            $menu_module = explode("/",$menu_data['module']);
            
            $page_link_arr = ['site',$menu_data['uid']];
            
            if( isset($menu_module[0]) ){
                if( $menu_data['module_type'] ){
                    $page_link_arr[] = $menu_module[0];
                }else{
                    foreach( $menu_module as $module ){
                        $page_link_arr[] = $module;    
                    }
                }
            }

            
            

            $page_link = "/".implode("/",$page_link_arr);
            
            $child_datas = (isset($menu_data['child_datas'])) ? $menu_data['child_datas'] : [];

            if( count($child_datas) > 0 ){
                $child_tpl = $this->getSideMenuTpl( $child_datas, (++$depth) );
                $drop_class = "nav-dropdown";
                $droptoggle = "nav-dropdown-toggle";
                $page_link = "javascript:;";
            }else{
                $child_tpl = "";
                $drop_class = "";
                $droptoggle = "";
            }

            $depth_class = " dep_".$menu_data['depth'];

            $tpl .= "<li class=\"nav-item ".$drop_class." ".$depth_class."\">";
            $tpl .= "   <a class=\"nav-link ".$droptoggle."\" href=\"".$page_link."\">".$menu_data['name']."</a>";
            $tpl .= $child_tpl;
            $tpl .= "   </li>";
            
        }
        $tpl .= "</ul>";

        return $tpl;

    }
    
    public function getSiteMenuTpl($menu_arr){
        $menu_redata = ['a'=>[],'d'=>[],'0'=>[]];
        foreach( $menu_arr as $menu_data ){
            $menu_data['addinfo'] = ($menu_data['addinfo']) ? $menu_data['addinfo'] : "0";
            $menu_redata[$menu_data['addinfo']][] = $menu_data;
        }

        $tpl = "";
        foreach( $menu_redata as $key => $menu_datas){
            if( count($menu_datas) > 0 ){
                foreach( $menu_datas as $menu_data ){
                    $tpl .= "<div class=\"callout callout-info m-0 py-1\"><a href=\"".$menu_data['joint']."\" target=\"_blank\">".$menu_data['name']."</a></div>";
                }
                $tpl .= "<hr class=\"mx-3 my-1\"></hr>";
            }
        }

        return $tpl;
    }
	
}