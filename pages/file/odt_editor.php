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
$file = get_entity($file_guid);
// TODO: is there a way to get the original filename when uploaded?
$file_name = $file->getFilename();
$user_guid = elgg_get_logged_in_user_guid();

$edit_mode = "readonly";
if ($file->canEdit()) {
    // currently locked?
    if (odt_editor_locking_is_locked($file)) {
        $lock_owner_guid = odt_editor_locking_lock_owner_guid($file);
        if ($lock_owner_guid != $user_guid) {
            $locking_user = get_entity($lock_owner_guid);
            $locking_user_name = $locking_user ? $locking_user->name : elgg_echo("odt_editor:unknown_user");
            system_message(elgg_echo("odt_editor:document_locked_by", array($locking_user_name)));
        } else {
            register_error(elgg_echo('odt_editor:document_locked_by_self'));
        }
    } else {
        $lock_guid = odt_editor_locking_create_lock($file, $user_guid);
        if ($file->save()) {
            $edit_mode = "readwrite";
        } else {
            register_error(elgg_echo("odt_editor:error:cannotwritelock"));
        }
    }
}

$title = $file->title;

elgg_load_js('FileSaver');
elgg_load_js('wodotexteditor');
elgg_load_js('elgg.odt_editor');
elgg_load_css('elgg.odt_editor_dojo_overwrite');
elgg_load_js('lightbox');
elgg_load_css('lightbox');


$download_url = elgg_get_site_url() . "file/download/{$file_guid}";
$sitename = elgg_get_config('sitename');

// TODO: the header bar size of 28px should be fetched from somewhere, to support themes
$content = "<div class=\"notranslate\" translate=\"no\" id=\"odt_editor\" style=\"width: 100%;height: calc(100% - 28px); margin-top: 28px; padding: 0;\" data-document-url=\"$download_url\" data-guid=\"$file_guid\" data-filename=\"$file_name\" data-editmode=\"$edit_mode\" data-lockguid=\"$lock_guid\" data-sitename=\"$sitename\"></div>";

$body = $content;

# draw page
echo elgg_view_page($title, $body, 'odt_editor');
