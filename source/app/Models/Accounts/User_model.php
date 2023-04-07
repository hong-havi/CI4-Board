<?php namespace App\Models\Accounts;

use CodeIgniter\Model;

/**
 * Class Account
 *
 * @package App\Models
 */
class User_model extends Model
{
    protected $intraDB;
    protected $DBTable;

    private $id_build;
    private $data_build;
    private $datasub_build;

    protected $birthtype_arr = ['0'=>'양력','1'=>'음력'];
    protected $sex_arr = ['1'=>'남','2'=>'여'];
    public $state_color = ['출근'=>'#20C92B','퇴근'=>'#FA1A1A'];

    public function __construct()
    {
        $this->intraDB  = db_connect();
        
        $this->id_build = $this->intraDB->table(DB_T_s_mbrid);
        $this->data_build = $this->intraDB->table(DB_T_s_mbrdata);
        $this->datasub_build = $this->intraDB->table(DB_T_s_mbrdata_sub);
    }


    public function getUserID( string $type, $value ){
        switch( $type ){
            case 'id' :
                $this->id_build->where('id',$value);
                break;
            case 'uno' :
                $this->id_build->where('uid',$value);
                break;
            default :
                return [];
                break;
        }
        $res = $this->id_build->get();
        $data = $res->getRow();

        return $data;

    }

    public function getUserInfo( Int $uno ){
        
        $this->data_build->where('memberuid',$uno);
        $this->data_build->where('auth','1');
        $this->data_build->where('site','1');
        $res = $this->data_build->get();
        
        $data = $res->getRowArray();

        return $data;

    }
    
    public function getUserInfoDetail( Int $uno ,String $selector = "*" ){
        
        $data_build = $this->intraDB->table(DB_T_s_mbrdata." as m");
        $data_build->select($selector);
        $data_build->join(DB_T_s_mbrgroup." as g"," m.sosok = g.uid ","left");
        $data_build->join(DB_T_s_mbrgroup." as gp"," g.bpuid = gp.uid ","left");
        $data_build->join(DB_T_s_mbrlevel." as l"," m.level = l.uid ","left");

        $data_build->where('m.memberuid',$uno);
        $data_build->where('m.auth','1');
        $data_build->where('m.site','1');
        $res = $data_build->get();
        
        $data = $res->getRowArray();

        return $data;

    }

    public function getUserList( String $selector = "*", Array $where_arr = [] ){
        $datas_build = $this->intraDB->table(DB_T_s_mbrdata." as m");
        $datas_build->join(DB_T_s_mbrgroup." as g"," m.sosok = g.uid ","left");
        $datas_build->join(DB_T_s_mbrlevel." as l"," m.level = l.uid ","left");
        if( count($where_arr) > 0  ){
            $where = implode( " AND ", $where_arr);
            $datas_build->where( $where );
        }
        $datas_build->select($selector);
        $res = $datas_build->get();
        $datas = $res->getResultArray();

        return $datas;
    }



    public function getPhoto( String $fileurl ){        
        
        //$url = "https://file.sjwcorp.kr/2020/05/26/df9e9f08dbce989cdcf7658dc60a1c1b141519.gif";
    }

    public function getUserPhone( int $uno){
        $this->data_build->where( 'memberuid',$uno );
    }

    public function getUserAdmin( int $uno ){
        $this->data_build->select('admin');
        $this->data_build->where("auth",'1');
        $this->data_build->where("memberuid",$uno);
        $res = $this->data_build->get();

        $data = $res->getRow();

        return $data;
    }

    public function getAllInfo( Array $user_info){
        $tmp_info = $user_info;

        $tmp_info['birth_date'] = date("Y-m-d",strtotime($user_info['birth1'].$user_info['birth2']));
        $tmp_info['birthtype_txt'] = (isset($this->birthtype_arr[$user_info['birthtype']])) ? $this->birthtype_arr[$user_info['birthtype']] : "";
        $tmp_info['sex_txt'] = (isset($this->sex_arr[$user_info['sex']])) ? $this->sex_arr[$user_info['sex']] : "";
        
        return $tmp_info;
    }


}