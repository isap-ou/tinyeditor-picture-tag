There is https://github.com/mohamedsabil83/filament-forms-tinyeditor and https://github.com/amidesfahani/filament-tinyeditor (it is v6 and looks like a fork of the previous package). By default, TinyMCE inserts the tag `img` and doesn't allow insert  tag `picture` with responsive images.

So goal is to make extension that will handle the saving model (Observer) and will modify content with replacing tag `img` to `picture`.

## List of dependency package
1. `filament/filament` - it has laravel dependency inside
2. `spatie/laravel-medialibrary` - as it is the easiest way to generate responsive images
3. `paquettg/php-html-parser` - for DOM manipulations

## Already made peace of codes

### Observer

1. I think We need a more generic class name. 
2. Fileds should be configurable in the model. I think allow multiple fileds
3. Add any interface to the model
4. Check if field was changed to avoid unnecessary manipulations 
5. Driver should be configurable in model or in config (see about Manager lower)

```php
class BlogContentObserver
{
    /**
     * Handle the Blog "created" event.
     */
    public function created(Model $model): void
    {
       App::make(ImgToPictureService::class)->convertTranslatable($blog, 'content');
    }

    /**
     * Handle the Blog "updated" event.
     */
    public function updated(Model $model): void
    {
        App::make(ImgToPictureService::class)->convertTranslatable($blog, 'content');
    }

}
```

### Service

1. Make an interface
2. Need config file to specify media collection name and media conversions
4. Avoid manipulations for SVG
5. Translatable should be optional
6. Instead of service let's use something like manager (as example https://github.com/isap-ou/laravel-cart/blob/main/src/Manager/CartManager.php)
7. Question about storage drivers. E.g. tinymce saves to local storage and we need to save to s3
8. may be move the html to a separate view file and call `render` function

```php
class ImgToPictureService
{
    public function __construct(
        protected Dom $dom
    ) {}

    public function convertTranslatable(HasMedia $model, string $field): void
    {
        if (! method_exists($model, 'getTranslations')) {
            return;
        }
        foreach ($model->getTranslations($field) as $locale => $content) {
            if (empty($content)) {
                continue;
            }
            $newContent = $this->toPictureTag($content, $model);
            $model->setTranslation($field, $locale, $newContent);
        }
        $model->saveQuietly();
    }

    public function toPictureTag(string $html, HasMedia $model): string
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

            /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media $media */
            $media = $model->addMedia(Storage::disk('public')->path($src))
                ->withCustomProperties(['random_id' => Str::random(10)])
                ->toMediaCollection('blog_content');

            $image->setAttribute('src', $media->getFullUrl());
            $image->setAttribute('loading', 'lazy');

            $imageHtml = $image->outerHtml();

            $pictureTag = <<<HTML
<picture>
<source type="image/webp" srcset="{$media->getFullUrl('lg_webp')}" media="(min-width: 576px)">
<source type="{$media->mime_type}" srcset="{$media->getFullUrl('lg')}" media="(min-width: 576px)">
<source type="image/webp" srcset="{$media->getFullUrl('sm_webp')}">
<source type="{$media->mime_type}" srcset="{$media->getFullUrl('sm')}">
$imageHtml
</picture>
HTML;

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
```

### Model
1. Move the body of the method to Trait  and call a method inside `registerMediaCollections`

```php
   public function registerMediaCollections(): void
    {
        $this->addMediaCollection('blog_content')->registermediaConversions(function (Media $media) {
            $this->addMediaConversion('sm_webp')
                ->width(410)
                ->format('webp');
            $this->addMediaConversion('sm')
                ->width(410);
            $this->addMediaConversion('lg_webp')
                ->format('webp')
                ->width(1200);
            $this->addMediaConversion('lg')
                ->width(1200);
        });
}
```
