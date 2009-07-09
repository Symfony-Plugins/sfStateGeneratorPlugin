<?php 

// need to pass in: type, fixtures, count($i)

$app = $argv[1];//needed to override the guessing in the test bootstrap
$type = $argv[2];
$fixtures = $argv[3];
$index = $argv[4];

require_once(dirname(__FILE__).'/../../../test/bootstrap/functional.php');

$parser = new sfYamlParser();
$forms = $parser->parse(file_get_contents($fixtures));
$params = $forms[$type];

$authentication = (array_key_exists('authentication', $params)) ? $params['authentication'] : null;

$requestSequence = new sfStateGeneratorRequestSequence($params['request_sequence'], 
                                                       $index, 
                                                       $authentication);
                                                       
$requestSequence->executeRequests();
