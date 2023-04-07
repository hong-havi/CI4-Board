<?php namespace App\Models\Common\Attach;

use CodeIgniter\Model;
use App\Models\Common\Attach\Info_model;

/**
 * Class Account
 *
 * @package App\Models
 */
class Upload_model extends Model
{

    private $File;
    private $intraDB;
    
    public function __construct()
    {
        $this->intraDB  = db_connect();
    }

    public function upload( Object $File , String $path = "uploads", Array $add_data=[], Array $opt = ['path_day_flag'=>false,'name_enc'=>true]){
        $this->File = $File;

        if( ENVIRONMENT == 'development'){
            $save_path = $path.'/';
        }else{
            $save_path = FILE_PATH.$path.'/';
        }

        if( $opt['path_day_flag'] == true ){
            $save_path = $save_path.date("Y").'/';
        }

        $file_path = $this->File->store($save_path);
        if( $file_path ){
            return $this->setDB( $save_path, $file_path, $add_data );
        }else{
            return false;
        }
    }

    private function setDB( $save_path, $file_path, $add_data ){
        
        $uno = USER_INFO['memberuid'];
        $gidx = 0;
        $version = '0.1';
        $ext = $this->File->getClientExtension();
        $file_type = $this->fileType($ext);
        $file_path_arr = explode("/",$file_path);
        $tmpname = $file_path_arr[(count($file_path_arr)-1)];

        if( substr($save_path,-1) == '/' ){
            $save_path = substr($save_path,0,-1);
        }

        if( $add_data['gidx'] > 0 ){
            $info = new Info_model();
            $ginfo = $info->getFileInfogidx($add_data['gidx']);
            $add_data['uptype'] = $ginfo['uptype'];
            $gidx = $ginfo['gidx'];
            $gversion_arr = explode(".",$ginfo['version']);
            $version = ($gversion_arr[0]+1).".0";
        }

        $date = date("YmdHis");

        $data = [
            'gid'       => 0,
            'hidden'    => 0,
            'gidx'      => $gidx,
            'del'       => 'N',
            'tmpcode'   => $add_data['tempcode'],
            'uptype'   => $add_data['uptype'],
            'site'      => '1',
            'mbruid'    => $uno,
            'type'      => $file_type,
            'ext'       => $ext,
            'url'       => FILE_URL,
            'folder'    => $save_path,
            'name'      => $this->File->getClientName(),
            'tmpname'   => $tmpname,
            'thumbname' => '',
            'size'      => $this->File->getSize(),
            'width'     => 0,
            'height'    => 0,
            'caption'   => $this->fileTypeToTag($ext),
            'version'   => $version,
            'down'      => 0,
            'd_regis'   => $date
        ];

        $builder = $this->intraDB->table(DB_T_s_upload);
        $builder->insert($data);
        $fidx = $this->intraDB->insertID();

        if( $gidx == 0 ){
            $gidx = ($add_data['gidx'] > 0 ) ? $add_data['gidx']  : $fidx;
            $this->setDBpidx($fidx,$gidx);
        }

        return $fidx;
    }

    //생성후 그룹 키 먹이기
    private function setDBpidx( int $fidx, int $gidx){
        $builder = $this->intraDB->table(DB_T_s_upload);
        $builder->set('gidx',$gidx);
        $builder->where('uid',$fidx);
        $builder->update();
    }

    private function fileType($ext){
        if (strpos('_gif,jpg,jpeg,png,bmp,',strtolower($ext))) return 2;
        if (strpos('_swf,',strtolower($ext))) return 3;
        if (strpos('_mid,wav,mp3,',strtolower($ext))) return 4;
        if (strpos('_asf,asx,avi,mpg,mpeg,wmv,wma,mov,flv,',strtolower($ext))) return 5;
        if (strpos('_doc,xls,ppt,hwp',strtolower($ext))) return 6;
        if (strpos('_zip,tar,gz,tgz,alz,',strtolower($ext))) return 7;
        return 1;
    }

    private function fileTypeToTag( $ext ){
        if (strpos('_gif,jpg,jpeg,png,bmp,',strtolower($ext))) return '디자인';
        if (strpos('_swf,',strtolower($ext))) return '영상';
        if (strpos('_mid,wav,mp3,',strtolower($ext))) return '영상';
        if (strpos('_asf,asx,avi,mpg,mpeg,wmv,wma,mov,flv,',strtolower($ext))) return '영상';
        if (strpos('_xls,pdf,xlsx',strtolower($ext))) return '기타';
        if (strpos('_ppt,pptx,hwp,doc',strtolower($ext))) return '기획';
        if (strpos('_zip,tar,gz,tgz,alz,',strtolower($ext))) return '기타';
        return '';

    }

    
}