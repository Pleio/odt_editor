<?php
/**
 * ODT editor plugin hook callback functions
 *
 * @package odt_editor
 */

/**
 * Take over the file page handler if this is some ODT file belonging to a group
 *
 * @param string $hook         the 'route' hook
 * @param string $type         for the 'file' page handler
 * @param bool   $return_value tells which page is handled, contains:
 *               $return_value['handler'] => requested handler
 *               $return_value['segments'] => url parts ($page)
 * @param null   $params       no params provided
 *
 * @return bool false if we take over the page handler
 */
function odt_editor_route_file_handler($hook, $type, $return_value, $params) {
    $result = $return_value;

    if (!empty($return_value) && is_array($return_value)) {
        $page = $return_value['segments'];

        if ($page[0] == "view") {
            $file = get_entity($page[1]);
            // an ODT file?
            if ($file &&
                ($file instanceof ElggFile) &&
                ($file->getMimeType() == "application/vnd.oasis.opendocument.text")) {

                // show in WebODF editor page
                set_input('guid', $page[1]);
                include(dirname(dirname(__FILE__)) . "/pages/file/odt_editor.php");
                $result = false;
            }
        }
    }

    return $result;
}

/**
 * Add a button to the file page to allow the user to create a new document
 *
 * @param string $hook         the 'route' hook
 * @param string $type         for the 'file' page handler
 * @param bool   $return_value tells which page is handled, contains:
 *               $return_value['handler'] => requested handler
 *               $return_value['segments'] => url parts ($page)
 * @param null   $params       no params provided
 */
function odt_editor_file_menu_title_hook($hook, $type, $return_value, $params) {
    if (elgg_in_context('file')) {
        $return_value[] = ElggMenuItem::factory(array(
            "name" => "view",
            "text" => elgg_echo('odt_editor:newdocument'),
            "class" => "elgg-button elgg-button-action",
            "id" => "file_tools_list_new_document_toggle",
            "priority" => 50
        ));
    }
    
    return $return_value;
}
