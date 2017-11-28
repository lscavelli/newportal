<?= '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    @foreach($sitemap->get('items') as $resource)
        <url>
            <loc>{{ $resource->url }}</loc>
            @if (!empty($resource->lastmod))
                <lastmod>{{ $resource->lastmod }}</lastmod>
            @endif
            @if (!empty($resource->changefreq))
                <changefreq>{{ $resource->changefreq }}</changefreq>
            @endif
            @if (!empty($resource->priority))
                <priority>{{ $resource->priority }}</priority>
            @endif
        </url>
    @endforeach

</urlset>