<?php
/**
 * Group search form
 *
 * @uses $vars['entity'] ElggGroup
 */

$params = array(
	'name' => 'q',
	'class' => 'elgg-input-search mbl',
	'placeholder' => elgg_echo('groups:search_in_group')
);
echo elgg_view('input/text', $params);

echo elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $vars['entity']->getGUID(),
));

echo elgg_view('input/submit', array(
	'value' => elgg_echo('search:go'),
	'class' => 'hidden'
));
