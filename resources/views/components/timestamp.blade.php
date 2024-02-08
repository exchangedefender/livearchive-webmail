{{--<time--}}
{{--    _="--}}
{{--    init--}}
{{--        js return {!! $jsDate() !!} end then put it into my.innerHTML--}}
{{--    "--}}
{{--    {{$attributes->class(['inline-flex items-center py-1 px-2', 'text-xs font-thin' => $styled])}} datetime="{{$timestamp->toString()}}">--}}
{{--    <noscript>{{$display()}}</noscript>--}}
{{--</time>--}}
@php
    if($timestamp->isToday()) {
        $formatStyle = 'narrow';
        $format = 'relative';
    } else if ($timestamp->isYesterday()) {
        $formatStyle = 'narrow';
        $format = 'datetime';
    } else {
        $formatStyle = $timestamp->isCurrentYear() ? 'short' : 'long';
        $format = 'datetime';
    }
@endphp
<relative-time {{$attributes->except(['tense', 'precision', 'format', 'formatStyle', 'datetime'])}} tense="past" precision="minute" format="{{$format}}" formatStyle="{{$formatStyle}}" datetime="{{$timestamp->toISOString()}}">
    {{$display()}}
</relative-time>
