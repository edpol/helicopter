<?php

require_once('Api.php');
require_once('credentials.php');
$api = new Api($AgentLogin, $AgentPassword, $ServiceId);

require_once('Consume.php');
$consume = new Consume();

