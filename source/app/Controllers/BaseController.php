<?php
namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */



use CodeIgniter\Controller;

use App\Models\Common as Common_model;
use CodeIgniter\I18n\Time;

class BaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */


	protected $helpers = [];

	protected $session = [];
	protected $cache = [];
	protected $uri = [];

	protected $SiteModel = [];

	protected $api = [];

	protected $nowtime;

	protected $page_type = "PAGE";

	protected $User = ['flag'=>false,'info'=>[]];

	protected $layout_Thema = "coreui";

	protected $tempcode = '';

	
	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
		

		
		$this->uri = service('uri');
		$this->cache = \Config\Services::cache();

		if( $this->request->isAJAX() ){
			$this->page_type = 'AJAX';
		}

		$this->SiteModel = new Common_model\SiteModel();

		$this->nowtime = Time::now();
	}

	public function View( String $view_file, Array $datas = [] ){
		
		helper('html');
		helper('formfill');
		helper('vdatacheck');
		
		$view = \Config\Services::renderer();
		$Layout = new Common_model\Layout_model();

		$Layout_var = ['css'=>"",'script'=>""];

		if( count($datas) > 0){
			foreach( $datas as $key => $val ){
				switch( $key ){
					case 'script_cell' :
						$Layout_var['script'] = $Layout->getJSscript($val);
						break;
					case 'css_cell' :						
						$Layout_var['css'] = $Layout->getCss($val);
						break;
					default : 
						$view->setVar($key,$val);
						break;
				}
			}
		}
		
		$Breadcrumb = (isset($datas['Breadcrumb'])) ? $this->SiteModel->getParam('Breadcrumb',$datas['Breadcrumb'],'FIELD',false,"" ) : "";
		$view->setVar( 'Breadcrumb' , $Breadcrumb );

		
		$BredTab = (isset($datas['BredTab'])) ? $this->SiteModel->getParam('BredTab',$datas['BredTab'],'FIELD',false,[] ) : [];
		$view->setVar( 'BredTab', $BredTab);
		
		$Container = ( isset($datas['Container']['class']) ) ? $this->SiteModel->getParam('Container',$datas['Container']['class'],'FIELD',false,'container-fluid' ) : 'container-fluid';
		$view->setVar('Container',$Container); //가로폭 조정
		
		$Container_width = ( isset($datas['Container']['width']) ) ? $this->SiteModel->getParam('Container',$datas['Container']['width'],'FIELD',false,'container-fluid' ) : 'container-fluid';
		$view->setVar('Container_width',$Container_width); //가로폭 조정


		$view->setVar('layout',$Layout_var);


		if( USER_FLAG == true ){
			$menu_tpl = $Layout->getMenu(USER_INFO['memberuid']);
			$view->setVar('SideMenu',$menu_tpl['sidemenu']);
			$view->setVar('SiteLink',$menu_tpl['sitelink']);
		}

		if( USER_FLAG == 'true' ){
			$view->setVar('user',USER_INFO);
		}
		
		$view->setVar('layout_Thema',$this->layout_Thema);


		return $view->render($view_file);
	}

	public function page_cache( Int $sec = 0 ){
		if( USER_INFO['tflag'] == 'N'){
			$this->cachePage($sec);
		}
	}
}
