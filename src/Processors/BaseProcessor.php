<?php declare(strict_types=1);

namespace Isapp\FilamentFormsTinyeditorPictureTag\Processors;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Isapp\FilamentFormsTinyeditorPictureTag\Contracts\TinyeditorPictureTag;
use Isapp\FilamentFormsTinyeditorPictureTag\PictureTags\PictureTag;
use PHPHtmlParser\Dom;

abstract class BaseProcessor
{
    public function __construct(
        protected Dom $dom
    ) {}

    public function process(string $html, TinyeditorPictureTag $model, PictureTag $pictureTag): string
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

            /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media $media */
            $media = $model->addMedia(Storage::disk(
                config('filament-forms-tinyeditor-picture-tag.storage_disk', 'public')
            )->path($src))
                ->withCustomProperties(['random_id' => Str::random(10)])
                ->toMediaCollection($pictureTag->collectionName);

            $image->setAttribute('src', $media->getFullUrl());
            $image->setAttribute('loading', 'lazy');
            $imageHtml = $image->outerHtml();
            $pictureTag = view('picture')->with([
                'media' => $media,
                'imageHtml' => $imageHtml,
            ])->render();

            /** @var Dom $pictureDom */
            $pictureDom = (new Dom())->loadStr($pictureTag);
            if ($parent->getTag()->name() === 'p' && $parent->countChildren() === 1) {
                $dom->root->replaceChild($parent->id(), $pictureDom->firstChild());
            } else {
                $parent->replaceChild($image->id(), $pictureDom->firstChild());
            }
        }

        return $dom->outerHtml;
    }

    abstract protected function convert(TinyeditorPictureTag $model): void;
}
