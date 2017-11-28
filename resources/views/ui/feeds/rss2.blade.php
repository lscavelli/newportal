<?= '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{!! $feed->get('title') !!}</title>
        <link>{{ $feed->get('linkFeed') }}</link>
        <description><![CDATA[{!! $feed->get('description') !!}]]></description>
        <atom:link href="{{ $feed->get('link') }}" rel="self" type="application/rss+xml" />
        <language>{{ $feed->get('language') }}</language>
        @if (!empty($feed->get('copyright')))
            <copyright>{{ $feed->get('copyright') }}</copyright>
        @endif
        @if (!empty($feed->get('logo')))
            <image>
                <url>{{ $feed->get('logo') }}</url>
                <title>{{ $feed->get('title') }}</title>
                <link>{{ $feed->get('link') }}</link>
            </image>
        @endif
        @if (!empty($feed->get('category')))
            <category>{{ $feed->get('category') }}</category>
        @endif
        @foreach($feed->get('items') as $item)
            <item>
                <guid isPermaLink="false">{{ $item->link }}</guid>
                <title><![CDATA[{!! $item->title !!}]]></title>
                @if (!empty($item->category))
                    <category>{{ $item->category }}</category>
                @endif
                <link>{{ $item->link }}</link>
                <description><![CDATA[{!! $item->content !!}]]></description>
                <pubDate>{{ $item->updated }}</pubDate>
                @if (!empty($item->comments))
                    <comments>{{ $item->comments }}</comments>
                @endif
                @if (!empty($item->enclosure))
                    <enclosure url="{{ $item->enclosure['url'] }}"
                               length="{{ $item->enclosure['length'] }}"
                               type="{{ $item->enclosure['type'] }}" />
                @endif

            </item>
        @endforeach
    </channel>
</rss>