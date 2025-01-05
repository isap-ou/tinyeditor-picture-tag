<?php declare(strict_types=1);

namespace Isapp\FilamentFormsTinyeditorPictureTag\Processors;

use Isapp\FilamentFormsTinyeditorPictureTag\Contracts\TinyeditorPictureTag;

class NonTranslatableProcessor extends BaseProcessor
{
    public function convert(TinyeditorPictureTag $model): void
    {
        foreach ($model->pictureTags as $pictureTag) {
            $content = $model->{$pictureTag->field};
            if (empty($content) || $model->isDirty($pictureTag->field) === false) {
                continue;
            }

            $model->{$pictureTag->field} = $this->process($content, $model);
        }

        $model->saveQuietly();
    }
}
