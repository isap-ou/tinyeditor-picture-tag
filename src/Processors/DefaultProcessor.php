<?php

declare(strict_types=1);

namespace Isapp\TinyeditorPictureTag\Processors;

use Isapp\TinyeditorPictureTag\Contracts\HasTinyeditorPictureTag;

class DefaultProcessor extends Processor
{
    public function convert(HasTinyeditorPictureTag $model, string $field): void
    {
        $content = $model->{$field};
        if (empty($content) || $model->isDirty($field) === false) {
            return;
        }

        $model->{$field} = $this->process($content, $model, $model->getTinyeditorFields()[$field]);

        $model->saveQuietly();
    }
}
