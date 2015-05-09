<?php
/**
 * ODT editor JavaScript logic
 *
 * @package odt_editor
 */
?>
//<script>

elgg.file_tools.init_append = function() {
	$('#file_tools_list_new_document_toggle').live('click', elgg.file_tools.new_document);
}

elgg.file_tools.new_document = function(event) {
    event.preventDefault();
    var link = elgg.get_site_url() + "odt_editor/create/" + elgg.get_page_owner_guid();
	var hash = window.location.hash.substr(1);
	if (hash) {
		link += "?folder_guid=" + hash;		
	}

    window.open(link);
}

elgg.register_hook_handler('init', 'system', elgg.file_tools.init_append);