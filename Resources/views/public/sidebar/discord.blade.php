@if($configuration['widget__discord'])
    <div class="flex-sidebar-element">
        <iframe src="https://discordapp.com/widget?id={{ $configuration['discord'] }}&theme=dark" height="500" style="width: 100%" allowtransparency="true" frameborder="0" sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts"></iframe>
    </div>
@endif