# admin module
This module contains basic elements for site admins

## Configuration
This section describes the module's configurations available in the config directory.

## Public functions
This section describes the public functions that can be called from another module.

### index
Loads the main index, currently `user_index`

### user_index
Displays the list of users with their access

### user_add
Displays the form to add or update an user

### user_form
Validates the input in `user_add` and updates the database

### user_delete
Displays the form for deleting/deactivating an user and deletes/deactivates an user

### user_password_change
Displays the form for changing an user's password

### user_password_change_form
Validates the input in `user_password_change` and updates the database

### cb_not_null_user
Checks that an user exists

### cb_not_inactive_user
Checks that an user is active

### cb_not_active_user
Checks that an user is inactive

### cb_type_exists
Checks that an user type exists

## Dependencies
No dependencies for this module other than the libraries in "Built with" section.

## Built With
* [CodeIgniter](https://www.codeigniter.com/) - PHP framework
* [CodeIgniter modular extensions HMVC](https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc) - HMVC for CodeIgniter
* [CodeIgniter base model](https://github.com/jamierumbelow/codeigniter-base-model) - Generic model
* [Bootstrap](https://getbootstrap.com/) - To simplify views design

## Authors
* **Orif, domaine informatique** - *Creating and following this module* - [GitHub account](https://github.com/OrifInformatique)