<?php
defined('DS')             ? null : define('DS', DIRECTORY_SEPARATOR);
defined('CACHE_LIFETIME') ? null : define('CACHE_LIFETIME', 60 * 60 * 24);

require_once('Api.php');
require_once('credentials.php');
$api = new Api($AgentLogin, $AgentPassword, $ServiceId);

require_once('Consume.php');
$consume = new Consume();

require_once('functions.php');