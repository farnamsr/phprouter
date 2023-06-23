<?php

require_once("Router.php");


//callback
Router::get("/home", function() {
    echo "Welcome Home!";
});


//function
Router::get("/help", "example/helpers.php@help");



// file
Router::get("/gallery", "example/gallery.php");


//parameter
Router::get("/users/{id}/posts/{post}", "example/users.php@users");



// Router::routes();
Router::dispatch();




