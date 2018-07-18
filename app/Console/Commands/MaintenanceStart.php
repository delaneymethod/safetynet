<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */
 
namespace App\Console\Commands;

use Illuminate\Console\Command;

class MaintenanceStart extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'maintenance:start';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Put the application into maintenance mode';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		touch(storage_path('framework').DIRECTORY_SEPARATOR.'down');
		
		$this->warn('Application is now in maintenance mode.');
	}
}
