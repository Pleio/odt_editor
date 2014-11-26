<?php

# Using data-document-url on the editor div to get the url from where to download the document
# TODO: is there another/better way to pass data from views to javascript methods?

?>
//<script>

elgg.provide("elgg.odt_editor");

elgg.odt_editor.init = function() {
    var editor;

    function save() {
        editor.getDocumentAsByteArray(function(err, data) {
            if (err) {
                elgg.register_error(err);
                return;
            }

            // TODO: get original filename here, if needed, by name: parameter
            var blob = new Blob([data.buffer], {type: "application/vnd.oasis.opendocument.text"});
            var formData = new FormData();

            formData.append("upload", blob);
            formData.append("file_guid", guid);
            var token = {};
            elgg.security.addToken(token);
            Object.keys(token).forEach(function (k) {
                formData.append(k, token[k]);
            });

            elgg.post("action/odt_editor/upload", {
                data: formData,
                contentType: false, // not "multipart/form-data", false lets browser do the right thing, ensures proper encoding of boundaryline
                processData: false,
                error: function() {
                    elgg.system_message('Save failed!');
                },
                success: function(data) {
                    data = runtime.fromJson(data);
                    elgg.system_message('Save result: '+data.status);
                    if (data.system_messages.error.length > 0) {
                        elgg.system_message('Save error: '+data.system_messages.error[0]);
                    }
                    if (data.system_messages.success.length > 0) {
                        elgg.system_message('Save success: '+data.system_messages.success[0]);
                    }
                }
            });
        });
    }

    var editorConfig = {
        allFeaturesEnabled: true,
        saveCallback: save,
        userData: {
            fullName: elgg.get_logged_in_user_entity().name
        }
    };
    var odtEditorDiv = document.getElementById("odt_editor");
    var documentUrl = odtEditorDiv && odtEditorDiv.getAttribute("data-document-url");
    var guid = odtEditorDiv && odtEditorDiv.getAttribute("data-guid");

    Wodo.createTextEditor("odt_editor", editorConfig, function (err, e) {
        editor = e;
        editor.openDocumentFromUrl(documentUrl, function(err) {
            if (err) {
                elgg.register_error(err);
            }
        });
    });
}

//register init hook
elgg.register_hook_handler("init", "system", elgg.odt_editor.init);
