import icons from "trumbowyg/dist/ui/icons.svg"
import "trumbowyg/dist/ui/trumbowyg.min.css"
import "trumbowyg/dist/trumbowyg.min.js"
import "trumbowyg/dist/langs/fr"

// Plugins Trumbowyg
import 'jquery-resizable-dom'
import "trumbowyg/dist/plugins/table/trumbowyg.table.min"
import "trumbowyg/dist/plugins/table/ui/trumbowyg.table.min.css"
import "trumbowyg/dist/plugins/resizimg/trumbowyg.resizimg.min"
import 'trumbowyg/dist/plugins/upload/trumbowyg.upload.min'

$.trumbowyg.svgPath = icons

$('.wysiwyg').trumbowyg({
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
        ['undo', 'redo'], // Only supported in Blink browsers
        ['formatting'],
        ['strong', 'em', 'del'],
        ['superscript', 'subscript'],
        ['link'],
        ['table'],
        ['image'],
        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
        ['unorderedList', 'orderedList'],
        ['horizontalRule'],
        ['removeformat'],
        ['fullscreen']
    ],
    autogrow: true,
    removeformatPasted: true,
    urlProtocol: true,
    plugins: {
        upload: {
            serverPath: 'https://api.imgur.com/3/image',
            fileFieldName: 'image',
            headers: {
                'Authorization': 'Client-ID 83560e3b380c3ef'
            },
            urlPropertyName: 'data.link'
        },
        resizimg: {
            minSize: 64,
            step: 16,
        }
    }
})

$('a[data-confirm]').on('click', function() {
    return confirm($(this).attr('data-confirm'))
})