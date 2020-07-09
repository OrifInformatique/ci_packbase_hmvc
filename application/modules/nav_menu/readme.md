# Navigation Menu Module #

Adds a dynamic navigation menu, to remove the need to remake it each time a module is added or removed

## Version 1.0 ##

## Adding a link ##

Add the link with `$config['nav'][] = <values>` in `MY_nav_menu_config.php`.
It can also be added to any other config, but it must be loaded before the navigation menu.

Values is an array, with up to 6 distinct keys.

- The key `cond` must contain a function. The function will return TRUE if the link can be displayed and FALSE otherwise.
- The key `link` must contain a string. It will be passed to `base_url` for the target.
- The key `text` must contain a string. It will be passed to `$this->lang->line` for the text to display.
- The key `pattern` can contain a string. It will be used to check if the link should have the active class. If not specified, the link will never be active.
- The key `aClasses` can contain an array of strings. It will be used for the CSS classes to add to the link. If not specified, no class will be added to the link.
- The key `liClasses` can contain an array of strings. It will be used for the CSS classes to add to the list entry. If not specified, no class will be added to the list entry.

If `cond`, `link`, or `text` are not specified, the link will be skipped.

Example:

```php
$config['nav'][] = [
    'cond' => function() {
        $CI =& get_instance();
        $CI->config->load('user/MY_user_config');
        return $_SESSION['user_access'] >= $CI->config->item('access_lvl_admin');
    },
    'link' => 'user/admin/list_user',
    'text' => 'btn_admin',
    'pattern' => '/user\/admin/'
];
```
