<?php


use Pecee\SimpleRouter\SimpleRouter;


$someVar = [];

dnl($someVar);


//$host = DB_HOST;
//$username = DB_USERNAME;
//$password = DB_PASSWORD;
//$database = DB_DATABASE;
//
//// Create connection
//$conn = new mysqli($host, $username, $password, $database);
//
//// Check connection
//if ($conn->connect_error) {
//    die("Connection failed: " . $conn->connect_error);
//}
//echo "Connected successfully";
//$conn->close();



SimpleRouter::get('/', function() {
    phpinfo();
    return 'Hello world';
});


SimpleRouter::get('/user/{id}', function ($userId) {
    return 'User with id: ' . $userId;
});


SimpleRouter::get('/product-view/{id}', 'ProductsController@show', ['as' => 'product']);
SimpleRouter::controller('/images', 'ImagesController');

# output: /product-view/22/?category=shoes
//url('ProductsController@show', ['id' => 22], ['category' => 'shoes']);

# output: /images/image/?id=22
//url('ImagesController@getImage', null, ['id' => 22]);


SimpleRouter::get('/', [MyClass::class, 'myMethod']);
SimpleRouter::get('/project', [MyClass::class, 'myMethod']);
//Available methods
//Here you can see a list over all available routes:

$url = '';
$callback = [];
$settings = [];
SimpleRouter::get($url, $callback, $settings);
SimpleRouter::post($url, $callback, $settings);
SimpleRouter::put($url, $callback, $settings);
SimpleRouter::patch($url, $callback, $settings);
SimpleRouter::delete($url, $callback, $settings);
SimpleRouter::options($url, $callback, $settings);
