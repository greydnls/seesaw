<?php
/**
 * Created by PhpStorm.
 * User: kayladaniels
 * Date: 4/7/15
 * Time: 11:17 AM
 */
require __DIR__."/vendor/autoload.php";


$ba = new \Kayladnls\Seesaw\Seesaw();
$ba->setBaseUrl('http://joelle.com');

$ba->addNamedRoute('JimBob', 'GET', 'url/jim/bob', function(){});



echo $ba->route('JimBob')->secure();

echo $ba->route('JimBob')->relative();