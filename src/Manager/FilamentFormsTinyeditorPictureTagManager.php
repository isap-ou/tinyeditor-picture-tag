<?php

namespace Isapp\FilamentFormsTinyeditorPictureTag\Manager;

use Illuminate\Support\Manager;
use Isapp\FilamentFormsTinyeditorPictureTag\Processors\BaseProcessor;
use Isapp\FilamentFormsTinyeditorPictureTag\Processors\NonTranslatableProcessor;
use Isapp\FilamentFormsTinyeditorPictureTag\Processors\TranslatableProcessor;
use PHPHtmlParser\Dom;

class FilamentFormsTinyeditorPictureTagManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return $this->config->get('filament-forms-tinyeditor-picture-tag.driver');
    }

    protected function createNonTranslatableDriver(): BaseProcessor
    {
        return new NonTranslatableProcessor(new Dom());
    }

    protected function createTranslatableDriver(): BaseProcessor
    {
        return new TranslatableProcessor(new Dom());
    }
}
