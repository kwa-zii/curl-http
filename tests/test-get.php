<?php
/**
 * Test http request
 * 
 * @author Kevin <qinqiwei@hotmail.com>
 */
namespace tests;
require_once 'bootstrap.php';

use Qinqw\Curl\Http\Request;

$req = new Request("");
$req->get("http://www.baidu.com");
$res = $req->exec();
var_dump($res);
$info = $req->getInfo();
var_dump($info);