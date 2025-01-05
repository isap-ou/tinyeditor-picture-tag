<?php

namespace Isapp\TinyeditorPictureTag\Contracts;

interface HasTinyeditorPictureTag
{
    public function getTinyeditorFields(): array;

    public function getTinyeditorFieldKeys(): array;

    public function registerTinyeditorFields(): void;

    public function runReplace(string $field): void;
}
