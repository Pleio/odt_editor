<?php
/**
 * Save an ODT file as another file
 *
 * @package odt_editor
 */

// need to be logged in
gatekeeper();

//only for group members
group_gatekeeper();

$file_guid = (int) get_input('guid');

echo elgg_view("odt_editor/forms/saveas", array("file_guid" => $file_guid));
