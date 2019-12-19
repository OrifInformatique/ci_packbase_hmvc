# Conventions in this project #

In addition to [CodeIgniter's conventions](https://codeigniter.com/user_guide/general/styleguide.html), we use our own additional conventions.  
Functions and classes should be documented with DocBlock, or any equivalent.  
Constants should be CAPITALIZED_SNAKE_CASE.

## Ignored CodeIgniter conventions ##

There is no need to use `OR` instead of `||`.  
Usage of `<?=$var;?>` is recommended, as CodeIgniter recommends at least 5.6, meaning it should work anyway.

## HTML/PHP and CSS ##

Element ids and classes are in kebab-case.

## CSS ##

Files are stored in */assets/css*.  
Filenames start with *MY_* and end with *.css*.

## Javascript ##

Variables and functions are named in camelCase.  
Variables should be declared using `let` or `const` instead of `var`.  
Classes are named in PascalCase.  
Files are stored in */assets/js*.  
Filenames start with *MY_* and end with *.js*.

## Images ##

Images are stored in */assets/images*.

## PHP (in general) ##

Variables are in camelCase.  
Functions are in snake_case.  
Classes are in Upper_snake_case.

## CodeIgniter ##

### Controllers ###

Are stored in either */application/controllers* or */application/modules/{module name}/controllers*.  
Names are in Upper_snake_case, and should only be a single word.  
Read methods (e.g. a list) should start with `list_` for the list, or `detail_` for a specific item, both followed by the type in singular (e.g. `list_user`).  
Create and update methods should start with `form_` for displaying the form, and `validation_` for validating the input, both followed by the type in singular.  
Delete methods should start with `delete_`, also followed by the type.  
If there is only one type, simply use `list`, `detail`, `form`, `validation`, and `delete`.  
`index` should call the first list.  
CI callbacks for form_validation must start with `cb_`.

### Models ###

Names are in snake_case, and end with *_model*.  
Custom create methods should start with `insert_`, read methods with `get_`, update methods with `update_`, and delete methods with `delete_`.

### Views ###

Names are in snake_case, must have the name of the method calling them with *.php* at the end (e.g. *list_user.php*).  
Whenever possible, use `<?=$var;?>` instead of `<?php echo $var; ?>`.

### Lang ###

Use prefixes and suffixes accordingly.  
If you need to use more than one, put the highest first.  
Feel free to expand this list.

#### Prefixes ####

| Represents    | Prefix    |
| ------------- | --------- |
| Page title    | title_    |
| Nav link      | nav_      |
| Field label   | field_    |
| Placeholder   | phd_      |
| Button        | btn_      |
| Message       | msg_      |
| Error         | err_      |

#### Suffixes ####

| Represents    | Suffix    |
| ------------- | --------- |
| Confirmation  | _confirm  |

## User Interface ##

All pages must be displayed alongside the views in the common module.  
The order is *header.php*, *login_bar.php*, the view(s), *footer.php*.

### Lists ###

Must be a table with the `table` class.  
The table headers are in a thead.  
The create button must be at the top left of the list.

### Forms ###

Each input and label is in a div using `form-group`, which is in another div using `row`.  
They are stacked on each other.  
Inputs use `form-control`.

### Buttons ###

Cancel buttons use the `btn-default` class, confirm/create buttons use the `btn-primary`.  
Dangerous buttons use the `btn-danger`.  
All buttons use the `btn` class.  
Buttons do not use Bootstrap `col`s.
