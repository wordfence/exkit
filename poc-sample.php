#!/usr/bin/env php
<?php

require __DIR__ . DS . 'vendor' . DS . 'autoload.php';

use Wordfence\WPKit\WPAuthentication;
use Wordfence\WPKit\Cli;

//Prompt for something
$value = Cli::prompt("Enter a value", 'some default');
Cli::write('Value: ' . $value);

//Prompt for the baseURL early if needed
Endpoint::baseURL();

//Log in as a subscriber
$session = new \Requests_Session();
WPAuthentication::logInAsUserRole($session, WPAuthentication::USER_ROLE_SUBSCRIBER);
Cli::write('[+] Logged in', 'green', null);