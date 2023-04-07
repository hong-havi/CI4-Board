<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
	// Makes reading things below nicer,
	// and simpler to change out script that's used.
	public $aliases = [
		'csrf'     		=> \CodeIgniter\Filters\CSRF::class,
		'toolbar'  		=> \CodeIgniter\Filters\DebugToolbar::class,
		'honeypot' 		=> \CodeIgniter\Filters\Honeypot::class,

		'auth' 			=> \App\Filters\Auth::class,
		'ip' 			=> \App\Filters\Ip::class,

		'Board_check' 	=> \App\Filters\Board_check::class,
		'Menuinfo'		=> \App\Filters\Menuinfo::class

	];

	// Always applied before every request
	public $globals = [
		'before' => [
			'auth' => ['except'=>['_assets/*','img/*','cron/*']],
			'ip' => ['except'=>['cron/*']]
			//'honeypot'
			// 'csrf',
		],
		'after'  => [
			'toolbar',
			//'honeypot'
		],
	];

	// Works on all of a particular HTTP method
	// (GET, POST, etc) as BEFORE filters only
	//     like: 'post' => ['CSRF', 'throttle'],
	public $methods = [];

	// List filter aliases and any before/after uri patterns
	// that they should run on, like:
	//    'isLoggedIn' => ['before' => ['account/*', 'profiles/*']],
	public $filters = [
		'Menuinfo' => ['before'=>['site/*']],
		'Board_check' => ['before'=>['site/*/bbs','site/*/bbs/*']]
	];
}
