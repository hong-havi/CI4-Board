<?php namespace App\Models\Common\Attach;

use CodeIgniter\Model;


/**
 * Class Account
 *
 * @package App\Models
 */
class Img_model extends Model
{
    public $noImg = "";
    
    public function __construct( )
    {
    }

    public function setPath( String $path ){
        
        $check = $this->checkPermission();

        if( $check == false ){
            return $this->noImg;
        }

		$file_path = str_replace("/common/attach/viewimg/","",$path);
        
        
		if( ENVIRONMENT == 'development'){
			$file_path = FILE_PATH_DEV.$file_path;
		}else{
			$file_path = FILE_PATH.$file_path;
		}
        
        $ischeck = $this->checkIsfile($file_path);

        if( $ischeck == false ){
            return $this->noImg;
        }

        return $file_path;
    }
   
    public function checkPermission(){
        $request = \Config\Services::request();

        if( in_array($request->getServer('REMOTE_ADDR'),DEV_IP_ARR) ){
            return true;
        }

        $check_domain = array('intra.sjwcorp.kr','file.sjwcorp.kr','crm.sjwcorp.kr','dcrm.sjwcorp.kr','recruit.siwonschool.com');
        $REFERER = ($request->getServer('HTTP_REFERER')) ? $request->getServer('HTTP_REFERER') : "";
        if( !$REFERER ){
            return false;
        }

        $sslflag = false;
        $httpflag = false;

        preg_match("/(https:\/\/)?([a-zA-Z0-9_-]+.[a-zA-Z0-9_.-]+)/ ", $REFERER, $match);
        $ref_url = $match[2];
        if( in_array($ref_url,$check_domain) ){
            $sslflag = true;
        }
        
        preg_match("/(http:\/\/)?([a-zA-Z0-9_-]+.[a-zA-Z0-9_.-]+)/ ", $REFERER, $match);
        $ref_url = $match[2];
        if( in_array($ref_url,$check_domain) ){
            $httpflag = true;
        }
        
        if( $sslflag == false && $httpflag == false ){
            return false;
        }

        
        return true;
    }

    public function checkIsfile( String $file_path){
        if( is_file($file_path) ){
            return true;
        }else{
            return false;
        }
    }

    public function getExtension( String $file_path ){
        $FileInfo = pathinfo($file_path);
        
        $EXT = strtolower($FileInfo['extension']);
        switch( $EXT ){
            case 'jpeg' :
                return "image/jpeg";
                break;
            case 'jpg' :
                return "image/jpg";
                break;
            case 'gif' :
                return "image/gif";
                break;
            case 'png' :
                return "image/png";
                break;
            default :
                return "image/png";
                break;
        }
    }
}