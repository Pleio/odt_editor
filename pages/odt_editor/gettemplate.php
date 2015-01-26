<?php
/**
 * ODT template file download.
 *
 * @package odt_editor
 */

// fix for IE https issue
header("Pragma: public");

header("Content-type: application/vnd.oasis.opendocument.text");

ob_clean();
flush();
readfile(elgg_get_plugins_path() . 'odt_editor/data/template.odt');
exit;
