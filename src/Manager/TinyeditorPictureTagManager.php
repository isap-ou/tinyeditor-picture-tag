<?php

namespace Isapp\TinyeditorPictureTag\Manager;

use Illuminate\Support\Manager;
use Isapp\TinyeditorPictureTag\Contracts\ProcessorContract;
use Isapp\TinyeditorPictureTag\Processors\DefaultProcessor;
use Isapp\TinyeditorPictureTag\Processors\TranslatableProcessor;

class TinyeditorPictureTagManager extends Manager
{
    final public function getDefaultDriver(): string
    {
        return 'default';
    }

    protected function createDefaultDriver(): ProcessorContract
    {
        return new DefaultProcessor;
    }

    protected function createTranslatableDriver(): ProcessorContract
    {
        return new TranslatableProcessor;
    }
}
