<?php
/**
 * ODT file lock refresh action
 *
 * @package odt_editor
 */

// Get variables
$file_guid = (int) get_input('file_guid');
$lock = (int) get_input('lock');
$user_guid = elgg_get_logged_in_user_guid();

// load original file object
$file = new ElggFile($file_guid);
if (!$file) {
    if ($lock) {
        register_error(elgg_echo('The file that is edited got removed on the system.'));
    }
    forward(REFERER);
}

// user must be able to edit file
/*
if (!$file->canEdit()) {
    // TODO: also support case that user gets write access again, needs suberror state so client keeps pulling the lock
    // for now just hide this problem, will be obvious when saving is tried, and there the error can be recovered by retrying
    register_error(elgg_echo('You do no longer have write access to the file that is edited.'));
    forward(REFERER);
}
*/

// lock no longer owned?
if ($file->odt_editor_lock_user != $user_guid && $lock) {
    $locking_user_guid = (int)$file->odt_editor_lock_user;
    $locking_user = get_entity($locking_user_guid);
    $locking_user_name = $locking_user ? $locking_user->name : elgg_echo("Unknown user");
    register_error(elgg_echo('The editing lock has been lost to: '.$locking_user_name));
    forward(REFERER);
}

// update lock time
if ($lock) {
    $file->odt_editor_lock_time = time();
} else {
    unset($file->odt_editor_lock_time);
}

$file->save();
