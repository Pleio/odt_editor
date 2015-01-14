<?php
/**
 * ODT Editor Save-as form
 *
 * @package odt_editor
 */

$old_file_guid = elgg_extract('file_guid', $vars, null);
$old_file = new ElggFile($old_file_guid);

$title = $old_file ? $old_file->title : "";
$tags = $old_file ? $old_file->tags : array();
$access_id = $old_file ? $old_file->access_id : ACCESS_DEFAULT;


$form_body .= '<div>';
$form_body .= '<label>' . elgg_echo('title') . '</label><br />';
$form_body .= elgg_view('input/text', array('name' => 'title', 'value' => $title));
$form_body .= '</div>';

$form_body .= '<div>';
$form_body .= '<label>' . elgg_echo('tags') . '</label>';
$form_body .= elgg_view('input/tags', array('name' => 'tags', 'value' => $tags));
$form_body .= '</div>';

$form_body .= '<div>';
$form_body .= '<label>' . elgg_echo('access') . '</label><br />';
$form_body .= elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id));
$form_body .= '</div>';

$form_body .= '<div class="elgg-foot">';
$form_body .= elgg_view('input/submit', array('value' => elgg_echo('odt_editor:saveas'), "class" => "elgg-button-submit mtm"));
$form_body .= '</div>';

$body = elgg_view('input/form', array('id'     => 'odt_editor_form_saveas', 
                                      'name'   => 'odt_editor_form_saveas', 
                                      'action' => 'javascript:elgg.odt_editor.doSaveAs($(\'#odt_editor_form_saveas\'))',
                                      'body'   => $form_body));

echo elgg_view_module('popup', elgg_echo('odt_editor:title:saveas'), $body);