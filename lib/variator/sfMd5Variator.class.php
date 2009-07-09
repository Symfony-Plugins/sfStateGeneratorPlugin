<?php
class sfMd5Variator
{
  public function getVariate($variate, $postVariate, $variationIndex)
  {
    return md5(rand(100000, 999999) . time() . uniqid()) . $postVariate[$variate];
  }
}
