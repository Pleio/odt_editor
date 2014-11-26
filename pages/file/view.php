<?php
/**
 * View a file
 */

// need to be logged in
gatekeeper();

//only for group members
// group_gatekeeper();

$file_guid = get_input('guid');
$file = get_entity($file_guid);
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

elgg_load_js('wodotexteditor');
elgg_load_js('elgg.odt_editor');

$download_url = elgg_get_site_url() . "file/download/{$file_guid}";
$content = "<div id=\"odt_editor\" style=\"width: 100%;height: 600px;\" data-document-url=\"$download_url\" data-guid=\"$file_guid\"></div>";

# build page
$body = elgg_view_layout('content', array(
    'content' => $content,
    'title' => $title,
    'filter' => '',
));

# draw page
echo elgg_view_page($title, $body);
