<?php

declare(strict_types=1);

namespace Isapp\TinyeditorPictureTag\Processors;

use Isapp\TinyeditorPictureTag\Contracts\HasTinyeditorPictureTag;

class TranslatableProcessor extends Processor
{
    /**
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\UnknownChildTypeException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function convert(HasTinyeditorPictureTag $model, string $field): void
    {
        if (! method_exists($model, 'getTranslations')) {
            return;
        }

        if ($model->isDirty($field) === false) {
            return;
        }

        $translations = $model->getTranslations($field);

        foreach ($translations as $locale => $content) {
            if (! empty($content)) {
                $model->setTranslation($field, $locale, $this->process($content, $model, $model->getTinyeditorFields()[$field]));
            }
        }

        $model->saveQuietly();
    }
}
