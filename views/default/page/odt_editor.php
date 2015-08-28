<?php
/**
 * ODT editor pageshell
 *
 * @package odt_editor
 *
 * @uses $vars['body']        The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
 */

// render content before head so that JavaScript and CSS can be loaded. See #4032
$topbar = elgg_view('page/elements/topbar', $vars);
$messages = elgg_view('page/elements/messages', array('object' => $vars['sysmessages']));
$header = elgg_view('page/elements/header', $vars);
$body = elgg_view('page/elements/body', $vars);

// Set the content type
header("Content-type: text/html; charset=UTF-8");

$lang = get_current_language();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $lang; ?>" lang="<?php echo $lang; ?>" style="width:100%; height:100%;">
<head>
<?php echo elgg_view('page/elements/head', $vars); ?>
</head>
<body style="width:100%; height:100%;">
<div style="width:100%; height:100%;">
	<?php if (elgg_is_logged_in()){ ?>
	<div class="elgg-page-topbar">
		<div class="elgg-inner">
			<?php echo $topbar; ?>
		</div>
	</div>
	<?php } ?>

	<div class="elgg-page-messages">
		<?php echo $messages; ?>
	</div>

	<div class="elgg-page-header">
		<div class="elgg-inner">
			<?php echo $header; ?>
		</div>
	</div>
			<?php echo $body; ?>
</div>
</body>
</html>
