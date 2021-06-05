@foreach($icons as $i)
    @if($i['type'] == 1)
        <link rel="stylesheet" href="{{ $i['import'] }}" type="text/css">
    @elseif($i['type'] == 2)
        <script src="{{ $i['import'] }}"></script>
    @endif
@endforeach

<style>
    :root {
        --main-color: {{ $customization['color__main'] }};
        --second-color: {{ $customization['color__second'] }};
        --background: {{ $customization['color__background'] }};
    }

</style>

<link rel="stylesheet" href="@PluginAssets('css/flexible.css')">
<link rel="stylesheet" href="@PluginAssets('css/tooltip.css')">
