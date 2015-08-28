<?php
/**
 * ODT editor CSS fixups
 *
 * @package odt_editor
 */
?>

body {
    background-color: #fff;
    background-image: none !important;
}

.elgg-page-messages {
    z-index: 1;
    position: absolute;
    margin: 0 auto;
    left: 0;
    right: 0;
}

@namespace office url(urn:oasis:names:tc:opendocument:xmlns:office:1.0);

/* dojo sets margin-top to 1em, which screws the elgg header */
.elgg-inner > h1 {
    margin-top: 0px
}

/* elgg set body to have background-image */
office|body {
    background-color: white;
    background-image: none;
}

/* for some yet to be researched reason elgg's rule ".developers-log *" also affects border-radius this, so enforce it */
.annotationRemoveButton {
    -webkit-border-radius: 10px !important;
    -moz-border-radius: 10px !important;
    border-radius: 10px !important;
}
