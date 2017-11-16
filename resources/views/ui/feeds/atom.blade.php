<?= '<? xml version="1.0" encoding="UTF-8" ?>'.PHP_EOL ?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <title type="text">{!! $feed->get('feedTitle') !!}</title>
    @if (!empty($feed->get('feedSubTitle')))
        <subtitle type="html"><![CDATA[{!! $feed->get('feedSubTitle') !!}]]></subtitle>
    @endif
    <link href="{{ $feed->get('feedLink') }}" />
    <id>{{ $feed->get('feedLink') }}</id>
    @if (!empty($feed->get('feedIcon')))
        <icon>{{ $feed->get('feedIcon') }}</icon>
    @endif
    @if (!empty($feed->get('feedLogo')))
        <logo>{{ $feed->get('feedLogo') }}</logo>
    @endif
    @if (!empty($feed->get('feedCategory')))
        <category term="sports" />
    @endif
    <updated>{{ $feed->get('feedDate') }}</updated>
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