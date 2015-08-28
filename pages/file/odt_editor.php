<?php
/**
 * ODT editor page body
 *
 * @package odt_editor
 */

elgg_load_library('odt_editor:locking');

// need to be logged in
gatekeeper();

//only for group members
group_gatekeeper();

$file_guid = get_input('guid');

// new document?
if ($file == 0) {
    $title = elgg_echo("odt_editor:newdocument");
    $file_name = "document.odt";
    $container_guid = get_input('container_guid');
    $download_url = elgg_get_site_url() . "odt_editor/gettemplate";
    $edit_mode = "readwrite";
} else {
    $file = get_entity($file_guid);
    // TODO: is there a way to get the original filename when uploaded?
    $file_name = $file->getFilename();
    $user_guid = elgg_get_logged_in_user_guid();

    $edit_mode = "readonly";
    if ($file->canEdit()) {
        // save folder guid in parameter to make sure file_tools_object_handler does not overwrite the relationship
        $relationships = get_entity_relationships($file->guid, FILE_TOOLS_RELATIONSHIP, true);
        if (elgg_is_active_plugin('file_tools') && count($relationships) > 0) {
            set_input('folder_guid', $relationships[0]->guid_one);
        }

        // currently locked?
        if (odt_editor_locking_is_locked($file)) {
            $locking_user = get_entity(odt_editor_locking_lock_owner_guid($file));
            $locking_user_name = $locking_user ? $locking_user->name : elgg_echo("odt_editor:unknown_user");

            system_message(elgg_echo("odt_editor:document_locked_by", array($locking_user_name)));
        } else {
            $lock_guid = odt_editor_locking_create_lock($file, $user_guid);
            if ($file->save()) {
                $edit_mode = "readwrite";
            } else {
                register_error(elgg_echo("odt_editor:error:cannotwritelock"));
            }
        }
    } else {
        system_message(elgg_echo("odt_editor:read_only"));
    }

    $title = $file->title;
    $container_guid = $file->container_guid;
    $download_url = elgg_get_site_url() . "file/download/{$file_guid}";
}

elgg_load_js('FileSaver');
elgg_load_js('wodotexteditor');
elgg_load_js('elgg.odt_editor');
elgg_load_css('elgg.odt_editor_dojo_overwrite');
elgg_load_js('lightbox');
elgg_load_css('lightbox');


$sitename = elgg_get_config('sitename');

// TODO: the header bar size of 28px should be fetched from somewhere, to support themes
$content = "<div class=\"notranslate\" translate=\"no\" id=\"odt_editor\" style=\"width: 100%;height: calc(100% - 28px); margin-top: 28px; padding: 0;\" data-document-url=\"$download_url\" data-guid=\"$file_guid\" data-filename=\"$file_name\" data-containerguid=\"$container_guid\" data-editmode=\"$edit_mode\" data-lockguid=\"$lock_guid\" data-sitename=\"$sitename\"></div>";

$body = $content;

# draw page
echo elgg_view_page($title, $body, 'odt_editor');
