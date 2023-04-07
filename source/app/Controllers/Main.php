<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Common\Main_model;

class Main extends BaseController
{
	public function __construct()
	{

	}

	public function index()
	{

		$main = new Main_model();
		$this->page_cache(5);

		$script_cell = [
							['link'=>'_assets/vendors/OwlCarousel2-2.3.4/owl.carousel.min.js'],
							['link'=>'_assets/js/views/main.js'],
		];
		$css_cell = [
			['link'=>'_assets/vendors/OwlCarousel2-2.3.4/assets/owl.carousel.css']
		];


		$main_datas = $main->getMainData(USER_INFO['memberuid']);

		$view_datas = ['script_cell'=>$script_cell,'css_cell'=>$css_cell,'main_datas'=>$main_datas];
		return $this->View( 'main' , $view_datas );  
	}



	//--------------------------------------------------------------------

}
