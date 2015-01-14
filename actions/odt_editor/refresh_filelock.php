<?php
/**
 * ODT file lock refresh action
 *
 * @package odt_editor
 */

elgg_load_library('odt_editor:locking');

// Get variables
$file_guid = (int) get_input('file_guid');
$lock_guid = get_input('lock_guid');
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
if (odt_editor_locking_lock_guid($file) != $lock_guid) {
    if ($lock_set) {
        $lock_owner_guid = odt_editor_locking_lock_owner_guid($file);
        if ($lock_owner_guid != $user_guid) {
            $locking_user = get_entity($lock_owner_guid);
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
    odt_editor_locking_update_lock($file);
} else {
    odt_editor_locking_remove_lock($file);
}

$file->save();
