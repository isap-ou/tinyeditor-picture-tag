<?php declare(strict_types=1);

namespace Isapp\FilamentFormsTinyeditorPictureTag\Facades;
use Illuminate\Support\Facades\Facade;
use Isapp\FilamentFormsTinyeditorPictureTag\Manager\FilamentFormsTinyeditorPictureTagManager;

class TinyeditorPictureTag extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FilamentFormsTinyeditorPictureTagManager::class;
    }
}
