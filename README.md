# Filament Forms TinyMCE Editor Image Helper

It's helper package for module https://github.com/mohamedsabil83/filament-forms-tinyeditor
The main goal is creating responsive images in content of some content...
Also, the base of this module is https://spatie.be/docs/laravel-medialibrary/v11/introduction

## Installation

You can install the package via composer:

```bash
composer require isapp/filament-forms-tinyeditor-picture-tag
```

## Usage

1. Add observer to your model - ```TinyeditorPictureTagObserver```
2. Implement interface - ```TinyeditorPictureTagProvider```
3. Add trait - ```HasMediaCollections```
4. Implement method - ```getProvider```
5. Expand ```registerMediaCollections``` method of spatie media library, call ```registerMediaCollection```


Example of a model

```php
<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Isapp\FilamentFormsTinyeditorPictureTag\Contracts\TinyeditorPictureTagProvider;
use Isapp\FilamentFormsTinyeditorPictureTag\Observers\TinyeditorPictureTagObserver;
use Isapp\FilamentFormsTinyeditorPictureTag\Traits\HasMediaCollections;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[ObservedBy([TinyeditorPictureTagObserver::class])]
class Post extends Model implements TinyeditorPictureTagProvider, HasMedia
{
    use InteractsWithMedia;
    use HasMediaCollections;

    protected $guarded = [];

    public function getProvider(): string
    {
        return config('filament-forms-tinyeditor-picture-tag.driver');
    }

    public function registerMediaCollections(): void
    {
        $this->registerMediaCollection();
    }
}

```

So when you have some filament form with tinyMCE editor, you can upload some images to content. When you save this form
our observer will parse the content and replace common image on responsive images.
Also, note, that the queue should be working.

## Configuration

 - processed_fields - The fields with content where we need to replace img to responsive image.
 - storage_disk - the same as main storage driver, public, s3, etc...
 - driver - non-translatable, translatable. Default is non-translatable, if you use spatie translatable package and json fields you need translatable driver.
 - media_collection - any name which you want for image collection
 - media_conversions - media endpoints

Default config:

```php
return [
    'processed_fields' => ['content'],
    'storage_disk' => 'public',
    'driver' => 'non-translatable',
    'media_collection' => 'editor-collection',
    'media_conversions' => [
        [
            'name' => 'sm_webp',
            'width' => 410,
            'format' => 'webp',
            'position' => 1
        ],
        [
            'name' => 'sm',
            'width' => 410,
            'position' => 2
        ],
        [
            'name' => 'lg_webp',
            'width' => 1200,
            'format' => 'webp',
            'min-width' => 576,
            'position' => 3
        ],
        [
            'name' => 'lg',
            'width' => 1200,
            'min-width' => 576,
            'position' => 4
        ],
    ]
];
```
