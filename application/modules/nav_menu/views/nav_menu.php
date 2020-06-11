<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('check_active')) {
	/**
	 * Checks whether the item is active
	 *
	 * @param string $pattern = Pattern to check against the current URI
	 * @return bool = TRUE if the pattern is not empty and matches
	 */
	function check_active(string $pattern) : bool
	{
		$uri = $_SERVER['REQUEST_URI'];
		return !empty($pattern) && preg_match($pattern, $uri);
	}
}
if (!function_exists('echo_nav_link')) {
	/**
	 * Displays a navigation link. Created to prevent copy-pasting once someone
	 * figures out how to align part of the navigation bar to the right.
	 * 
	 * As a bonus it works with array_walk, removing the need for a foreach.
	 *
	 * @param array $navLink = Link to display.
	 * @return void
	 */
	function echo_nav_link(array $navLink)
	{ ?>
		<li class="<?=$navLink['li_class'];?>">
			<a href="<?=base_url($navLink['link']);?>"
				class="<?=$navLink['a_class'];?>">
				<?=lang($navLink['text']);?>
			</a>
		</li>
	<?php }
}
$CI =& get_instance();
$CI->config->load('nav_menu/MY_nav_menu_config', FALSE, TRUE);
// Load navigation links
$navLinks = $CI->config->item('nav');
if (empty($navLinks)) return;
// Remove links when the condition is FALSE
$navLinks = array_filter($navLinks, function($nl) {
	return isset($nl['cond'], $nl['link'], $nl['text']) && $nl['cond']();
});
// Prepare css for parsing
array_walk($navLinks, function(&$nl) {
	$nl['li_class'] = trim('nav-item'.(check_active($nl['pattern'] ?? '')?' active ':' ').implode(' ', $nl['liClasses']));
	$nl['a_class'] = trim('nav-link '.implode(' ', $nl['aClasses'] ?? []));
});
?>
<nav id="my-navbar" class="container">
	<div class="row">
		<ul class="navbar navbar-nav navbar-expand-md w-100 justify-content-start">
			<?php array_walk($navLinks, 'echo_nav_link'); ?>
		</ul>
	</div>
</nav>
