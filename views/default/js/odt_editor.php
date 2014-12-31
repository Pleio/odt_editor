<?php
/**
 * ODT editor JavaScript logic
 *
 * @package odt_editor
 */

# Using data-document-url on the editor div to get the url from where to download the document
# TODO: is there another/better way to pass data from views to javascript methods?

?>
//<script>

elgg.provide("elgg.odt_editor");

elgg.odt_editor.init = function() {
    var editor,
        refreshFileLockTask,
        refreshFileLockTaskTimeout = 5*1000,//60*1000,
        isLockedNeeded = true,
        isFileLockKnown = true,
        lockGuid,
        documentUrl,
        fileGuid,
        fileName,
        isDocumentModifed = false;

    function refreshFileLock() {
        elgg.action('odt_editor/refresh_filelock', {
            data: {
                file_guid: fileGuid,
                lock_guid: lockGuid,
                lock_set: isLockedNeeded ? 1 : 0
            },
            error: function() {
                elgg.system_message(elgg.echo('odt_editor:error:cannotrefreshlock_servernotreached'));
                isFileLockKnown = false;
                refreshFileLockTask.trigger();
            },
            success: function(data) {
                if (!isFileLockKnown) {
                    isFileLockKnown = true;
                    if (data.status == 0) {
                        elgg.system_message(elgg.echo('odt_editor:lock_restored'));
                    }
                }
                if (data.status == 0) {
                    refreshFileLockTask.trigger();
                }
            }
        });
    }

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
            formData.append("file_guid", fileGuid);
            formData.append("lock_guid", lockGuid);
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
                    elgg.system_message(elgg.echo('odt_editor:error:cannotwritefile_servernotreached'));
                },
                success: function(data) {
                    data = runtime.fromJson(data);
                    if (data.system_messages.error.length > 0) {
                        elgg.system_message(data.system_messages.error[0]);
                    }
                    if (data.system_messages.success.length > 0) {
                        elgg.system_message(data.system_messages.success[0]);
                        // TODO: isDocumentModifed = false;
                    }
                }
            });
        });
    }

    function downloadOriginal() {
        window.open(documentUrl, '_parent');
    }

    function download() {
        editor.getDocumentAsByteArray(function(err, data) {
            if (err) {
                elgg.register_error(err);
                return;
            }
            var mimetype = "application/vnd.oasis.opendocument.text",
                blob = new Blob([data.buffer], {type: mimetype});
            saveAs(blob, fileName);
        });
    }

    var odtEditorDiv = document.getElementById("odt_editor");
    var isReadonlyMode = odtEditorDiv && odtEditorDiv.getAttribute("data-editmode") === "readonly";

    var editorConfig = isReadonlyMode ? {
        readonlyModeEnabled: true,
        downloadCallback: downloadOriginal,
        userData: {
            fullName: elgg.get_logged_in_user_entity().name
        }
    } : {
        allFeaturesEnabled: true,
        zoomingEnabled: false,
        saveCallback: save,
        downloadCallback: download,
        userData: {
            fullName: elgg.get_logged_in_user_entity().name
        }
    };
    documentUrl = odtEditorDiv && odtEditorDiv.getAttribute("data-document-url");
    fileGuid = odtEditorDiv && odtEditorDiv.getAttribute("data-guid");
    fileName = odtEditorDiv && odtEditorDiv.getAttribute("data-filename");
    lockGuid = odtEditorDiv && odtEditorDiv.getAttribute("data-lockguid");

    Wodo.createTextEditor("odt_editor", editorConfig, function (err, e) {
        editor = e;
        // TODO: need to know about the state relativ to last time this was saved (also respect state by redo/undo)
//         editor.addEventListener(Wodo.EVENT_METADATACHANGED, function() {
//             isDocumentModifed = true;
//         });
        editor.openDocumentFromUrl(documentUrl, function(err) {
            if (err) {
                elgg.register_error(err);
                return;
            }
            // lock the file to avoid editing conflicts
            if (!isReadonlyMode) {
                refreshFileLockTask = core.Task.createTimeoutTask(function () {
                    refreshFileLock();
                }, refreshFileLockTaskTimeout);
                refreshFileLockTask.trigger();
                // be gently and on unloading try to remove the lock
                // TODO: is too late for page reload, as the new page seems requested
                // before the XHR from this handler gets called.
                // no data loss, but not perfect
                window.addEventListener('unload', function(event) {
                    isLockedNeeded = false;
                    refreshFileLockTask.triggerImmediate();
                });
            }

            window.addEventListener("beforeunload", function (e) {
                var confirmationMessage = "no?";

                if (isDocumentModifed) {
                    // Gecko + IE
                    (e || window.event).returnValue = confirmationMessage;
                    // Webkit, Safari, Chrome etc.
                    return confirmationMessage;
                }
            });

        });
    });
}

//register init hook
elgg.register_hook_handler("init", "system", elgg.odt_editor.init);
