# ODT editor

Allows the online editing of ElggFile objects that are text documents in the
[OpenDocument][odf] format (OpenDocument Text, file extension .odt)


## Features

* opens documents which the user has write rights in an online editor in the browser
* changes to a document can be saved to the original document on the server
* the current state of the edited document can be downloaded to the local computer
* the current state of the edited document can be saved as a new document
* locks a document that is currently opened for editing, so other users cannot
  accidentally edit it at the same time
* new documents can be created by a button in the file list view

The ODT editor uses Wodo.TextEditor from the [WebODF][webodf] project.


## Configuration

This module should run after installation without any setup needed.

There are some customisations possible:


### Template for new ODT documents

The template for new ODT documents is located in
`mod/odt_editor/data/template.odt`. To use an own ODT document as template
replace that file with the own file.

### Fonts

The list of fonts which is offered in the font selectors, next to the
fonts used in the current document, is controlled by the file
`mod/odt_editor/vendors/wodotexteditor/resources/fonts/fonts.css`.

Each font is registered with a normal `@font-face` CSS rule. A font can either
be expected to be installed on the computers where the clients run their
browser or it can be installed on the server, to ensure it is available to
all users.
The font files can be installed on the server whereever it suits, as long as
their location is correctly given in the `fonts.css` file.

#### Example:

There is a font with the font-family name "Gentium Basic". The data of this font is in a file `GenBasR.ttf`.

Because the font should be made available to all users, the file with the font data is installed on the Elgg server.
It has been placed in `mod/odt_editor/vendors/wodotexteditor/resources/fonts`.

So it will be listed in `fonts.css` as

    @font-face {
        font-family: "Gentium Basic";
        src: url("./GenBasR.ttf") format("truetype");
        font-weight: normal;
        font-style: normal;
    }

When relying on the font being installed on the computer of the users, the file with the font data would not be installed on the server.
The snippet for `fonts.css` would be the same as above, but without the `src` line:

    @font-face {
        font-family: 'Gentium Basic';
        font-weight: normal;
        font-style: normal;
    }

## Known issues

None yet, please file any issue you experience.

[odf]: https://en.wikipedia.org/wiki/Opendocument
[webodf]: http://www.webodf.org/
