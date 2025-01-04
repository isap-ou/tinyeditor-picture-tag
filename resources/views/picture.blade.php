<picture>
    <source type="image/webp" srcset="{{ $media->getFullUrl('lg_webp') }}" media="(min-width: 576px)">
    <source type="{{ $media->mime_type }}" srcset="{{ $media->getFullUrl('lg') }}" media="(min-width: 576px)">
    <source type="image/webp" srcset="{{ $media->getFullUrl('sm_webp') }}">
    <source type="{{ $media->mime_type }}" srcset="{{ $media->getFullUrl('sm') }}">
    {!! $imageHtml !!}
</picture>
