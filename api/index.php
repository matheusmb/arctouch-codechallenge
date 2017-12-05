<?php
require_once dirname(__DIR__).'/bootstrap.php';

$app = new \CodeChallenge\Api();

/* Prints te API data in JSON format */
echo json_encode( $app->processRequest($_SERVER['QUERY_STRING']) );

