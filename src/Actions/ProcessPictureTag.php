<?php declare(strict_types=1);

namespace Isapp\FilamentFormsTinyeditorPictureTag\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPHtmlParser\Dom;

class ProcessPictureTag
{
    public function __construct(
        protected Dom $dom
    ) {}

    public function process(string $html, Model $model): string
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
            ->toMediaCollection(config('filament-forms-tinyeditor-picture-tag.media_collection'));

            $image->setAttribute('src', $media->getFullUrl());
            $image->setAttribute('loading', 'lazy');
            $imageHtml = $image->outerHtml();
            $pictureTag = view('picture')->with([
                'media' => $media,
                'mediaConversions' => collect(
                    config('filament-forms-tinyeditor-picture-tag.media_conversions', [])
                )->sortBy('position'),
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
}
