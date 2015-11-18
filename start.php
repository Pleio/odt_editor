<?php
/**
 * Start file for this plugin
 *
 * @package odt_editor
 */

require_once(dirname(__FILE__) . "/lib/hooks.php");
require_once(dirname(__FILE__) . "/lib/page_handlers.php");

elgg_register_event_handler('init', 'system', 'odt_editor_init');

function odt_editor_init() {
    elgg_register_library('odt_editor:locking', elgg_get_plugins_path() . 'odt_editor/lib/locking.php');

    // TODO: why does  elgg_get_plugins_path()  not work here?
    elgg_register_js('FileSaver', '/mod/odt_editor/vendors/FileSaver.js');
    elgg_register_js('wodotexteditor', '/mod/odt_editor/vendors/wodotexteditor/wodotexteditor.js');

    elgg_register_js('elgg.odt_editor', elgg_get_simplecache_url('js', 'odt_editor'));

    // extend js
    elgg_extend_view("js/elgg", "js/file_tools");

    elgg_register_simplecache_view('js/odt_editor');

    // extend file page handler
    elgg_register_plugin_hook_handler("route", "file", "odt_editor_route_file_handler");
    
    // extend file page menu
    if (elgg_is_active_plugin('file_tools')) {
        elgg_register_plugin_hook_handler("register", "menu:title", "odt_editor_file_menu_title_hook");
    }

    elgg_register_action("odt_editor/upload", elgg_get_plugins_path() . "odt_editor/actions/odt_editor/upload.php");
    elgg_register_action("odt_editor/upload_asnew", elgg_get_plugins_path() . "odt_editor/actions/odt_editor/upload_asnew.php");
    elgg_register_action("odt_editor/refresh_filelock", elgg_get_plugins_path() . "odt_editor/actions/odt_editor/refresh_filelock.php");

    // fight Dojo css ruining some of elgg styling
    elgg_register_css('elgg.odt_editor_dojo_overwrite', elgg_get_simplecache_url('css', 'odt_editor_dojo_overwrite'), 1000);
    elgg_register_simplecache_view('css/odt_editor_dojo_overwrite');

    elgg_register_page_handler("odt_editor", "odt_editor_page_handler");
}
