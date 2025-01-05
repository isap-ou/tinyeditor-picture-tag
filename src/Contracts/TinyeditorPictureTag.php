<?php

namespace Isapp\FilamentFormsTinyeditorPictureTag\Contracts;

interface TinyeditorPictureTag
{
    public function getPictureTagDriver(): string;
    public function addMediaEndpointsConfiguration(): void;
}
