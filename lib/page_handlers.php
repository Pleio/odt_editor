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

    $odt_editor_dir = elgg_get_plugins_path() . 'odt_editor/pages/odt_editor';

    $page_type = $page[0];
    switch ($page_type) {
        case 'saveas':
            set_input('guid', $page[1]);
            include "$odt_editor_dir/saveas.php";
            break;
        default:
            return false;
    }
    return true;
}
