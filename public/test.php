Ay
<br><?php

#error_reporting (E_ALL);
throw new \Exception( 'ABLE to set display_errors.' ); 
echo "display errors: ". (ini_get('display_errors') . '<br>');
echo "display startup errors: ". ini_get('display_startup_errors') . '<br>';
echo (8/0). ' (n.0) <br>';

if ( ini_set( 'display_errors', '1' ) === false ) {
    throw new \Exception( 'Unable to set display_errors.' ); 
}else{
    throw new \Exception( 'ABLE to set display_errors.' ); 
}
echo (8/0). ' (n.1) <br>';

ini_set('display_startup_errors', 1);
echo (8/0). ' (n.2) <br>';
error_reporting(E_ALL);

echo (8/0). ' (n.3) <br>';
echo "GOES ON";
require __DIR__.'/../bootstrap/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);



$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
/*
$response->send();

$kernel->terminate($request, $response);
*/