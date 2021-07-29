<?php
namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SwaggerScanCommand extends Command
{
	protected $signature = 'swagger:scan';

	protected $description = "swagger scan";

	public function handle()
	{
		$path = dirname(dirname(dirname(__DIR__)));

		$outputPath = dirname(dirname(dirname(__DIR__))). DIRECTORY_SEPARATOR . "public\swagger.json";

		$this->info('scanning ' . $path);

		$openApi = \OpenApi\Generator::scan([$path. DIRECTORY_SEPARATOR . "app", $path. DIRECTORY_SEPARATOR . "modules"]);
		header("Content-Type: application/json");

		file_put_contents($outputPath, $openApi->toJson());

		$this->info('output '. $outputPath);
	}

}