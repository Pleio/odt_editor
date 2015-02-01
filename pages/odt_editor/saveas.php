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
$container_guid = (int) get_input('container_guid');

$container = get_entity($container_guid);
if ($container instanceof ElggGroup) {
	elgg_set_page_owner_guid($container->guid);
}

echo elgg_view("odt_editor/forms/saveas", array("file_guid" => $file_guid));
