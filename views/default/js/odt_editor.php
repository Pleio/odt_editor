<?php

# Using data-document-url on the editor div to get the url from where to download the document
# TODO: is there another/better way to pass data from views to javascript methods?

?>
//<script>

elgg.provide("elgg.odt_editor");

elgg.odt_editor.init = function() {
    var editorConfig = {
        allFeaturesEnabled: true,
        userData: {
            fullName: elgg.get_logged_in_user_entity().name
        }
    };
    var odtEditorDiv = document.getElementById("odt_editor");
    var documentUrl = odtEditorDiv && odtEditorDiv.getAttribute("data-document-url");

    Wodo.createTextEditor("odt_editor", editorConfig, function (err, editor) {
        editor.openDocumentFromUrl(documentUrl, function(err) {
        });
    });
}

//register init hook
elgg.register_hook_handler("init", "system", elgg.odt_editor.init);
