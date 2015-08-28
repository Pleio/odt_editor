<?php
/**
 * ODT editor translations
 *
 * @package odt_editor
 */

    $english = array(
        // general
        'odt_editor:error:file_removed' => "The file that is edited has been removed on the system.",
        'odt_editor:read_only' => "This document is opened in read-only mode as you don't have write access.",
        'odt_editor:unknown_user' => "Unknown user",
        'odt_editor:lock_lost_to' => "The editing lock has been lost to: %s.",
        'odt_editor:lock_lost_to_self' => "The editing lock has been lost to another session of you.",
        'odt_editor:file:cannotwrite_lock_lost_to' => "Cannot write the file. The editing lock has been lost to: %s.",
        'odt_editor:file:cannotwrite_lock_lost_to_self' => "Cannot write the file. The editing lock has been lost to another session of you.",
        'odt_editor:document_locked_by' => "Document is currently locked for editing by: %s.",
        'odt_editor:document_locked_by_self' => "Document is currently locked for editing by another session of you.",
        'odt_editor:error:cannotwritelock' => "Could not create editing lock for the file.",
        'odt_editor:error:cannotrefreshlock_servernotreached' => "Editing lock could not be refreshed: error on talking to server.",
        'odt_editor:lock_restored' => "Editing lock is restored.",
        'odt_editor:error:cannotwritefile_servernotreached' => "The file could not be written: error on talking to server.",
        'odt_editor:unsaved_changes_exist' => "There are unsaved changes to the file.",
        'odt_editor:saveas' => "Save as new document",
        'odt_editor:title:saveas' => "Save as a new document.",
        'odt_editor:error:notitleentered' => "Please enter a title.",
        'odt_editor:newdocument' => "New document",

        '' => ""
    );

    add_translation("en", $english);
