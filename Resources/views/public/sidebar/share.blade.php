@if($configuration['widget__share'])
    <div class="flex-sidebar-element">
        @foreach(forum__getShares()->get() as $w)
            <a class="@if($w['name'] != 9) share-btn @endif share-icon" href="{{ forum__generateSharable()[0][$w['name']-1] }}" style="background: {{ $w['color'] }}">
                {!! iconify($w) !!}
            </a>
        @endforeach
        <script !src="">
            (function(){

                var shareButtons = document.querySelectorAll(".share-btn");

                if (shareButtons) {
                    [].forEach.call(shareButtons, function(button) {
                        button.addEventListener("click", function(event) {
                            var width = 650,
                                height = 450;

                            event.preventDefault();

                            window.open(this.href, 'Share Dialog', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,width='+width+',height='+height+',top='+(screen.height/2-height/2)+',left='+(screen.width/2-width/2));
                        });
                    });
                }

            })();
        </script>
    </div>
@endif