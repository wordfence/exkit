#!/usr/bin/env php
<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use Wordfence\ExKit\WPAuthentication;
use Wordfence\ExKit\Cli;

//Prompt for something
$value = Cli::prompt("Enter a value", 'some default');
Cli::write('Value: ' . $value);

//Prompt for the baseURL early if needed
Endpoint::baseURL();

//Log in as a subscriber
$session = new \Requests_Session();
WPAuthentication::logInAsUserRole($session, WPAuthentication::USER_ROLE_SUBSCRIBER);
Cli::write('[+] Logged in', 'green', null);