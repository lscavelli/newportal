<?= '<? xml version="1.0" encoding="UTF-8" ?>'.PHP_EOL ?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <title type="text">{!! $feed->get('summary.title') !!}</title>
    <subtitle type="html"><![CDATA[{!! $feed->get('summary.description') !!}]]></subtitle>
    <link href="{{ $feed->get('summary.link') }}" />
    <link rel="alternate" type="text/html" href="{{ $feed->get('summary.rssLink') }}" />
    <link rel="{{ $feed->get('summary.ref') }}" type="application/atom+xml" href="{{ $feed->get('summary.link') }}" />
    <id>{{ $feed->get('summary.link') }}</id>
    @if (!empty($feed->get('summary.logo')))
        <logo>{{ $feed->get('summary.logo') }}</logo>
    @endif
    <updated>{{ $feed->get('summary.date') }}</updated>
    @foreach($feed->get('items') as $item)
        <entry>
            <id>{{ $item->link }}</id>
            <author>
                <name>{{ $item->author }}</name>
            </author>
            <title type="text"><![CDATA[{!! $item->title !!}]]></title>
            <link rel="alternate" type="text/html" href="{{ $item->link }}" />
            <summary type="html"><![CDATA[{!! $item->summary !!}]]></summary>
            <updated>{{ $item->updated }}</updated>
        </entry>
    @endforeach
</feed>