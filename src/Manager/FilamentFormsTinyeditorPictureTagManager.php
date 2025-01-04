<?php

namespace Isapp\FilamentFormsTinyeditorPictureTag\Manager;

use Illuminate\Support\Manager;
use Isapp\FilamentFormsTinyeditorPictureTag\Actions\ProcessPictureTag;
use Isapp\FilamentFormsTinyeditorPictureTag\Contracts\TinyeditorPictureTagConverter;
use Isapp\FilamentFormsTinyeditorPictureTag\Services\DefaultEditorConverter;
use Isapp\FilamentFormsTinyeditorPictureTag\Services\TranslatableEditorConverter;
use PHPHtmlParser\Dom;

class FilamentFormsTinyeditorPictureTagManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return $this->config->get('filament-forms-tinyeditor-picture-tag.driver');
    }

    protected function createNonTranslatableDriver(): TinyeditorPictureTagConverter
    {
        return new DefaultEditorConverter(
            new ProcessPictureTag(new Dom())
        );
    }

    protected function createTranslatableDriver(): TinyeditorPictureTagConverter
    {
        return new TranslatableEditorConverter(
            new ProcessPictureTag(new Dom())
        );
    }
}
