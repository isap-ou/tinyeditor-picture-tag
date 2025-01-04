<?php declare(strict_types=1);

namespace Isapp\FilamentFormsTinyeditorPictureTag\Services;

use Isapp\FilamentFormsTinyeditorPictureTag\Contracts\TinyeditorPictureTagConverter;
use Isapp\FilamentFormsTinyeditorPictureTag\Actions\ProcessPictureTag;
use Spatie\MediaLibrary\HasMedia;

class TranslatableEditorConverter implements TinyeditorPictureTagConverter
{
    public function __construct(
        protected ProcessPictureTag $processPictureTag
    ) {}

    public function convert(HasMedia $model): void
    {
        if (!method_exists($model, 'getTranslations')) {
            return;
        }

        $fieldsToProcess = config('filament-forms-tinyeditor-picture-tag.processed_fields', []);
        if (empty($fieldsToProcess)) {
            return;
        }

        foreach ($fieldsToProcess as $field) {
            if ($model->isDirty($field) === false) {
                continue;
            }

            $translations = $model->getTranslations($field);

            foreach ($translations as $locale => $content) {
                if (!empty($content)) {
                    $model->setTranslation($field, $locale, $this->processPictureTag->process($content, $model));
                }
            }
        }

        $model->saveQuietly();
    }
}
