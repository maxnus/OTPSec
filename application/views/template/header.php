<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<!doctype html>
<html lang='en'>
<head>
<title>OTPSec Authentication</title>
<meta charset="utf-8"/>
<?=link_tag('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css')?>
<?php if(isset($page) && $page==='login') :?>
<?=link_tag('assets/css/sweetalert2/sweetalert2.css')?>
<?php endif;?>
<?=link_tag('assets/css/main/style.css')?>
</head>
<body>
<div class="navbar navbar-fixed-top navbar-default">
    <div class="navbar-inner">
        <div class="container">
        <nav class='navbar-brand'>
        OTPSec Authentication   
        </nav>
        </div>
    </div>
</div> 