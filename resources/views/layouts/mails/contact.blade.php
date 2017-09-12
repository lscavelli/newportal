<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<h4>Nuova richiesta informazioni da parte di {{ $attributes['name'] }}</h4>
<p>{{ $attributes['name'] }} ti ha invito il seguente messaggio:</p>
<p>{{ $attributes['message'] }}</p>
<h4>Ulteriori informazioni:</h4>
<ul>
    @foreach($attributes['infos'] as $key => $info)
        <li><strong>{{ $key }}:</strong> {{ $info }}</li>
    @endforeach
</ul>
</body>
</html>