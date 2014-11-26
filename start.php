<?php
/**
 * Start file for this plugin
 */

require_once(dirname(__FILE__) . "/lib/hooks.php");

elgg_register_event_handler('init', 'system', 'odt_editor_init');

function odt_editor_init() {
    // TODO: why does  elgg_get_plugins_path()  not work here?
    elgg_register_js('wodotexteditor', '/mod/odt_editor/vendors/wodotexteditor/wodotexteditor.js');

    elgg_register_js('elgg.odt_editor', elgg_get_simplecache_url('js', 'odt_editor'));
    elgg_register_simplecache_view('js/odt_editor');

    // extend file page handler
    elgg_register_plugin_hook_handler("route", "file", "odt_editor_route_file_handler");

    elgg_register_action("odt_editor/upload", elgg_get_plugins_path() . "odt_editor/actions/odt_editor/upload.php");
}
