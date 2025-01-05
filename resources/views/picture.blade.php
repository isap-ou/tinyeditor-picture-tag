<picture>
    @if (!empty($pictureTagConfig->getSources()))
        @foreach($pictureTagConfig->getSources() as $mediaConversion)
            <source
                    type="{{$mediaConversion->getType($media->mime_type)}}"
                    media="{{$mediaConversion->getMediaBreakPoints()}}"
                    srcset="{{$media->getFullUrl($mediaConversion->getMediaConversionName())}}"
            />
        @endforeach
    @endif
    {!! $imageHtml !!}
</picture>
