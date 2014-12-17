<?php
/**
 * The standard HTML head
 *
 * @uses $vars['title'] The page title
 */

// Set title
if (empty($vars['title'])) {
	$title = elgg_get_config('sitename');
} else {
	$title = elgg_get_config('sitename') . ": " . $vars['title'];
}

$js = elgg_get_loaded_js('head');
$css = elgg_get_loaded_css();

?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="ElggRelease" content="<?php echo $release; ?>" />
    <meta name="ElggVersion" content="<?php echo $version; ?>" />
	<title><?php echo $title; ?></title>
    <?php echo elgg_view('page/elements/shortcut_icon', $vars); ?>

<?php foreach ($css as $link) { ?>
    <link rel="stylesheet" href="<?php echo $link; ?>" type="text/css" />
<?php } ?>

<?php
    $ie_url = elgg_get_simplecache_url('css', 'ie');
    $ie7_url = elgg_get_simplecache_url('css', 'ie7');
    $ie6_url = elgg_get_simplecache_url('css', 'ie6');
?>
    <!--[if gt IE 7]>
        <link rel="stylesheet" type="text/css" href="<?php echo $ie_url; ?>" />
    <![endif]-->
    <!--[if IE 7]>
        <link rel="stylesheet" type="text/css" href="<?php echo $ie7_url; ?>" />
    <![endif]-->
    <!--[if IE 6]>
        <link rel="stylesheet" type="text/css" href="<?php echo $ie6_url; ?>" />
    <![endif]-->

<?php foreach ($js as $script) { ?>
	<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>

<script type="text/javascript">
	<?php echo elgg_view('js/initialize_elgg'); ?>
</script>
