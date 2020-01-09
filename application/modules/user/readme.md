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

## Database and models ##

### user ###

This table represents the users.  
It stores the users' ids, user types, usernames, passwords (hashed), whether they are active and dates of creation.

### user_type ###

This table represents the user types.  
It stores the user types' ids, names and access_levels.

## Dependencies ##

The module cannot work correctly without CodeIgniter sessions.

## Built With ##

- [CodeIgniter](https://www.codeigniter.com/) - PHP framework
- [CodeIgniter modular extensions HMVC](https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc) - HMVC for CodeIgniter
- [CodeIgniter base model](https://github.com/jamierumbelow/codeigniter-base-model) - Generic model
- [Bootstrap](https://getbootstrap.com/) - To simplify views design

## Authors ##

- **Orif, domaine informatique** - *Creating and following this module* - [GitHub account](https://github.com/OrifInformatique)
