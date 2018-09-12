<!--npm install selectize --save-->
<input type="text" name="tags" id="tags">

<script>
    var tags = [
            @foreach ($tags as $tag)
        {tag: "{{$tag}}" },
        @endforeach
    ];
    window.$ = window.jQuery = require('jquery')
    require('selectize');
    var bootstrap = require('bootstrap-sass');

    $( document ).ready(function() {
        $('#tags').selectize({
            delimiter: ',',
            persist: false,
            valueField: 'tag',
            labelField: 'tag',
            searchField: 'tag',
            options: tags,
            create: function(input) {
                return {
                    tag: input
                }
            }
        });
    });

</script>

