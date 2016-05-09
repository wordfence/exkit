# WPKit

WPKit is a convenience library for proof of concept code developed by Wordfence. It includes common actions such as logging in as a user with a specific role, returning the path for an endpoint (e.g., admin-ajax.php), grabbing WP nonces, and so on.

## Configuration

Most proofs of concept will require some degree of customization for the host being targeted. Each PoC will automatically prompt as needed for these values, but for repeated testing it may be useful to create a configuration file to pass in to avoid extra typing.

The configuration file is a simple, one-level JSON file. All of the common fields are defined in the file `config-sample.json`, but PoCs may define their own as needed. Using this file when executing a PoC is done with the `--config` command line option. The syntax is `proofofconcept.php --config=/path/to/config.json`.

## Available Functions

This is just a broad overview of the available functions. For any additional details, consult the source code for the documentation and syntax.

---------------------------------------

### Cli

The `Cli` class defines a number of convenience functions for getting and sending information via the console.

#### options

Returns a pre-parsed associative array of all options provided when the script was invoked.

#### write

Writes to the console, optionally with coloring.

#### prompt

Prompts the user for a value, optionally providing a default.

---------------------------------------

### Config

The `Config` class provides a unified interface for getting and storing environment-dependent values. This includes things like user credentials, host URLs, and so on.

#### useConfigurationFile

Merges the given file with the stored value array.

#### get

Returns the value for the desired key, optionally prompting for it or returning a default value.

#### set

Stores the value for the given key.

---------------------------------------

### Endpoint

The `Endpoint` class provides a unified interface for getting the URLs for the common endpoints for PoCs. It causes a prompt for these values if they have not been provided.

#### baseURL

The base URL to the site.

#### loginURL

The login URL for the site. This defaults to the `baseURL` + `/wp-login.php` if not yet known.

#### adminAjaxURL

The admin AJAX URL for the site. This defaults to the `baseURL` + `/wp-admin/admin-ajax.php` if not yet known.

---------------------------------------

### ExitCodes

Defines several exit codes to ensure PoCs use a common set of values.

#### EXIT_CODE_INFORMATIONAL_ONLY

Use if displaying some information only and not running the exploit (e.g., displaying the help message).

#### EXIT_CODE_EXPLOIT_FAILED

Use if the exploit fails.

#### EXIT_CODE_EXPLOIT_SUCCEEDED

Use if the exploit succeeds.

#### EXIT_CODE_FAILED_PRECONDITION

Use if some precondition for running the exploit fails (e.g., invalid login credentials).

#### EXIT_CODE_VALID_REQUEST_FAILED

Use if a test for a valid request fails.

---------------------------------------

### WPAuthentication

Provides login capabilities. This may be for a specific user or for a user with the desired role.

#### logInAsUserRole

Logs in as a user with the desired role (calls `logInAsUser`). If no user is found in the configuration cache, it will prompt for the user's credentials.

#### logInAsUser

Logs in as the user with the given credentials. If logging in fails, it will write out an error and exit with the code `EXIT_CODE_FAILED_PRECONDITION`.