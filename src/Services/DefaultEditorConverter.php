<?php declare(strict_types=1);

namespace Isapp\FilamentFormsTinyeditorPictureTag\Services;

use Isapp\FilamentFormsTinyeditorPictureTag\Actions\ProcessPictureTag;
use Isapp\FilamentFormsTinyeditorPictureTag\Contracts\TinyeditorPictureTagConverter;
use Isapp\FilamentFormsTinyeditorPictureTag\Contracts\TinyeditorPictureTagProvider;

class DefaultEditorConverter implements TinyeditorPictureTagConverter
{
    public function __construct(
        protected ProcessPictureTag $processPictureTag
    ) {}

    public function convert(TinyeditorPictureTagProvider $model): void
    {
        foreach (config('filament-forms-tinyeditor-picture-tag.processed_fields', []) as $convertedField) {
            $content = $model->{$convertedField};
            if (empty($content) || $model->isDirty($convertedField) === false) {
                continue;
            }

            $model->{$convertedField} = $this->processPictureTag->process($content, $model);
        }

        $model->saveQuietly();
    }
}
