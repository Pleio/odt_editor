<?php
/**
 * ODT editor page body
 *
 * @package odt_editor
 */

// 15 secs longer than lock refresh cycle, to avoid race conditions
$lock_validity_duration = (5 * 60) + 15; // in seconds

// need to be logged in
gatekeeper();

//only for group members
group_gatekeeper();

$file_guid = get_input('guid');
$file = get_entity($file_guid);
// TODO: is there a way to get the original filename when uploaded?
$file_name = $file->getFilename();

$edit_mode = "readonly";
if ($file->canEdit()) {
    // currently locked?
    if (isset($file->odt_editor_lock_time) &&
        $file->odt_editor_lock_time + $lock_validity_duration >= time()) {
        $locking_user_guid = (int)$file->odt_editor_lock_user;
        $locking_user = get_entity($locking_user_guid);
        $locking_user_name = $locking_user ? $locking_user->name : elgg_echo("Unknown user");
        system_message(elgg_echo("Document is currently locked for editing by: %s.", array($locking_user_name)));
    } else {
        $file->odt_editor_lock_time = time();
        $file->odt_editor_lock_user = elgg_get_logged_in_user_guid();
       if ($file->save()) {
            $edit_mode = "readwrite";
        } else {
            register_error(elgg_echo("Could not create editing lock for the file."));
        }
    }
}

$title = $file->title;

elgg_load_js('FileSaver');
elgg_load_js('wodotexteditor');
elgg_load_js('elgg.odt_editor');
elgg_load_css('elgg.odt_editor_dojo_overwrite');

$download_url = elgg_get_site_url() . "file/download/{$file_guid}";

// TODO: the header bar size of 28px should be fetched from somewhere, to support themes
$content = "<div class=\"notranslate\" translate=\"no\" id=\"odt_editor\" style=\"width: 100%;height: calc(100% - 28px); margin-top: 28px; padding: 0;\" data-document-url=\"$download_url\" data-guid=\"$file_guid\" data-filename=\"$file_name\" data-editmode=\"$edit_mode\"></div>";

$body = $content;

# draw page
echo elgg_view_page($title, $body, 'odt_editor');
