<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes(true);

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Main');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 * 89349112
 * 
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Main::index');

$routes->get('errors/(:any)', 'Errors::index/$1/$2');

$routes->group('api', function($routes){

});

$routes->get("img/(:any)",'Common/Img');

$routes->group('common', function($routes)
{
	$routes->get('postnum', 'Common\Postnum::search');

	$routes->get('profilepop/(:num)','Accounts\User::Profile/$1');
	$routes->post('searchpeople','Accounts\User::SearchPeople');

	$routes->group('sender', function($routes){
		$routes->get('find','Common\Sender::findform');
		$routes->get('list','Common\Sender::list');
		$routes->post('add','Common\Sender::add');
	});

	$routes->group('attach', function($routes){
		$routes->get('initform','Common\Attach::initForm');
		$routes->get('upload','Common\Attach::uploadForm');
		$routes->post('upload','Common\Attach::upload');
		$routes->post('uploadeditor','Common\Attach::uploadeditor');
		$routes->post('download/(:num)','Common\Attach::download/$1');
		$routes->get('viewimg/(:any)','Common\Attach::viewimg/$1');
		
		$routes->post('list','Common\Attach::list');

		$routes->get('descedit','Common\Attach::desceditForm');
		$routes->post('descedit','Common\Attach::desceditProc');
	});

});

$routes->group('accounts', function($routes)
{
	$routes->get('login', 'Accounts\Login::index');
	$routes->post('login', 'Accounts\Login::procLogin');
		
	$routes->get('register', 'Accounts\Register::index');
	$routes->post('register', 'Accounts\Register::setRegister');

	$routes->post('cnumber', 'Accounts\Login::procSendnumber');

	$routes->get('logout', 'Accounts\Login::procLogout');
});

$routes->group('mypage', function($routes)
{
	$routes->get('info','Mypage\Info::info');
	$routes->group('paper',function($routes){
		$routes->get('','Mypage\Paper::list');
		$routes->get('list','Mypage\Paper::lists');
		$routes->get('view','Mypage\Paper::views');
		$routes->get('write','Mypage\Paper::write');
	});

	$routes->group('favorit', function($routes){
		$routes->post('proc','Mypage\Favorit::proc');
	});
});

$routes->group('info', function($routes)
{
	$routes->group('user', function($routes)
	{
		$routes->post('list','Info\User::list');
		$routes->post('listmention','Info\User::listmention');
	});
	$routes->group('group', function($routes)
	{
		$routes->get('list','Info\Group::lists');
	});
});


$routes->group('site', function($routes){
	$routes->group('(:num)', function($routes){
		$routes->group('bbs',function($routes){
			$routes->get('','Board\Board::blist');
			$routes->get('list','Board\Board::blist');
			$routes->get('write','Board\Board::bwrite');
			$routes->get('write/(:num)','Board\Board::bmodify/$2');
			$routes->get('view/(:num)','Board\Board::bview/$2');
			
			$routes->group('proc',function($routes){
				$routes->post('write','Board\Proc::write');
				$routes->post('modify','Board\Proc::modify');
				$routes->post('delete','Board\Proc::delete');
			});
		});
		
		$routes->group('comment',function($routes){
			$routes->get('','Comment\Comment::init');		
			$routes->get('replay','Comment\Comment::replayForm');
			$routes->get('modify','Comment\Comment::modifyForm');
			$routes->get('list','Comment\Comment::list');
			$routes->post('write','Comment\Comment::write_proc');
			$routes->post('reply','Comment\Comment::reply_proc');	
			$routes->post('delete','Comment\Comment::delete_proc');	
			$routes->post('modify','Comment\Comment::modify_proc');	
			
		});

		$routes->group('workspace',function($routes){
			$routes->get('','Workspace\Workspace::lists');
			$routes->get('list','Workspace\Workspace::lists');
			$routes->get('write','Workspace\Workspace::write');
			$routes->get('write/(:num)','Workspace\Workspace::modify/$2');
			$routes->get('view/(:num)','Workspace\Workspace::views/$2');
			
			$routes->get('getPwtype','Workspace\Workspace::getPwtype');
			$routes->get('findWork','Workspace\Workspace::formFindwork');
			$routes->get('loglist_modal','Workspace\Workspace::loglist');
			$routes->get('status','Workspace\Status::views');
			$routes->group('proc',function($routes){
				$routes->post('write','Workspace\Proc::write');
				$routes->post('modify','Workspace\Proc::modify');
				$routes->post('delete','Workspace\Proc::delete');
				$routes->post('infosave','Workspace\Proc::infosave');
				$routes->post('setFile','Workspace\Proc::setFile');
				$routes->post('favorit','Workspace\Proc::favorit');
			});

			$routes->group('worker',function($routes){
				$routes->get('addform','Workspace\Worker::addform');
				$routes->get('timeform','Workspace\Worker::timeform');
				$routes->get('list','Workspace\Worker::wlist');

				$routes->post('add','Workspace\Worker::add');
				$routes->post('savetime','Workspace\Worker::savetime');
				$routes->post('delete','Workspace\Worker::wdelete');
				$routes->post('modify','Workspace\Worker::wmodify');
			});
			
			$routes->group('dashboard',function($routes){
				$routes->get('','Workspace\Dashboard::views/my');
				$routes->get('view','Workspace\Dashboard::views/my');
				$routes->get('view/(:any)','Workspace\Dashboard::views/$2');

				$routes->get('getDList','Workspace\Dashboard::getDList');
				$routes->get('getHistory','Workspace\Dashboard::getHistory');
				$routes->post('getCalendar','Workspace\Dashboard::getCalendar');
				$routes->post('findPeople','Workspace\Dashboard::findPeople');
			});

		});
	});
	
});

$routes->group('cron', function($routes)
{
	$routes->cli('daily/(:any)','Cron\Daily::index/$1'); //1일 1회

	$routes->cli('layout/main','Cron\Layout::setMaindata'); //10분 간격
});
/*
$routes->group('testing',function($routes){
	$routes->get('setting','_Testing\Setting::index');
});
*/
/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
