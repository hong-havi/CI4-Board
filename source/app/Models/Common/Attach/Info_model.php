<?php namespace App\Models\Common\Attach;

use CodeIgniter\Model;

/**
 * Class Account
 *
 * @package App\Models
 */
class Info_model extends Model
{

    private $intraDB;
    
    public function __construct()
    {
        $this->intraDB  = db_connect();
    }

    public function getFdatas($fnos_arr,$selector = 'up.*,m.name as user_name'){
        
        $datas_build = $this->intraDB->table(DB_T_s_upload." as up");
        $datas_build->select($selector);
        $datas_build->join(DB_T_s_mbrdata." as m", 'up.mbruid = m.memberuid','left');

        $datas_build->whereIn('up.uid',$fnos_arr);
        $datas_build->where('up.hidden','0');
        $datas_build->where('up.del','N');
        $datas_build->OrderBy('up.gidx DESC,up.`version` DESC');
        $res = $datas_build->get();

        $datas = $res->getResultArray();
        return $datas;
    }
    
    public function getFileInfo( int $fuid ){

        $builder = $this->intraDB->table(DB_T_s_upload);
        $builder->select('*');

        $builder->where('uid',$fuid);
        $res = $builder->get();

        $data = $res->getRowArray();
        
        return $data;
    }
    
    public function getFileInfogidx( int $gidx ){

        $builder = $this->intraDB->table(DB_T_s_upload);
        $builder->select('*');

        $builder->where('gidx',$gidx);
        $builder->orderBy('version desc');
        $res = $builder->get();

        $data = $res->getRowArray();
        
        return $data;
    }


    public function makeTpl($gdatas, $mode){
        helper('number');

        $tpl = "";
     
        foreach( $gdatas as $datas ){
            $gcnt = count($datas);
            foreach( $datas as $k => $data ){

                $downlink = "/common/attach/download/".$data['uid']."";

                $edit_btn = $del_btn = $addup_btn = $clist_btn = "";
                $caption = ($k == 0) ? (($data['caption']) ? "[".$data['caption']."]" : "") : "└";

                if( $data['mbruid'] == USER_INFO['memberuid'] && $mode == 'write'){
                    $del_btn = "<a href=\"javascript:;\" class=\"atta-del\" fuid=\"".$data['uid']."\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"파일삭제\"><i class=\"sjwi-delete\"></i></a>";
                    $edit_btn = "<a href=\"javascript:;\" class=\"atta-edit\" fuid=\"".$data['uid']."\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"파일정보수정\"><i class=\"sjwi-edit text-siwon\"></i></a>";
                }
                if( $k ==  0 && $mode == 'write'){
                    $addup_btn = "<a href=\"javascript:;\" class=\"atta-upload\" gidx=\"".$data['gidx']."\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"상위버전 파일 업로드\"><i class=\"sjwi-file_plus text-siwon\"></i></a>";
                }
                if( $gcnt > 1 && $k == 0 ){
                    $clist_btn = "<a href=\"javascript:;\" class=\"atta-list\" gidx=\"".$data['gidx']."\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"하위버전 파일 리스트 토글\"><i class=\"sjwi-list text-siwon\"></i></a>";
                }

                $tpl .= "
                <tr data=\"fd".$data['uid']."\" gdata=\"fg".$data['gidx']."\" ".(($k != 0) ? "style=\"display:none\" child" : "").">
                    <td>#F".$data['uid']."</td>
                    <td>".$caption."</td>
                    <td class=\"at-version\">ver ".$data['version']."</td>
                    <td class=\"at-fname\"><a href=\"". $downlink."\" target=\"_blank\">".$data['name']."</a> <span class=\"fsize\">".number_to_size($data['size'],2)."</span> (".number_format($data['down']).")</td>
                    <td class=\"at-date\">".date("Y.m.d H:i",strtotime($data['d_regis']))."</td>
                    <td class=\"at-btn\">
                        ".$del_btn."
                        ".$edit_btn."     
                        <a href=\"". $downlink."\" target=\"_blank\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"다운로드\"><i class=\"sjwi-download text-siwon\"></i></a>
                        ".$addup_btn."
                        ".$clist_btn."
                    </td>
                </tr>";
            }
        }



        return $tpl;
    }    


    public function checkPermission( int $uno , Array $file ){
        return true;
    }

    public function editInfo( int $fuid , Array $setData){
        $builder = $this->intraDB->table(DB_T_s_upload);
        $builder->set($setData);
        $builder->where('uid',$fuid);
        return $builder->update();
    }

    public function getListForm( $thema = "default" ){
    }
}