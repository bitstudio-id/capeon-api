<?php
namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ModuleMakeCommand extends Command
{
	protected $signature = 'module:make {module} {--c=}';

	protected $description = "create new module";
}