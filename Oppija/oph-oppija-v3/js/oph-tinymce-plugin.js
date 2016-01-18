(function() {  
    tinymce.create('tinymce.plugins.Oph', {  
        init : function(ed, url) {  
            ed.addButton('oph-sign', {
                title : 'Add Arrow Sign',  
                image : url + '/oph-sign.png', 
                onclick : function() {  
                     ed.selection.setContent('[oph-sign title="" href=""]' + ed.selection.getContent() + '[/oph-sign]');  
                }  
            });
            ed.addButton('oph-highlight', {
                title : 'Add Highlight',  
                image : url + '/oph-highlight.png', 
                onclick : function() {  
                     ed.selection.setContent('<span class="oph-highlight">' + ed.selection.getContent() + '</span>');  
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        },  
    });  
    tinymce.PluginManager.add('oph', tinymce.plugins.Oph);  
})();  