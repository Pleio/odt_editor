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
        refreshFileLockTaskTimeout = 5*60*1000,
        isLockedNeeded = true,
        isFileLockKnown = true,
        lockGuid,
        documentUrl,
        fileGuid,
        fileName,
        containerGuid,
        elggSiteName;

    function urlParam(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }    

    function refreshFileLock(async) {
        if (typeof async === 'undefined') {
            async = true;
        }

        // new document?
        if (fileGuid == 0) {
            // no lock, just trigger next attempt
            refreshFileLockTask.trigger();
            return;
        }

        if (isLockNeeded) {
            var lock_set = 1;
        } else {
            var lock_set = 0;
        }

        elgg.action('odt_editor/refresh_filelock', {
            async: async,
            data: {
                file_guid: fileGuid,
                lock_guid: lockGuid,
                lock_set: lock_set
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

    function saveAsAction() {
        function doSaveAs(form) {
            var title = $(form).find("input[name='title']").val();
            if (!title) {
                elgg.system_message(elgg.echo('odt_editor:error:notitleentered'));
                return;
            }

            $(form).find("input[type='submit']").hide();

            editor.getDocumentAsByteArray(function(err, data) {
                if (err) {
                    elgg.register_error(err);
                    $.fancybox.close();
                    return;
                }

                var title = $(form).find("input[name='title']").val();
                var tags = $(form).find("input[name='tags']").val();
                var access_id = $(form).find("select[name='access_id']").val();
                var folderGuid = $(form).find("select[name='folder_guid']").val();
                var blob = new Blob([data.buffer], {type: "application/vnd.oasis.opendocument.text"});
                var formData = new FormData();

                formData.append("upload", blob);
                formData.append("old_file_guid", fileGuid);
                formData.append("old_lock_guid", lockGuid);
                formData.append("container_guid", containerGuid);
                formData.append("folder_guid", folderGuid);
                formData.append("title", title);
                formData.append("tags", tags);
                formData.append("access_id", access_id);
                var token = {};
                elgg.security.addToken(token);
                Object.keys(token).forEach(function (k) {
                    formData.append(k, token[k]);
                });

                elgg.post("action/odt_editor/upload_asnew", {
                    data: formData,
                    contentType: false, // not "multipart/form-data", false lets browser do the right thing, ensures proper encoding of boundaryline
                    processData: false,
                    error: function() {
                        elgg.system_message(elgg.echo('odt_editor:error:cannotwritefile_servernotreached'));
                        $.fancybox.close();
                    },
                    success: function(data) {
                        var reply, windowTitle;

                        data = runtime.fromJson(data);
                        if (data.system_messages.error.length > 0) {
                            elgg.system_message(data.system_messages.error[0]);
                        }
                        if (data.system_messages.success.length > 0) {
                            elgg.system_message(data.system_messages.success[0]);
                            editor.setDocumentModified(false);
                            // update data for current file
                            reply = data.output;
                            lockGuid = reply.lock_guid;
                            fileGuid = reply.file_guid;
                            documentUrl = reply.document_url;
                            fileName = reply.file_name;
                            // update title of window with new document title
                            // TODO: window title generation depends on "page/elements/head", so pattern could be different
                            // needs better support in elgg
                            document.title = title ? (elggSiteName + ": " + title) : elggSiteName;
                            history.replaceState( {}, "", elgg.get_site_url()+"file/view/"+fileGuid);
                        }
                        $.fancybox.close();
                    }
                });
            });
        }
        $.fancybox({
            href: elgg.get_site_url() + "odt_editor/saveas/" + fileGuid + "?container_guid=" + containerGuid,
            onComplete: function () {
                elgg.odt_editor.doSaveAs = doSaveAs;
                // set focus to title field initially TODO: find better/standard way
                $("#odt_editor_form_saveas input[name='title']").focus();

                // set to current folder
                if (urlParam('folder_guid')) {
                    $("#odt_editor_form_saveas select[name='folder_guid']").val(urlParam('folder_guid'));
                }
            }
        });
    }

    function saveAction() {
        // Temporary prototype code, save button might be rather disabled
        // new document?
        if (fileGuid == 0) {
            saveAsAction();
            return;
        }
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
                        editor.setDocumentModified(false);
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
        saveCallback: saveAction,
        saveAsCallback: saveAsAction,
        downloadCallback: download,
        userData: {
            fullName: elgg.get_logged_in_user_entity().name
        }
    };
    documentUrl = odtEditorDiv && odtEditorDiv.getAttribute("data-document-url");
    fileGuid = odtEditorDiv && odtEditorDiv.getAttribute("data-guid");
    fileName = odtEditorDiv && odtEditorDiv.getAttribute("data-filename");
    containerGuid = odtEditorDiv && odtEditorDiv.getAttribute("data-containerguid");
    lockGuid = odtEditorDiv && odtEditorDiv.getAttribute("data-lockguid");
    elggSiteName = odtEditorDiv && odtEditorDiv.getAttribute("data-sitename");

    Wodo.createTextEditor("odt_editor", editorConfig, function (err, e) {
        editor = e;

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

                window.addEventListener("beforeunload", function (e) {
                    isLockNeeded = false;
                    refreshFileLock(false);

                    // reapply lock when user continues
                    setTimeout(function() {
                        isLockNeeded = true;
                        refreshFileLock(false);
                    }, 100);

                    if (editor.isDocumentModified()) {
                        var confirmationMessage = elgg.echo('odt_editor:unsaved_changes_exist');
                        // Gecko + IE
                        (e || window.event).returnValue = confirmationMessage;
                        // Webkit, Safari, Chrome etc.
                        return confirmationMessage;
                    }
                });
            }

        });
    });
}

//register init hook
elgg.register_hook_handler("init", "system", elgg.odt_editor.init);
