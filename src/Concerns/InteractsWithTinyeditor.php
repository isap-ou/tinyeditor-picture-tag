<?php

declare(strict_types=1);

namespace Isapp\TinyeditorPictureTag\Concerns;

use Isapp\TinyeditorPictureTag\Contracts\HasTinyeditorPictureTag;
use Isapp\TinyeditorPictureTag\PictureTag\PictureTag;
use Isapp\TinyeditorPictureTag\PictureTag\PictureTagSource;
use RuntimeException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use function app;
use function array_keys;
use function class_implements;
use function in_array;
use function method_exists;

trait InteractsWithTinyeditor
{
    protected array $tinyeditorFields = [];

    public static function bootInteractsWithTinyeditor(): void
    {
        static::created(static function (HasTinyeditorPictureTag $model) {
            foreach ($model->getTinyeditorFieldKeys() as $field) {
                if (! empty($model->{$field})) {
                    $model->runReplace($field);
                }
            }
        });
        static::updated(static function (HasTinyeditorPictureTag $model) {
            foreach ($model->getTinyeditorFieldKeys() as $field) {
                if (! empty($model->{$field}) && $model->wasChanged($field)) {
                    $model->runReplace($field);
                }
            }
        });
    }

    public function initializeInteractsWithTinyeditor(): void
    {
        $this->registerTinyeditorFields();
    }

    public function getTinyeditorFields(): array
    {
        return $this->tinyeditorFields;
    }

    public function getTinyeditorFieldKeys(): array
    {
        return array_keys($this->tinyeditorFields);
    }

    final public function runReplace(string $field): void
    {
        if (! in_array($field, $this->getTinyeditorFieldKeys())) {
            throw new RuntimeException('Field '.$field.' has not registered for convertation');
        }

        $pictureTag = $this->tinyeditorFields[$field];

        /** @var \Isapp\TinyeditorPictureTag\Contracts\ProcessorContract $processor */
        $processor = app('tinyeditor-picture-tag')->driver($pictureTag->getDriver());
        $processor->convert($this, $field);
        $processor->removeOldMedia();
    }

    protected function registerTinyeditorField(string $field, string $mediaCollection): PictureTag
    {
        $pictureTag = PictureTag::make($mediaCollection);

        $this->tinyeditorFields[$field] = $pictureTag;

        $driver = 'default';

        if (method_exists($this, 'getTranslations') && in_array($field, $this->getTranslatableAttributes())) {
            $driver = 'translatable';
        }

        $pictureTag->setDriver($driver);

        return $pictureTag;
    }

    protected function registerTinyeditorPictureSource(string $mediaConversionName): PictureTagSource
    {
        return PictureTagSource::make($mediaConversionName);
    }

    protected function applyTinyeditorMediaCollections(): void
    {

        if (! in_array(\Spatie\MediaLibrary\HasMedia::class, class_implements($this))) {
            throw new RuntimeException('Model '.static::class.' must implement \Spatie\MediaLibrary\HasMedia');
        }

        /**
         * @var string $field
         * @var PictureTag $pictureTag
         */
        foreach ($this->tinyeditorFields as $pictureTag) {
            if (array_key_exists($pictureTag->getMediaCollection(), $this->mediaCollections)) {
                continue;
            }
            $this->addMediaCollection($pictureTag->getMediaCollection())
                ->registerMediaConversions(
                    function (Media $media) use ($pictureTag) {
                        /** @var \Isapp\TinyeditorPictureTag\PictureTag\PictureTagSource $source */
                        foreach ($pictureTag->getSources() as $source) {
                            $conversion = $this->addMediaConversion($source->getMediaConversionName());
                            if (! empty($source->getFormat())) {
                                $conversion->format($source->getFormat());
                            }
                            if ($source->getWidth() !== null) {
                                $conversion->width($source->getWidth());
                            }
                            if ($source->getHeight() !== null) {
                                $conversion->height($source->getHeight());
                            }
                        }
                    }
                );
        }
    }
}
