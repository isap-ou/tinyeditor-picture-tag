<?php

declare(strict_types=1);

namespace Isapp\TinyeditorPictureTag\PictureTag;

use function sprintf;

class PictureTagSource
{
    protected ?string $format = null;

    protected ?int $width = null;

    protected ?int $height = null;

    protected ?int $breakpointMaxWidth = null;

    protected ?int $breakpointMinWidth = null;

    protected function __construct(
        readonly protected string $mediaConversionName
    ) {}

    public static function make(string $mediaConversionName): static
    {
        return new static($mediaConversionName);
    }

    public function getMediaConversionName(): string
    {
        return $this->mediaConversionName;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getBreakpointMaxWidth(): ?int
    {
        return $this->breakpointMaxWidth;
    }

    public function setBreakpointMaxWidth(int $breakpointMaxWidth): static
    {
        $this->breakpointMaxWidth = $breakpointMaxWidth;

        return $this;
    }

    public function getBreakpointMinWidth(): ?int
    {
        return $this->breakpointMinWidth;
    }

    public function setBreakpointMinWidth(int $breakpointMinWidth): static
    {
        $this->breakpointMinWidth = $breakpointMinWidth;

        return $this;
    }

    public function getType(string $default): string
    {
        if ($this->format === null) {
            return $default;
        }

        return 'image/'.$this->format;
    }

    public function getMediaBreakPoints(): string
    {
        $media = [];

        if ($this->breakpointMaxWidth !== null) {
            $media[] = sprintf('(max-width: %dpx)', $this->breakpointMaxWidth);
        }
        if ($this->breakpointMinWidth !== null) {
            $media[] = sprintf('(min-width: %dpx)', $this->breakpointMinWidth);
        }

        if (empty($media)) {
            return '';
        }

        return implode(' and ', $media);
    }
}
