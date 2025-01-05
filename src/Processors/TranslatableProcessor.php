<?php declare(strict_types=1);

namespace Isapp\FilamentFormsTinyeditorPictureTag\Processors;

use Isapp\FilamentFormsTinyeditorPictureTag\Contracts\TinyeditorPictureTag;

class TranslatableProcessor extends BaseProcessor
{
    public function convert(TinyeditorPictureTag $model): void
    {
        if (!method_exists($model, 'getTranslations')) {
            return;
        }

        $fieldsToProcess = $model->pictureTags;
        if (empty($fieldsToProcess)) {
            return;
        }

        foreach ($fieldsToProcess as $field) {
            if ($model->isDirty($field->field) === false) {
                continue;
            }

            $translations = $model->getTranslations($field->field);

            foreach ($translations as $locale => $content) {
                if (!empty($content)) {
                    $model->setTranslation($field->field, $locale, $this->process($content, $model));
                }
            }
        }

        $model->saveQuietly();
    }
}
