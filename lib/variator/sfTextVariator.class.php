<?php 
class sfTextVariator
{
  public function getVariate($variate, $postVariate, $variationIndex)
  {
    return $postVariate[$variate].$variationIndex;
  }
}
