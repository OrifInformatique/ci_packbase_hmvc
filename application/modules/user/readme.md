# user module #

This module contains every element needed for user administration and authentication.

## Configuration ##

This section describes the module's configurations available in the config directory.

### Access levels ###

Defines the access levels in powers of 2.  
By default, guest, registered, and admins are defined.

### Validation rules ###

Defines the min/max length of usernames and passwords.

### password_hash_algorithm ###

Defines the algorithm to use for password hashing.  
Does not automatically updates the database.

## Public functions ##

This section describes the public functions callable from another module.

### login ###

Display a login form, check login information and create session variables.

### logout ###

Reset session and redirect to the homepage.

### change_password ###

Display a form to change the current user's password.

### old_password_check ###

Callback for CodeIgniter to make sure the old password entered matches the actual old password.

Arguments:

1. `$pwd` (string): The old user's password entered.
2. `$user` (string): The user's name.

Returns:

- boolean: TRUE if the password matches, FALSE otherwise

### user_index ###

Display the list of users. Hides the archived users by default.  
Admin-only.

Arguments:

1. `$with_deleted` (boolean), defaults to FALSE: Whether the archived users are to be shown alongside the normal users.

### user_add ###

Display a form to add a new user, or to edit an existing user.

Arguments:

1. `$user_id` (integer), defaults to 0: The id of the user to update. Leave 0 to create a new user.
2. `$old_values` (array), defaults to []: The previously entered values.

### user_form ###

Validate user_add input.

### user_delete ###

Delete or deactivate an user.

Arguments:

1. `$user_id` (integer): The id of the user to delete/deactivate.
2. `$action` (integer), defaults to 0: The action to take.
   1. 0 to display the confirmation
   2. 1 to deactivate the user
   3. 2 to delete the user
   4. Anything else to cancel

### user_reactivate ###

Reactivate an user.

Arguments:

1. `$user_id` (integer): The id of the user to reactivate

### user_password_change ###

Display a form to change an user's password.

Arguments:

1. `$user_id` (integer): The id of the user whose password will be changed

### user_password_change_form ###

Validates user_password_change input.

### cb_unique_user ###

Callback for CodeIgniter to make sure the username is not taken by another user.

Arguments:

1. `$username` (string): The name to check for.
2. `$user_id` (integer): The id of the current user, in case there is no name change.

Returns:

- boolean: TRUE if the username is free (or used by the user being updated), FALSE otherwise.

### cb_not_null_user ###

Callback for CodeIgniter to make sure the user_id has a related user.

Arguments:

1. `$user_id` (integer): The id of the user to check for, 0 if the user does not exist.

Returns:

- boolean: TRUE if the id is 0 or is linked to an user, FALSE otherwise.

### cb_not_null_user_type ###

Callback for CodeIgniter to make sure the user_type_id has a related user type.

Arguments:

1. `$user_type_id` (integer): The id of the user type to check for.

Returns:

- boolean: TRUE if the user type exists, FALSE otherwise.

## Database and models ##

This section describes the database tables needed for this module, the corresponding models and eventual particularities.

## Dependencies ##

No dependencies for this module other than the libraries in "Built with" section.

## Built With ##

- [CodeIgniter](https://www.codeigniter.com/) - PHP framework
- [CodeIgniter modular extensions HMVC](https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc) - HMVC for CodeIgniter
- [CodeIgniter base model](https://github.com/jamierumbelow/codeigniter-base-model) - Generic model
- [Bootstrap](https://getbootstrap.com/) - To simplify views design

## Authors ##

- **Orif, domaine informatique** - *Creating and following this module* - [GitHub account](https://github.com/OrifInformatique)
