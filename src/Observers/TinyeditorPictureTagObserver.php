<?php declare(strict_types=1);

namespace Isapp\FilamentFormsTinyeditorPictureTag\Observers;

use Isapp\FilamentFormsTinyeditorPictureTag\Contracts\TinyeditorPictureTagProvider;
use Isapp\FilamentFormsTinyeditorPictureTag\Facades\TinyeditorPictureTag;

class TinyeditorPictureTagObserver
{
    /**
     * Handle the "created" event.
     */
    public function created(TinyeditorPictureTagProvider $model): void
    {
        TinyeditorPictureTag::driver($model->getProvider())->convert($model);
    }

    /**
     * Handle the "updated" event.
     */
    public function updated(TinyeditorPictureTagProvider $model): void
    {
        TinyeditorPictureTag::driver($model->getProvider())->convert($model);
    }

}
