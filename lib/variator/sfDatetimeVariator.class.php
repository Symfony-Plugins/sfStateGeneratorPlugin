<?php 
class sfDatetimeVariator
{
  public function getVariate($variate, $postVariate, $variationIndex)
  {
    $timestamp = strtotime($postVariate[$variate]);
    if (0 == $variationIndex)
    {
      $factor = 0;
    }
    else
    {
      $factor = ($variationIndex % 2 == 0) ? 1 : -1;
    }
    $modified_timestamp = $timestamp + ($factor * $variationIndex * 604800);
    
    $formated_date = date('n/j/Y G:i', $modified_timestamp);
    
    return $formated_date;
  }
}
