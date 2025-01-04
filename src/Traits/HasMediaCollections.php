<?php declare(strict_types=1);

namespace Isapp\FilamentFormsTinyeditorPictureTag\Traits;

trait HasMediaCollections
{
    public function registerMediaCollection(): void
    {
        $this->addMediaCollection(config('filament-forms-tinyeditor-picture-tag.media_collection'))
            ->registermediaConversions( fn () => $this->applyMediaConversions() );
    }

    /**
     * Apply media conversions based on the configuration.
     */
    private function applyMediaConversions(): void
    {
        $mediaConversions = config('filament-forms-tinyeditor-picture-tag.media_conversions', []);
        foreach ($mediaConversions as $conversion) {
            $this->createMediaConversion($conversion);
        }
    }

    /**
     * Create a single media conversion.
     */
    private function createMediaConversion(array $conversion): void
    {
        if (empty($conversion['name']) || empty($conversion['width'])) {
            return;
        }

        $mediaConversion = $this->addMediaConversion($conversion['name'])
            ->width($conversion['width']);

        if (!empty($conversion['format'])) {
            $mediaConversion->format($conversion['format']);
        }
    }
}
