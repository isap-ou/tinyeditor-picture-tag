<?php

namespace Isapp\TinyeditorPictureTag\PictureTag;

use Illuminate\Support\Facades\Config;

class PictureTag
{
    protected string $driver;

    protected array $sources = [];

    protected string $inputDisk = 'public';

    protected string $outputDisk;

    protected bool $loadingLazyHtmlAttribute = true;

    private function __construct(
        readonly protected string $mediaCollectionName
    ) {
        $this->outputDisk = Config::get('media-library.disk_name');
    }

    public static function make(string $mediaCollectionName): static
    {
        return new static($mediaCollectionName);
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function setDriver(string $driver): PictureTag
    {
        $this->driver = $driver;

        return $this;
    }

    public function getMediaCollection(): string
    {
        return $this->mediaCollectionName;
    }

    public function registerSource(PictureTagSource $source): PictureTag
    {
        $this->sources[] = $source;

        return $this;
    }

    public function getInputDisk(): string
    {
        return $this->inputDisk;
    }

    public function setInputDisk(string $disk): PictureTag
    {
        $this->inputDisk = $disk;

        return $this;
    }

    public function getOutputDisk(): string
    {
        return $this->outputDisk;
    }

    public function setOutputDisk(string $disk): PictureTag
    {
        $this->outputDisk = $disk;

        return $this;
    }

    public function getSources(): array
    {
        return $this->sources;
    }

    public function hasLoadingLazyHtmlAttribute(): bool
    {
        return $this->loadingLazyHtmlAttribute;
    }

    public function setLoadingLazyHtmlAttribute(bool $condition): static
    {
        $this->loadingLazyHtmlAttribute = $condition;

        return $this;
    }
}
