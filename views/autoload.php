<?php 
if (class_exists('Env')==false) {require_once "src/Env.php";}	
if (class_exists('Sessions')==false) {require_once "src/Sessions.php";}
if (class_exists('CC')==false) {require_once "src/CC.php";}
if (class_exists('Ccurl')==false) {require_once "src/Ccurl.php";}
if (class_exists('Connect')==false) {require_once "src/Connect.php";}
if (class_exists('Cipher')==false) {require_once "src/Cipher.php";}

try {
    //load the jwt master library
    require_once "src/jwtmaster/src/JWT.php";
    require_once "src/jwtmaster/src/TokenDecoded.php";
    require_once "src/jwtmaster/src/TokenEncoded.php";
    require_once "src/jwtmaster/src/Validation.php";
    require_once "src/jwtmaster/src/Base64Url.php";
    require_once "src/jwtmaster/src/Exceptions/InvalidStructureException.php";
} catch(exception $e) {}

//use Nowakowskir\JWT\JWT;
//use Nowakowskir\JWT\TokenDecoded;
//use Nowakowskir\JWT\TokenEncoded;
?>