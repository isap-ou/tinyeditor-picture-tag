<?php

namespace Isapp\TinyeditorPictureTag\Contracts;

use Isapp\TinyeditorPictureTag\PictureTag\PictureTag;

interface ProcessorContract
{
    public function process(string $html, HasTinyeditorPictureTag $model, PictureTag $pictureTagConfig);

    public function convert(HasTinyeditorPictureTag $model, string $field);
}
