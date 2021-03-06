<?php
/**
 * River layout area layout
 *
 * @uses $vars['content']        HTML of main content area
 * @uses $vars['sidebar']        HTML of the sidebar
 * @uses $vars['sidebar_alt'] Optional content that is displayed in the alternate sidebar
 * @uses $vars['header']         HTML of the content area header (override)
 * @uses $vars['nav']            HTML of the content area nav (override)
 * @uses $vars['footer']         HTML of the content area footer
 * @uses $vars['filter']         HTML of the content area filter (override)
 * @uses $vars['title']          Title text (override)
 * @uses $vars['context']        Page context (override)
 * @uses $vars['filter_context'] Filter context: everyone, friends, mine
 * @uses $vars['class']          Additional class to apply to layout
 * @uses $vars['group_header']          Additional block for group layout
 */


$context = elgg_extract('context', $vars, elgg_get_context());

$vars['title'] = elgg_extract('title', $vars, '');
if (!$vars['title'] && $vars['title'] !== false) {
	$vars['title'] = elgg_echo($context);
}

// 1.8 supported 'filter_override'
if (isset($vars['filter_override'])) {
	$vars['filter'] = $vars['filter_override'];
}

// register the default content filters
if (!isset($vars['filter']) && elgg_is_logged_in() && $context) {
	$username = elgg_get_logged_in_user_entity()->username;
	$filter_context = elgg_extract('filter_context', $vars, 'all');

	// generate a list of default tabs
	$tabs = array(
		'all' => array(
			'text' => elgg_echo('all'),
			'href' => (isset($vars['all_link'])) ? $vars['all_link'] : "$context",
			'selected' => ($filter_context == 'all'),
			'priority' => 200,
		),
		'mine' => array(
			'text' => elgg_echo('mine'),
			'href' => (isset($vars['mine_link'])) ? $vars['mine_link'] : "$context/owner/$username",
			'selected' => ($filter_context == 'mine'),
			'priority' => 300,
		),
		'friend' => array(
			'text' => elgg_echo('friends'),
			'href' => (isset($vars['friend_link'])) ? $vars['friend_link'] : "$context/friends/$username",
			'selected' => ($filter_context == 'friends'),
			'priority' => 400,
		),
	);

	foreach ($tabs as $name => $tab) {
		$tab['name'] = $name;
		elgg_register_menu_item('filter', $tab);
	}
}

$filter = elgg_view('page/layouts/elements/filter', $vars);
$vars['content'] = $filter . $vars['content'];

$class = 'elgg-layout elgg-layout-two-sidebar clearfix';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}
?>

<div class="<?php echo $class; ?>">

	<div class="elgg-sidebar-alt">
		<?php
			echo elgg_view('page/elements/sidebar_alt', $vars);
		?>
	</div>

	<div class="elgg-head clearfix">
		<?php echo $vars['group_header']; ?>
	</div>

	<div class="elgg-main elgg-body">
		<?php
			echo elgg_extract('nav', $vars, elgg_view('navigation/breadcrumbs'));

			//echo elgg_view('page/layouts/elements/header', $vars);

			echo $vars['content'];

			echo elgg_view('page/layouts/elements/footer', $vars);
		?>
	</div>

	<div class="elgg-sidebar hidden" pushedBy="$(this).parent().children('.elgg-head')">
		<?php
			// With the mobile experience in mind, the content order is changed in aalborg theme,
			// by moving sidebar below main content.
			// On smaller screens, blocks are stacked in left to right order: content, sidebar.
			echo elgg_view('page/elements/sidebar', $vars);
		?>
	</div>

</div>
