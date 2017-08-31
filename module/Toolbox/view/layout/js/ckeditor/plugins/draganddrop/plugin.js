CKEDITOR.plugins.add('myplugin',
        {
        init: function (editor) {

            function onDragStart(event) {                 
                console.log("onDragStart");
            };

            editor.on('contentDom', function (e) {                
                editor.document.on('dragstart', onDragStart);
                // editor.document.on('drop', onDrop);
                // etc.
            });
        }
});