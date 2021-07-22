<?php

namespace App\Providers;

use App\Repositories\Media\MediaInterface;
use App\Repositories\Media\MediaRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(MediaInterface::class, MediaRepository::class);
    }
}
