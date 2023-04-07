<?php namespace App\Controllers\Common;

use App\Controllers\BaseController;
use App\Models\Common\Attach\Upload_model;
use App\Models\Common\Attach\Info_model;
use App\Models\Common\Attach\Download_model;
use App\Models\Common\Attach\Img_model;
use CodeIgniter\API\ResponseTrait;

class Attach extends BaseController
{
	use ResponseTrait;
	
	public $file_tag = ['기획','디자인','영상','기타'];

	public function initForm(){
		$mode = $this->SiteModel->getParam('mode','','GET',false);
		$view_datas = ['mode'=>$mode];
		return view('common/attach/init_form',$view_datas);
	}

	public function uploadForm()
	{	

		$maxsize = $this->SiteModel->getParam('maxsize','','GET',false);
		$gidx = $this->SiteModel->getParam('gidx','','GET',false);
		$view_datas = [
			'gidx'=>$gidx,
			'maxsize'=>$maxsize,
			'tempcode'=>TEMPCODE
		];
		return view('common/attach/upload_modal',$view_datas);
	}

    public function upload(){
		$upload = new Upload_model();

		
		$file = $this->SiteModel->getParam('upfile','','FILE');
		$add_data = ['tempcode'=>'','uptype'=>'','gidx'=>0];
		$add_data['tempcode'] = $this->SiteModel->getParam('tempcode','','POST');
		$add_data['uptype'] = $this->SiteModel->getParam('uptype','','POST');
		$add_data['gidx'] = $this->SiteModel->getParam('gidx','','POST',false);
		
		if( ! $file->isValid() ){
			return $this->fail('파일 업로드에 실패했습니다. ('.$file->getErrorString().')');
		}

		$result = $upload->upload( $file ,'bbs_attach',$add_data,['path_day_flag'=>true]);

		if( $result > 0 ){
			$this->SiteModel->setResStatus(1,'');
			$this->SiteModel->setResData('fidx',$result);
			return $this->respondCreated($this->SiteModel->response_data,200);
		}

	}

	public function uploadeditor(){
		$upload = new Upload_model();
		$info = new Info_model();

		$file = $this->SiteModel->getParam('upload','','FILE');
		$add_data = ['tempcode'=>'','uptype'=>'','gidx'=>0];
		$add_data['tempcode'] = "";
		$add_data['uptype'] = "editor";
		$add_data['gidx'] = 0;
		
		if( ! $file->isValid() ){
			$return = [];
			return json_encode([]);
		}

		$result = $upload->upload( $file ,'bbs_attach',$add_data,['path_day_flag'=>true]);

		if( $result > 0 ){
			$finfo = $info->getFileInfo($result);
			
			if( ENVIRONMENT == 'development'){
				$file_url = IMG_URL_DEV.$finfo['folder']."/".$finfo['tmpname'];
			}else{
				$file_url = IMG_URL.$finfo['folder']."/".$finfo['tmpname'];
			}
			$return = ['url'=>$file_url];
			return $this->respond($return, 200);
		}
	}

	public function viewimg(){
		$img = new Img_model();
		$full_path = $this->request->getServer('REQUEST_URI');

		$img_path = $img->setPath($full_path);
		
		$view_data['img_path'] = $img_path;
		$view_data['Content-type']= $img->getExtension($img_path);

		$seconds_to_cache = 7200; //이미지 캐쉬 2시간
		$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
		$mime = mime_content_type($img_path); //<-- detect file type
		header("Expires: $ts");
		header("Pragma: cache");
		header("Cache-Control: max-age=$seconds_to_cache");
		header('Content-Length: '.filesize($img_path)); //<-- sends filesize header
		header("Content-Type: ".$mime.""); //<-- send mime-type header
		readfile($img_path); //<--reads and outputs the file onto the output buffer
		die();
		exit;
	}
	
	public function list(){
		$info = new Info_model();
		
		$fnos = $this->SiteModel->getParam('fnos','','POST',false);
		$mode = $this->SiteModel->getParam('mode','','POST',false);
		
		$attdatas = [];

		if( $fnos ){
			$fnos_arr = explode(",",$fnos);
			$fnos_arr_re = [];
			foreach( $fnos_arr as $ftmp ){
				if( $ftmp && $ftmp > 0 ){
					$fnos_arr_re[] = $ftmp;
				}
			}
			$attdatas_tmp = $info->getFdatas($fnos_arr);

			foreach( $attdatas_tmp as $attdata ){
				$attdatas[$attdata['gidx']][] = $attdata;
			}

		}
		

		$template = $info->makeTpl($attdatas,$mode);
		
		$this->SiteModel->setResStatus(1,'');
		$this->SiteModel->setResData('tpl',$template);
		return $this->respondCreated($this->SiteModel->response_data,200);
	}

	public function download( int $fuid ){
		$down = new Download_model();
		$info = new Info_model();

		$finfo = $info->getFileInfo($fuid);
		$uno = USER_INFO['memberuid'];
		if( !isset($finfo['uid']) ){			
            return $this->SiteModel->getReturn( $this->request->isAJAX() ,'direct_move','존재하지 않는 파일입니다..',['url'=>'/errors/500/60001']);
		}


		$check = $info->checkPermission( $uno, $finfo );
		if( $check == false ){
            return $this->SiteModel->getReturn( $this->request->isAJAX() ,'direct_move','파일 다운로드권한이 없습니다..',['url'=>'/errors/500/60002']);
		}

        if( ENVIRONMENT == 'development'){
			$file_path = FILE_PATH_DEV.$finfo['folder']."/".$finfo['tmpname'];
        }else{
			$file_path = FILE_PATH.$finfo['folder']."/".$finfo['tmpname'];
		}
		
		return $this->response->download($file_path,null)->setFileName($finfo['name']);

	}

	public function desceditForm(){
		$info = new Info_model();

		$fuid = $this->SiteModel->getParam('fuid','','GET',false);	
		$finfo = $info->getFileInfo($fuid);
		
		if( !isset($finfo['uid']) ){			
            return $this->fail('존재하지 않는 파일입니다.');
		}

		$view_datas = [
			'finfo'=>$finfo,
			'ftaglists'=>$this->file_tag,

		];
		return view('common/attach/edit_modal.php',$view_datas);
	}

	public function desceditProc(){
		$info = new Info_model();

		$fuid = $this->SiteModel->getParam('fuid','','POST',false);	
		$f_tag = $this->SiteModel->getParam('f_tag','','POST',false);	
		$f_version = $this->SiteModel->getParam('f_version','','POST',false);	
		
		$setData = [
			'caption' => $f_tag,
			'version' => $f_version
		];
		$result = $info->editInfo( $fuid, $setData );

		if( $result ){				
			$this->SiteModel->setResStatus(1,'');
			return $this->respondCreated($this->SiteModel->response_data,200);
		}else{
			return $this->fail('수정도중 오류가 발생했습니다.');
		}


	}
}
