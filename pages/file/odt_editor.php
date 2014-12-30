<?php
/**
 * View a file
 */

// need to be logged in
gatekeeper();

//only for group members
group_gatekeeper();

$file_guid = get_input('guid');
$file = get_entity($file_guid);
// TODO: is there a way to get the original filename when uploaded?
$file_name = $file->getFilename();
$owner = elgg_get_page_owner_entity();

elgg_push_breadcrumb(elgg_echo('file'), 'file/all');

$crumbs_title = $owner->name;
if (elgg_instanceof($owner, 'group')) {
    elgg_push_breadcrumb($crumbs_title, "file/group/$owner->guid/all");
} else {
    elgg_push_breadcrumb($crumbs_title, "file/owner/$owner->username");
}

$title = $file->title;

elgg_push_breadcrumb($title);

elgg_load_js('FileSaver');
elgg_load_js('wodotexteditor');
elgg_load_js('elgg.odt_editor');
elgg_load_css('elgg.odt_editor_dojo_overwrite');

$edit_mode = get_input('odt_editor_mode');

$download_url = elgg_get_site_url() . "file/download/{$file_guid}";
// TODO: the header bar size of 28px should be fetched from somewhere, to support themes
$content = "<div class=\"notranslate\" translate=\"no\" id=\"odt_editor\" style=\"width: 100%;height: calc(100% - 28px); margin-top: 28px; padding: 0;\" data-document-url=\"$download_url\" data-guid=\"$file_guid\" data-filename=\"$file_name\" data-editmode=\"$edit_mode\"></div>";

$body = $content;

# draw page
echo elgg_view_page($title, $body, 'odt_editor');
