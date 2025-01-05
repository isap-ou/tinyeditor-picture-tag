@if (!empty($mediaConversions))
    <picture>
        @foreach($mediaConversions as $mediaConversion)
            @if (!empty($mediaConversion['format']) && $mediaConversion['format'] === 'webp')
                <source type="image/webp" srcset="{{ $media->getFullUrl($mediaConversion['name']) }}" @if (!empty($mediaConversion['width'])) media="(min-width: {{ $mediaConversion['width'] }}px)" @endif>
            @else
                <source type="{{ $media->mime_type }}" srcset="{{ $media->getFullUrl($mediaConversion['name']) }}" @if (!empty($mediaConversion['width'])) media="(min-width: {{ $mediaConversion['width'] }}px)" @endif>
            @endif
        @endforeach
        {!! $imageHtml !!}
    </picture>
@endif
