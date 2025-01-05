<?php declare(strict_types=1);

namespace Isapp\FilamentFormsTinyeditorPictureTag\PictureTags;

class PictureTag
{
    public \Closure|null $pictureTagSources = null;

    public function __construct(
        public string $field,
        public string $collectionName,
    ) {
        $this->pictureTagSources = function () {};
    }

    public function registerPictureTagSources(callable $pictureTagSources): void
    {
        $this->pictureTagSources = $pictureTagSources;
    }
}
