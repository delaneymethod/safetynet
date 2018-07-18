<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */
 
namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class DbImport extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'db:import {--path= : The path to the .sql file}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Imports data';

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
		$path = $this->option('path');
		
		if (!empty($path)) {
			return DB::unprepared(file_get_contents($path)); 
		}
		
		return DB::unprepared(file_get_contents(database_path('data').DIRECTORY_SEPARATOR.'general.sql')); 
	}
}
