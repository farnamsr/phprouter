
# PHP Router

A simple and easy to use router for php apps

## How to use


in the ``` index.php ``` file, require the router class:
```
require_once("Router.php");
```

### define the routes
use a callback function as a route action
```
Router::get("/home", function() {
    echo "Welcome Home!";
});
```

use a file as the action
```
Router::get("/gallery", "example/gallery.php");
```

separate the file and the target function with ``` @ ```

```
Router::get("/help", "example/helpers.php@help");
```

with parameters

```
Router::get("/users/{id}/posts/{post}", "example/users.php@users");
```

## Run the router

```
Router::dispatch();
```

## Get all defined routes

```
Route::routes();
```

