<?php namespace App\Models\Accounts;

use CodeIgniter\Model;
use App\Models\Accounts\User_model;

/**
 * Class Account
 *
 * @package App\Models
 */
class Group_model extends Model
{
    protected $intraDB;
    protected $DBTable;


    public function __construct()
    {
        $this->intraDB  = db_connect();
        
    }

    public function getGrouplist( int $depth, int $parent , Array $opt = ['userflag'=>false] ){
        $user_model = new User_model();

        $builder = $this->intraDB->table(DB_T_s_mbrgroup);

        $builder->select('*');
        if( $depth == 0){
            $builder->where("uid",$parent);
        }else{
            $builder->where("depth",$depth);
            $builder->where("puid",$parent);
        }
        $builder->where("useYN",'Y');
        $builder->whereNotIn('uid',[60.78,38,90]);
        $builder->orderBy('gid','asc');
        $res = $builder->get();
        
        $result = $res->getResultArray();
        $datas = [];        
        foreach( $result as $data ){
            
            if( $data['childn'] > 0){
                $data['childn_list'] = $this->getGrouplist( $data['depth']+1 , $data['uid'] , $opt);
            }else{
                $data['childn_list'] = [];
            }
            if( $opt['userflag'] == true ){
                $data['user_list'] = $user_model->getUserList( "m.memberuid,m.name,m.job_det,g.name as gname,l.name as lname", [' m.auth = 1 ', "m.sosok = '".$data['uid']."' " ] );
            }else{
                $data['user_list'] = [];
            }
            $datas[] = $data;
        }

        return $datas;
    }

    public function getGroupOnlyIist( int $puid, int $depth ){
        
        $builder = $this->intraDB->table(DB_T_s_mbrgroup);

        $builder->select('*');
        if( $puid == 0 ){
            $builder->where("depth",$depth);
        }else{
            $builder->where("depth",$depth);
            $builder->where("puid",$puid);
        }
        $builder->where("useYN",'Y');
        $builder->whereNotIn('uid',[60.78,38,90]);
        $builder->orderBy('gid','asc');
        $res = $builder->get();
        
        $result = $res->getResultArray();
        $datas = [];        
        foreach( $result as $data ){
            
            $datas[] = $data;
        }

        return $datas;
    }
    
    public function getGrouplistReverse( int $gno , Array $opt = ['userflag'=>false,'onlyuid'=>false] ){
        $user_model = new User_model();

        $builder = $this->intraDB->table(DB_T_s_mbrgroup);

        $builder->select('*');
        $builder->where("uid",$gno);
        $builder->where("useYN",'Y');
        $builder->whereNotIn('uid',[60.78,38,90]);
        $builder->orderBy('gid','asc');
        $res = $builder->get();
        
        $result = $res->getResultArray();
        $datas = [];        
        foreach( $result as $data ){
            

            if( $opt['onlyuid'] == true){
                $datas[] = $data['uid'];
            }else{                
                $datas[] = $data;
            }


            if( $data['puid'] > 0 ){
                $datas = array_merge($datas,$this->getGrouplistReverse($data['puid'],$opt));
            }
        }

        return $datas;
    }

    public function getGroupSumUserlist( int $depth, int $parent , Array $opt = ['userflag'=>false] ){
        $user_model = new User_model();

        $builder = $this->intraDB->table(DB_T_s_mbrgroup);

        $builder->select('*');
        if( $depth == 0){
            $builder->where("uid",$parent);
        }else{
            $builder->where("depth",$depth);
            $builder->where("puid",$parent);
        }
        $builder->where("useYN",'Y');
        $builder->whereNotIn('uid',[60.78,38,90]);
        $builder->orderBy('gid','asc');
        $res = $builder->get();
        
        $result = $res->getResultArray();
        $user_lists = [];        
        foreach( $result as $data ){
            if( $data['childn'] > 0){
                $child_user_lists = $this->getGroupSumUserlist( $data['depth']+1 , $data['uid'] , $opt);
            }else{
                $child_user_lists = [];
            }

            $guser_lists = $user_model->getUserList( "m.memberuid,m.name,m.job_det,g.name as gname,l.name as lname", [' m.auth = 1 ', "m.sosok = '".$data['uid']."' " ] );
            
            $user_lists = array_merge($guser_lists,$child_user_lists,$user_lists);
        }

        return $user_lists;
    }

    public function getGinfo( int $gno, String $selector, Array $opt = ['ulist_flag'=>false]){
        $builder = $this->intraDB->table(DB_T_s_mbrgroup);
        $builder->select($selector);
        $builder->where('uid',$gno);
        $res = $builder->get();

        $result = $res->getRowArray();

        return $result;
    }

    public function setUnoList( Array $uno_arr = ['p'=>[],'g'=>[]] ){
        $group_ulists = [];
        if( isset($uno_arr['g']) ){
            foreach( $uno_arr['g'] as $gno ){
                $group_lists = $this->getGroupSumUserlist( 0, $gno ,  ['userflag'=>true] );
                foreach( $group_lists as $data ){
                    $group_ulists[] = $data['memberuid'];
                }
            }
        }

        return array_unique(array_merge($group_ulists,$uno_arr['p']));
    }

}