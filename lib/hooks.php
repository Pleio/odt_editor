<?php
/**
 * All plugin hook callback functions are defined in this file
 *
 * @package odt_editor
 */

/**
 * Take over the file page handler in some cases
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
            if ($file && $file->getMimeType() == "application/vnd.oasis.opendocument.text") {
                set_input('guid', $page[1]);
                include(dirname(dirname(__FILE__)) . "/pages/file/view.php");
                $result = false;
            }
        }
    }

    return $result;
}
