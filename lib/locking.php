<?php
/**
 * ODT editor locking functions
 *
 * @package odt_editor
 */


/**
 * @return string
 */
function odt_editor_locking_create_lock_guid() {
    return (string) time(); // TODO: or some timereset-safer base?
}


/**
 * @param ElggFile $file
 * @return void
 */
function odt_editor_locking_update_lock($file) {
    $file->odt_editor_lock_time = time();
}

/**
 * @param ElggFile $file
 * @param int $user_guid
 * @return string
 */
function odt_editor_locking_create_lock($file, $user_guid) {
    $lock_guid = odt_editor_locking_create_lock_guid();

    $file->odt_editor_lock_time = time();
    $file->odt_editor_lock_user = $user_guid;
    $file->odt_editor_lock_guid = $lock_guid;

    return $lock_guid;
}

/**
 * @param ElggFile $file
 * @return void
 */
function odt_editor_locking_remove_lock($file) {
    unset($file->odt_editor_lock_time);
    unset($file->odt_editor_lock_guid);
    unset($file->odt_editor_lock_user);
}

/**
 * @param ElggFile $file
 * @return string
 */
function odt_editor_locking_lock_guid($file) {
    return $file->odt_editor_lock_guid;
}

/**
 * @param ElggFile $file
 * @return int
 */
function odt_editor_locking_lock_owner_guid($file) {
    return $file->odt_editor_lock_user;
}

/**
 * @param ElggFile $file
 * @return bool
 */
function odt_editor_locking_is_locked($file) {
    // do not lock file if the editor is the same
    if ($file->odt_editor_lock_guid == elgg_get_logged_in_user_guid()) {
        return false;
    }

    // 15 secs longer than lock refresh cycle, to avoid race conditions
    $odt_editor_locking_lock_validity_duration = (5 * 60) + 15; // in seconds

    return (isset($file->odt_editor_lock_time) &&
            (($file->odt_editor_lock_time + $odt_editor_locking_lock_validity_duration) >= time()));
}
