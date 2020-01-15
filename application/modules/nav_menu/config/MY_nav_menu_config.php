<?php
/**
 * Config for the navigation menu.
 * 
 * Creating a new entry:
 * 	- Use `$config['nav'][] = [<entry values>];`.
 * 	- 'cond' is a function returning TRUE or FALSE. It decides if the link will
 * 		be shown. Required.
 * 	- 'link' is the target link. It will be given to `base_url` for the full link.
 * 		Required.
 * 	- 'text' is the target text. It will be given to `lang` for the translated text.
 * 		Required.
 * 	- 'pattern' is a pattern to check whether the link has the `active` CSS class.
 * 		It will be checked against the current URI. Leave blank to never have `active`.
 * 		Defaults to ''.
 * 	- 'classes' is an array of CSS classes for the link. Defaults to [].
 */

// In case nothing has been added, this will make sure there is no error.
if (!isset($config['nav'])) {
	$config['nav'] = [];
}
