<?php

namespace Isapp\FilamentFormsTinyeditorPictureTag\Contracts;

interface TinyeditorPictureTagConverter
{
    public function convert(TinyeditorPictureTagProvider $model): void;
}
