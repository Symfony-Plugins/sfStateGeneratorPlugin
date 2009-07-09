<?php 
class sfVariatorFactory
{
  public static function getVariator($type)
  {
    $class = 'sf'.ucfirst($type).'Variator';
    return new $class;
  }
}

