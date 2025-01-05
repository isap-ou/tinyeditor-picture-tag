<?php

declare(strict_types=1);

namespace Isapp\TinyeditorPictureTag\Processors;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Isapp\TinyeditorPictureTag\Contracts\HasTinyeditorPictureTag;
use Isapp\TinyeditorPictureTag\Contracts\ProcessorContract;
use Isapp\TinyeditorPictureTag\PictureTag\PictureTag;
use PHPHtmlParser\Dom;
use Throwable;

use function compact;

abstract class Processor implements ProcessorContract
{
    protected Dom $dom;

    protected array $removeMedia = [];

    public function __construct()
    {
        $this->dom = App::make(Dom::class);
    }

    /**
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\UnknownChildTypeException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     */
    public function process(string $html, HasTinyeditorPictureTag $model, PictureTag $pictureTagConfig): string
    {
        /** @var Dom $dom */
        $dom = $this->dom->loadStr($html);

        $images = $dom->find('img');

        /** @var \PHPHtmlParser\Dom\HtmlNode $image */
        foreach ($images as $image) {
            /** @var \PHPHtmlParser\Dom\HtmlNode $parent */
            $parent = $image->getParent();
            if ($parent?->getTag()->name() === 'picture') {
                continue;
            }

            $src = $image->getAttribute('src');
            $src = explode('/', $src);
            $src = end($src);

            if ($src && str_ends_with($src, '.svg')) {
                continue;
            }

            $inputStorage = Storage::disk($pictureTagConfig->getInputDisk());

            if (! $inputStorage->exists($src)) {
                continue;
            }

            /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media $media */
            /** @var \Spatie\MediaLibrary\HasMedia $model */
            $media = $model->copyMedia($inputStorage->path($src))
                ->withCustomProperties(['random_id' => Str::random(10)])
                ->toMediaCollection(
                    $pictureTagConfig->getMediaCollection(),
                    $pictureTagConfig->getOutputDisk()
                );

            $this->removeMedia[] = ['storage' => $inputStorage, 'path' => $src];

            $image->setAttribute('src', $media->getFullUrl());
            if ($pictureTagConfig->hasLoadingLazyHtmlAttribute()) {
                $image->setAttribute('loading', 'lazy');
            }
            $imageHtml = $image->outerHtml();
            $pictureTag = View::make(
                'tinyeditor-picture-tag::picture',
                compact('pictureTagConfig', 'imageHtml', 'media')
            )->render();

            $pictureDom = (new Dom)
                ->loadStr($pictureTag);
            if ($parent->getTag()->name() === 'p' && $parent->countChildren() === 1) {
                $dom->root->replaceChild($parent->id(), $pictureDom->firstChild());
            } else {
                $parent->replaceChild($image->id(), $pictureDom->firstChild());
            }
        }

        return $dom->outerHtml;
    }

    final public function removeOldMedia(): void
    {
        foreach ($this->removeMedia as $item) {
            try {
                $item['storage']->delete($item['path']);
            } catch (Throwable $exception) {
                // TODO: handle exception?
            }
        }
    }
}
