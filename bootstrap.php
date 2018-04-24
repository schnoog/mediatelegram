<?php


define("DIR_BASE",__DIR__ . "/");
define("DIR_FUNC", DIR_BASE . "FunctionsAndClasses/");


$nl = "<br />";
$cli=false;
if(php_sapi_name() === 'cli' OR defined('STDIN')){
    $nl = "\n";
    $cli=true;
}


require DIR_BASE . '/vendor/autoload.php';
require DIR_BASE . '/include/config.php';

if (isset($Config['DB']['datasource'])){
    DB::$user       = $Config['DB']['datasource']['user'];
    DB::$password   = $Config['DB']['datasource']['pass'];
    DB::$dbName     = $Config['DB']['datasource']['name'];
    DB::$host       = $Config['DB']['datasource']['host']; //defaults to localhost if omitted
    DB::$port       = $Config['DB']['datasource']['port']; // defaults to 3306 if omitted
    DB::$encoding   = 'utf8'; // defaults to latin1 if omitted
}

foreach (glob(DIR_FUNC . "*.php") as $filename)
{
    include_once $filename;
}
