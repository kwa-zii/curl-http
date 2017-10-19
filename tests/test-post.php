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
$params = json_encode(["user"=>"qinqw","mail"=>"qinqiwei@hotmail.com"]);
$req->post("http://www.baidu.com", $params);
$res = $req->exec();
var_dump($res);
$headers = $req->getHttpHeaders();
var_dump($headers);