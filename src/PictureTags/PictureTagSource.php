<?php declare(strict_types=1);

namespace Isapp\FilamentFormsTinyeditorPictureTag\PictureTags;

class PictureTagSource
{
    public function __construct(
        private string $name,
        private ?int $width = null,
        private ?string $format = null,
        private ?int $minWidth = null,
        private ?int $maxWidth = null,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function getMinWidth(): int
    {
        return $this->minWidth;
    }

    public function getMaxWidth(): int
    {
        return $this->maxWidth;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;
        return $this;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;
        return $this;
    }

    public function setMinWidth(int $minWidth): self
    {
        $this->minWidth = $minWidth;
        return $this;
    }

    public function setMaxWidth(int $maxWidth): self
    {
        $this->maxWidth = $maxWidth;
        return $this;
    }
}
