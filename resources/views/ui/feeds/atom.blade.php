<?= '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL ?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <title type="text">{!! $feed->get('title') !!}</title>
    @if (!empty($feed->get('subTitle')))
        <subtitle type="html"><![CDATA[{!! $feed->get('subTitle') !!}]]></subtitle>
    @endif
    <link rel="self" href="{{ $feed->get('linkFeed') }}" />
    <link rel="alternate" type="text/html" href="{{ $feed->get('link') }}" />
    <id>{{ $feed->get('linkFeed') }}</id>
    @if (!empty($feed->get('icon')))
        <icon>{{ $feed->get('icon') }}</icon>
    @endif
    @if (!empty($feed->get('logo')))
        <logo>{{ $feed->get('logo') }}</logo>
    @endif
    <updated>{{ $feed->get('date') }}</updated>
    @foreach($feed->get('items') as $item)
        <entry>
            <id>{{ $item->link }}</id>
            <author>
                <name>{{ $item->author }}</name>
            </author>
            <title type="text"><![CDATA[{!! $item->title !!}]]></title>
            <link rel="alternate" type="text/html" href="{{ $item->link }}" />
            <summary type="html"><![CDATA[{!! $item->summary !!}]]></summary>
            <content type="html"><![CDATA[{!! $item->content !!}]]></content>
            <updated>{{ $item->updated }}</updated>
        </entry>
    @endforeach
</feed>