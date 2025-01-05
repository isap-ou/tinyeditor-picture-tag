<?php declare(strict_types=1);

namespace Isapp\FilamentFormsTinyeditorPictureTag\Traits;

use Isapp\FilamentFormsTinyeditorPictureTag\PictureTags\PictureTag;
use Isapp\FilamentFormsTinyeditorPictureTag\PictureTags\PictureTagSource;

trait PictureTagCollections
{
    public array $pictureTags = [];
    public array $pictureTagSources = [];

    public function addPictureTag(string $field, string $collectionName): PictureTag
    {
        $pictureTag = new PictureTag($field, $collectionName);
        $this->pictureTags[$field] = $pictureTag;
        return $pictureTag;
    }

    public function addPictureTagSource(string $name): PictureTagSource
    {
        $pictureTagSource = new PictureTagSource($name);
        $this->pictureTagSources[] = $pictureTagSource;
        return $pictureTagSource;
    }

    public function addPictureTagCollections(): void
    {
        $this->addMediaEndpointsConfiguration();
        if (empty($this->pictureTags)) {
            return;
        }

        foreach ($this->pictureTags as $pictureTag) {
            $this->addMediaCollection($pictureTag->collectionName)
                ->registerMediaConversions( fn () => $this->applyMediaConversions($pictureTag) );
        }
    }

    /**
     * Apply media conversions based on the configuration.
     */
    private function applyMediaConversions(PictureTag $pictureTag): void
    {
        ($pictureTag->pictureTagSources)();
        $mediaConversions = $this->pictureTagSources;
        if (empty($mediaConversions)) {
            return;
        }

        foreach ($mediaConversions as $conversion) {
            $this->createMediaConversion($conversion);
        }
    }

    /**
     * Create a single media conversion.
     */
    private function createMediaConversion(PictureTagSource $conversion): void
    {
        if (empty($conversion->getName()) || empty($conversion->getWidth())) {
            return;
        }

        $mediaConversion = $this->addMediaConversion($conversion->getName())
            ->width($conversion->getWidth());

        if ($conversion->getFormat()) {
            $mediaConversion->format($conversion->getFormat());
        }
    }
}
