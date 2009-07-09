<?php

/**
 * generateStateTask class.
 *
 * @package    symfony
 * @subpackage sfStateGeneratorPlugin
 * @author     Oz Basarir <oz@ezkode.com>
 * @version    SVN: $Id$
 */
class generateStateTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'test'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
    ));

    $this->namespace        = '';
    $this->name             = 'generateState';
    $this->briefDescription = 'Prepopulates the database for testing.';
    $this->detailedDescription = <<<EOF
The [generateState|INFO] task repopulates the database for testing.
Call it with:

  [php symfony generateState|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $testFolder = sfConfig::get('sf_test_dir');

    $app = $options['application'];//needed to override the guessing in the test bootstrap
    include($testFolder.'/bootstrap/functional.php');

    ini_set("memory_limit","128M");//increase this if you have too many sequences in your yml

    $stateGenerator = new sfStateGenerator($testFolder.'/fixtures/state_generator.yml',
                                           $testFolder.'/fixtures/state_generator_initialize.yml',
                                           $app);
  }
}
