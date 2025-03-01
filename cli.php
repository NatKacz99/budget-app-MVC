<?php

$driver = 'mysql';
$config = http_build_query(data: [
  'host' => 'localhost',
  'port' => 3306,
  'dbname' => 'budget_app'
], arg_separator: ';');

$dsn = "{$driver}:{$config}";
$username = 'root';
$password = '';

$db = new PDO($dsn, $username, $password);

echo "Connected to the database";
