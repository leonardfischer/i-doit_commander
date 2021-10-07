# "Commander" Add-on

The "Commander" can help you navigate i-doit and perform simple tasks by user input.
The tasks are kept simple and try to rely as much on existing core logic as possle.

For example: when typing `create server` - instead of implementing custom code
to create a new object, the add-on will simply forward the user to the i-doit own
'new object' GUI and preselect the 'Server' object type.

## Usage

### Use the Commander

In order to open the Commander simply tap the 'Control' key three times and a input
with some examples will open.

### Close the Commander

Either execute the current task (by hitting 'Enter') or hit the 'Escape' key.
Alternatively you can click the 'X' on the top right of the Commander.

## Examples

### Open a object

You can do this by typing in the exact object ID like this `open #123` or input the
name of the object. When doing this you can use a asterisk as a wildchar, like this
`open "Gebäude *"`.

### Create a new object

In order to create a new object you'll need to provide the keyword `create` followed
by the object type name, so, for example `create server` or `new client`.

### Use the i-doit search

To use the search you can type `find "information"` - don't forget to put your term
in quotes.

### Open the administration

You can navigate to the administration by typing `admin` or `administration`.

### Logout

With the command `logout` you can terminate your current session. i-doit will move
you back to the login screen.

## Aliases

Most tasks will have multiple ways of accessing them. The search task, for example,
can be accessed by any of these keywords: `find`, `search`, `finde` or `suche`.

## Extend

It is possible to add new tasks from 'outside' of the add-on itself, for example
from your own add-on. In order to do so you'll just need to 'register' your task
to the Commander add-on.

The Commander is located in the DI Container and can be accessed via `lfischer_commander`.
Then you simply need to call the method `registerTask` and add your own Task:

```php
// Get the commander instance from the DI container.
$commander = isys_application::instance()->container->get('lfischer_commander');

if ($commander) {
    $commander->registerTask(new CustomTask($database, $language));
}
```

This code should be located in the `init.php` of your own add-on since this code will
always be run before any other process takes place.
