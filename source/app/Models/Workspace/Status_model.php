<?php namespace App\Models\Workspace;

use CodeIgniter\Model;
use App\Models\Workspace\Workspace_model;

/**
 * Class Account
 *
 * @package App\Models
 */
class Status_model extends Model
{
    protected $intraDB;


    public $workspace_lists;

    public function __construct()
    {
        $this->intraDB  = db_connect();        
    }

    public function getStatus_group( Int $sec_year, Int $sec_month, String $group_by){
       
        $builder = $this->intraDB->table(DB_T_wp_plist.' as p');
        $builder->where('substr(p.reg_date,1,7)',($sec_year."-".(sprintf("%02d",$sec_month))));


        $selector = "p_type,
        usosok,
        cate1,
        COUNT(pj_idx) AS all_cnt,
        COUNT(IF( state = 7, 1 ,NULL)) AS end_cnt,
        SUM( 
            IF( 
                start_date != '0000-00-00' AND end_date != '0000-00-00' AND due_date != '0000-00-00' ,
                IF( 
                    end_date = '0000-00-00', 
                    DATEDIFF(due_date,start_date), 
                    DATEDIFF(end_date,start_date)
                ), 
            0)
        ) AS work_day,
        (SELECT SUM(wt_time)/60 FROM ".DB_T_wp_time." WHERE pj_idx = p.pj_idx ) AS work_time";
        $builder->select($selector);
        $builder->groupBy($group_by);        
        $res = $builder->get();

        $datas = $res->getResultArray();

        return $datas;
    }

    public function makeDatas( Array $datas ){
        $defData = ['all_cnt' => 0,'end_cnt' => 0,'work_day' => 0,'work_time' => 0,'cnt_per'=>0];

        $total = ['1'=>$defData,'2'=>$defData,'3'=>$defData];
        $retdata = ['datas'=>[],'total'=>[]];
        foreach( $datas as $data ){

            $data['cnt_per'] = floor(($data['end_cnt']/$data['all_cnt'])*100);

            if( !isset($retdata[$data['usosok']]) ){                
                $retdata['datas'][$data['usosok']]['datas'] = ['1'=>$defData,'2'=>$defData,'3'=>$defData];
                $retdata['datas'][$data['usosok']]['info']['name'] = $data['cate1'];
            }

            $retdata['datas'][$data['usosok']]['datas'][$data['p_type']] = $data;

            $total[$data['p_type']] = [
                'all_cnt'   => $total[$data['p_type']]['all_cnt']+$data['all_cnt'],
                'end_cnt'   => $total[$data['p_type']]['end_cnt']+$data['end_cnt'],
                'work_day'  => $total[$data['p_type']]['work_day']+$data['work_day'],
                'work_time' => $total[$data['p_type']]['work_time']+$data['work_time'],
            ];
        }

        $retdata['total'] = $total;
        return $retdata;
    }

}