<?php declare(strict_types=1);

namespace Isapp\FilamentFormsTinyeditorPictureTag\Observers;

use Isapp\FilamentFormsTinyeditorPictureTag\Contracts\TinyeditorPictureTag;
use Isapp\FilamentFormsTinyeditorPictureTag\Manager\FilamentFormsTinyeditorPictureTagManager;

class TinyeditorPictureTagObserver
{
    /**
     * Handle the "created" event.
     */
    public function created(TinyeditorPictureTag $model): void
    {
        app(FilamentFormsTinyeditorPictureTagManager::class)->driver($model->getPictureTagDriver())->convert($model);
    }

    /**
     * Handle the "updated" event.
     */
    public function updated(TinyeditorPictureTag $model): void
    {
        app(FilamentFormsTinyeditorPictureTagManager::class)->driver($model->getPictureTagDriver())->convert($model);
    }

}
