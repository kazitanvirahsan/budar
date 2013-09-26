<?php
error_reporting(E_ALL);

function exception_handler($exception) {
  echo "Uncaught exception: " , $exception->getMessage(), "\n";
}

set_exception_handler('exception_handler');

include 'budar.php';

class MyModel extends Budar\Model
{
	protected $table = 'user';
	protected $primaryKey = 'id';

}

Budar\Config::init(array(
	'dev' => array('mysql', 'localhost', 'test', 'root', 'a0leetpw')
));

$m = new MyModel();
$m->get();