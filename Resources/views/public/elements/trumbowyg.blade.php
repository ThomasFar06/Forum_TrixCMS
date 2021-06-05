<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.21.0/plugins/table/ui/trumbowyg.table.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.21.0/plugins/emoji/ui/trumbowyg.emoji.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.21.0/plugins/colors/ui/trumbowyg.colors.min.css" />
<link rel="stylesheet" href="@PluginAssets('css/trumbowyg.css')">

<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.21.0/trumbowyg.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.21.0/plugins/colors/trumbowyg.colors.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.21.0/plugins/fontfamily/trumbowyg.fontfamily.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.21.0/plugins/fontfamily/trumbowyg.fontfamily.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.21.0/plugins/history/trumbowyg.history.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.21.0/plugins/pasteembed/trumbowyg.pasteembed.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.21.0/plugins/table/trumbowyg.table.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.21.0/plugins/upload/trumbowyg.upload.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.21.0/plugins/emoji/trumbowyg.emoji.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.21.0/plugins/cleanpaste/trumbowyg.cleanpaste.min.js"></script>




<script src="https://cdnjs.cloudflare.com/ajax/libs/tributejs/5.1.3/tribute.min.js" integrity="sha512-KJYWC7RKz/Abtsu1QXd7VJ1IJua7P7GTpl3IKUqfa21Otg2opvRYmkui/CXBC6qeDYCNlQZ7c+7JfDXnKdILUA==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tributejs/5.1.3/tribute.css" integrity="sha512-GnwBnXd+ZGO9CdP343MUr0jCcJXCr++JVtQRnllexRW2IDq4Zvrh/McTQjooAKnSUbXZ7wamp7AQSweTnfMVoA==" crossorigin="anonymous" />

<script>
    const editor = $("#editor");
    const editor_counter = $('.char-counter-editor');

    editor.trumbowyg({
        lang: 'fr',
        btnsDef: {
            // Create a new dropdown
            image: {
                dropdown: ['insertImage', 'upload'],
                ico: 'insertImage'
            }
        },
        btns: [
            ['viewHTML'],
            ['historyUndo', 'historyRedo'],
            ['formatting'],
            ['strong', 'em', 'del'],
            ['emoji'],
            ['superscript', 'subscript'],
            ['fontfamily', 'fontsize'],
            ['foreColor', 'backColor'],
            ['link'],
            ['image'],
            ['table'],
            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
            ['unorderedList', 'orderedList'],
            ['horizontalRule'],
            ['removeformat'],
            ['fullscreen']
        ],
        plugins: {
            fontfamily: {
                fontList: [
                    {name: 'Arial', family: 'Arial, Helvetica, sans-serif'},
                    {name: 'Open Sans', family: '\'Open Sans\', sans-serif'},
                    {name: 'Serif', family: '\'Serif\', serif'}
                ]
            },
            fontsize: {
                sizeList: [
                    '12px',
                    '14px',
                    '16px'
                ]
            },
            upload: {
                serverPath: 'https://api.imgur.com/3/image',
                fileFieldName: 'image',
                headers: {
                    'Authorization': 'Client-ID xxxxxxxxxxxx'
                },
                urlPropertyName: 'data.link'
            }
        }
    }).on('tbwchange', function (e, p) {

        $("#preview").html(editor.trumbowyg('html'));

        editor.css('height', '100%');

        editor_counter.children().html(editor.trumbowyg('html').length);

        editor_counter.children('#plural').html((editor.trumbowyg('html').length > 1) ? 's' : '');

    });

    function preview() {
        $("#preview").slideToggle(200);
    }

    const text = $('.trumbowyg-editor');

    var tribute = new Tribute({
        values: [
                @foreach(forum__getUsers()->get() as $u)
                    {key: '{{ user($u['id'])->pseudo }}', value: '{{ user($u['id'])->pseudo }}#{{str_pad($u['id'], 4, '0', STR_PAD_LEFT)}}'},
                @endforeach
        ]
    })

    tribute.attach(text);
</script>
