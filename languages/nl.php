<?php
/**
 * ODT editor translations
 *
 * @package odt_editor
 * @author Bart Jeukendrup <bart@jeukendrup.nl>
 */

    $dutch = array(
        // general
        'odt_editor:error:file_removed' => "Het gewijzigde bestand is verwijderd van de server.",
        'odt_editor:read_only' => "Dit document is geopend in alleen-lezen modus omdat je geen schrijfrechten hebt.",
        'odt_editor:unknown_user' => "Onbekende gebruiker",
        'odt_editor:lock_lost_to' => "Het bestand is geopend door: %s.",
        'odt_editor:lock_lost_to_self' => "De bestandsvergrendeling overgenomen door een andere sessie van jezelf.",
        'odt_editor:file:cannotwrite_lock_lost_to' => "Kan het bestand niet opslaan. Het bestand is op dit moment geopend door: %s.",
        'odt_editor:file:cannotwrite_lock_lost_to_self' => "Kan het bestand niet opslaan. Het bestand is nogmaals geopend door jezelf.",
        'odt_editor:document_locked_by' => "Het bestand is op dit moment geopend door %s en kan niet opgeslagen worden.",
        'odt_editor:document_locked_by_self' => "Het bestand is momenteel vergrendeld door een andere sessie van jezelf.",
        'odt_editor:error:cannotwritelock' => "Kan het bestand niet vergrendelen.",
        'odt_editor:error:cannotrefreshlock_servernotreached' => "De vergrendeling van het bestand kan niet ververst worden: kan geen verbinding maken met de server.",
        'odt_editor:lock_restored' => "Bestandsvergrendeling is hersteld.",
        'odt_editor:error:cannotwritefile_servernotreached' => "Kan niet schrijven naar het bestand: kan geen verbinding maken met de server.",
        'odt_editor:unsaved_changes_exist' => "Dit bestand bevat niet-opgeslagen wijzigingen.",
        'odt_editor:saveas' => "Sla op als nieuw document",
        'odt_editor:title:saveas' => "​Opslaan als nieuw document.",
        'odt_editor:error:notitleentered' => "De titel ontbreekt.",
        'odt_editor:newdocument' => "Nieuw document",

        '' => ""
    );

    add_translation("nl", $dutch);
