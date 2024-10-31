<?php


require_once '../lib/mobi_check.php';
$d = MobiCheck::getInstance();

var_dump( $d->isMobile() );