<?php
class sfEmailVariator
{
  public function getVariate($variate, $postVariate, $variationIndex)
  {
    $email_parts = explode('@', $postVariate[$variate]);
    return $email_parts[0].$variationIndex.'@'.$email_parts[1];
  }
}
