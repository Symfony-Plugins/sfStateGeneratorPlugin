<?php

/**
 * sfStateGenerator class.
 *
 * @package    symfony
 * @subpackage sfStateGeneratorPlugin
 * @author     Oz Basarir <oz@ezkode.com>
 * @version    SVN: $Id$
 */
class sfStateGenerator 
{
  
  public function __construct($fixtures, $init, $app)
  {
    if (!file_exists($fixtures))
    {
      throw new sfException('Could not find the fixtures file.');  
    }
    
    require_once(sfConfig::get('sf_symfony_lib_dir').'/vendor/lime/lime.php');
    
    $this->cleanupDB($init);
    
    $parser = new sfYamlParser();
    $forms = $parser->parse(file_get_contents($fixtures));
    foreach ($forms as $type => $params) 
    {
      $this->generateVariations($type, $params, $fixtures, $app);
    }
    
  }  
  
  private function generateVariations($type, $params, $fixtures, $app)
  {
    $harness = new lime_harness(new lime_output_color());
    $php_cli = $harness->php_cli;
    
    $sequenceRunner = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sequenceRunner.php';
    for($i = 1; $i <= $params['count']; $i++)
    {
      passthru(sprintf('cd & "%s" "%s" "%s" "%s" "%s" "%s" 2>&1', 
                       $php_cli, 
                       $sequenceRunner, 
                       $app, 
                       $type, 
                       $fixtures, 
                       $i), 
               $return);
      if ($return > 0) 
      {
        throw new sfException('sequenceRunner failed with: '.$return);
      }
    }
  }
  
  private function cleanupDB($init)
  {
    $con = Propel::getConnection();
    
    $parser = new sfYamlParser();
    $content = $parser->parse(file_get_contents($init));

    foreach($content['tables'] as $table)
    {
      $stmt = $con->prepare("TRUNCATE $table");
      $stmt->execute();  
    }    
  }
  
}


