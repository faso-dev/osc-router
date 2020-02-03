# osc-router
use case
  ```php
  require_once 'vendor/autoload.php';
  $app = new App\App();
  $app->get('/home', function () use ($app){
    require_once 'home.php';
  });
  $app->post('/home', function () use ($app){
    echo '<h1>Welcome to the submit page</h1>';
  });
  $app->run()
  ```
