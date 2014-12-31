<?php
/**
 * ODT file lock refresh action
 *
 * @package odt_editor
 */

// Get variables
$file_guid = (int) get_input('file_guid');
$lock_guid = (int) get_input('lock_guid');
$lock_set = ((int) get_input('lock_set')) == 1;
$user_guid = elgg_get_logged_in_user_guid();

// load original file object
$file = new ElggFile($file_guid);
if (!$file) {
    if ($lock) {
        register_error(elgg_echo('odt_editor:error:file_removed'));
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
if ($file->odt_editor_lock_guid != $lock_guid) {
    if ($lock_set) {
        if ($file->odt_editor_lock_user != $user_guid) {
            $locking_user_guid = (int)$file->odt_editor_lock_user;
            $locking_user = get_entity($locking_user_guid);
            $locking_user_name = $locking_user ? $locking_user->name : elgg_echo("odt_editor:unknown_user");
            register_error(elgg_echo('odt_editor:lock_lost_to', array($locking_user_name)));
        } else {
            register_error(elgg_echo('odt_editor:lock_lost_to_self'));
        }
    }
    forward(REFERER);
}

// update lock time
if ($lock_set) {
    $file->odt_editor_lock_time = time();
} else {
    unset($file->odt_editor_lock_time);
    unset($file->odt_editor_lock_guid);
    unset($file->odt_editor_lock_user);
}

$file->save();
