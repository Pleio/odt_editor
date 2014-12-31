<?php
/**
 * ODT file uploader/edit action
 *
 * @package odt_editor
 */

// Get variables
$file_guid = (int) get_input('file_guid');
$user_guid = elgg_get_logged_in_user_guid();

# TODO: just copied and reduced code to get started, not developed yet!
# TODO: what about other properties like title, tags, etc?
# also edit? and what about syncing with similar metadata in the ODT file itself?

// load original file object
$file = new ElggFile($file_guid);
if (!$file) {
    register_error(elgg_echo('file:cannotload'));
    forward(REFERER);
}

// user must be able to edit file
if (!$file->canEdit()) {
    register_error(elgg_echo('file:noaccess'));
    forward(REFERER);
}

if ($file->odt_editor_lock_user != $user_guid) {
    $locking_user_guid = (int)$file->odt_editor_lock_user;
    $locking_user = get_entity($locking_user_guid);
    $locking_user_name = $locking_user ? $locking_user->name : elgg_echo("odt_editor:unknown_user");
    register_error(elgg_echo('odt_editor:file:cannotwrite_lock_lost_to', array($locking_user_name)));
    forward(REFERER);
}

// previous file, delete it
$filename = $file->getFilenameOnFilestore();
if (file_exists($filename)) {
    unlink($filename);
}

$file->setMimeType("application/vnd.oasis.opendocument.text");
$file->simpletype = "document";

// Open the file to guarantee the directory exists
$file->open("write");
$file->close();
move_uploaded_file($_FILES['upload']['tmp_name'], $file->getFilenameOnFilestore());

$file->save();

system_message(elgg_echo("file:saved"));
