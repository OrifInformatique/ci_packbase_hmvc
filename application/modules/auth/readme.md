# auth module

This module contains every elements needed for user authentication.

## Configuration
This section describes the module's configurations available in the config directory.

### Access levels
Define the different access levels needed for your application.
By default, 3 levels are defined : admin, registered and guest.

### Validation rules
Define the validation rules for the login form.

## Public functions
This section describes the public functions that can be called from another module.

### login
Display a login form, check login informations and create session variables.

### logout
Reset session and redirect to the homepage.

## Dependencies
No dependencies for this module other than the libraries in "Built with" section.

## Built With

* [CodeIgniter](https://www.codeigniter.com/) - PHP framework
* [CodeIgniter base model](https://github.com/jamierumbelow/codeigniter-base-model) - Generic model
* [Bootstrap](https://getbootstrap.com/) - To simplify views design

## Authors

* **Orif, domaine informatique** - *Creating and following this module* - [GitHub account](https://github.com/OrifInformatique)
