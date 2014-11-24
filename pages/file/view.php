<?php
/**
 * View a file
 */

/**
 * This possible should be elsewhere and done differently
 *
 * @param string $div_id         id to use for the DOM element which holds the editor
 * @param string $download_url   url from where to get the document
 *
 * @return string which is the snippet
 */
function view_wodo_init($div_id, $download_url) {
    elgg_load_js('wodotexteditor');
    $fullname = elgg_get_logged_in_user_entity()->name;

    return
"<script type=\"text/javascript\" charset=\"utf-8\">
function setupReportEditor() {
    editorConfig = {
        allFeaturesEnabled: true,
        userData: {
            fullName: \"$fullname\"
        }
    };
    Wodo.createTextEditor(\"$div_id\", editorConfig, function (err, editor) {
        editor.openDocumentFromUrl(\"$download_url\", function(err) {
        });
    });
}
window.setTimeout(setupReportEditor, 0);
  </script>
<div id=\"$div_id\" style=\"width: 100%;height: 600px;\"></div>";
}


$file = get_entity(get_input('guid'));

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

$download_url = elgg_get_site_url() . "file/download/{$file->getGUID()}";
$content = view_wodo_init("odf", $download_url);

elgg_register_menu_item('title', array(
    'name' => 'download',
    'text' => elgg_echo('file:download'),
    'href' => "file/download/$file->guid",
    'link_class' => 'elgg-button elgg-button-action',
));

$body = elgg_view_layout('content', array(
    'content' => $content,
    'title' => $title,
    'filter' => '',
));

echo elgg_view_page($title, $body);