<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */
 
namespace App\Console\Commands;

use Illuminate\Console\Command;

class MaintenanceStop extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'maintenance:stop';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Bring the application out of maintenance mode';

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
		if (file_exists(storage_path('framework').DIRECTORY_SEPARATOR.'down')) {
			unlink(storage_path('framework').DIRECTORY_SEPARATOR.'down');
			
			$this->info('Application is now live.');
		}
	}
}
