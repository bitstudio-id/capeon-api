<?php

namespace Base;

use Illuminate\Support\ServiceProvider;
use Modules\Media\Repositories\MediaInterface;
use Modules\Media\Repositories\MediaRepository;

class BaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(MediaInterface::class, MediaRepository::class);
    }
}
