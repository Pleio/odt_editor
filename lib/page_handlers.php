<?php
/**
 * ODT editor plugin hook callback functions
 *
 * @package odt_editor
 */


/**
 * Dispatches odt_editor pages.
 * URLs take the form of
 *  Save as:       odt_editor/saveas/<guid>
 *
 * @param array $page
 * @return bool
 */
function odt_editor_page_handler($page) {

    $odt_editor_pages_dir = elgg_get_plugins_path() . 'odt_editor/pages';

    $page_type = $page[0];
    switch ($page_type) {
        case 'saveas':
            set_input('guid', $page[1]);
            include "$odt_editor_pages_dir/odt_editor/saveas.php";
            break;
        case 'create':
            $container = get_entity($page[1]);

            if (!$container) {
                $container = get_loggedin_userid();
            }

            // show new document in WebODF editor page
            // 0 as indicator for new document
            set_input('guid', 0);
            set_input('container_guid', $page[1]);
            include "$odt_editor_pages_dir/file/odt_editor.php";
            $result = false;

            break;
        case 'gettemplate':
             include "$odt_editor_pages_dir/odt_editor/gettemplate.php";
           break;
        default:
            return false;
    }
    return true;
}
