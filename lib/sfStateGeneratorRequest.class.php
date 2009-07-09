<?php
class sfStateGeneratorRequest 
{
  private $request,
          $variationIndex,
          $browser,
          $additionalParams;
  
  public function __construct($request, $variationIndex, $browser, $additionalParams) 
  {
    $this->request = $request;
    $this->variationIndex = $variationIndex;
    $this->browser = $browser;
    $this->additionalParams = $additionalParams;
  }
  
  public function executeRequest() 
  {
    $this->setPostVariation();

    $this->browser->
      post($this->request['url'], $this->postVariate)->
      isRequestParameter('module', $this->request['response']['module'])->
      isRequestParameter('action', $this->request['response']['action']);

    if(array_key_exists('forward', $this->request['response'])) 
    {
      $this->browser->
        isForwardedTo($this->request['response']['module'], $this->request['response']['forward']);
    } 
    
    if(array_key_exists('redirect', $this->request['response'])) 
    {
      $this->browser->
        isRedirected()->
        followRedirect()->
        isRequestParameter('module', $this->request['response']['redirect']['module'])->
        isRequestParameter('action', $this->request['response']['redirect']['action']);      
    }
    
    $this->browser->responseContains($this->request['response']['contains']);
    $this->browser->test()->diag('memory: '.memory_get_usage());
  }
  
  public function setPostVariation() 
  {
    $this->postVariate = $this->request['post_params'];
    if($this->additionalParams) 
    {
      $this->postVariate = array_merge($this->postVariate, $this->additionalParams);
    }
    
    if(!array_key_exists('variate', $this->request))
    {
      return $this->postVariate;
    }
    
    foreach($this->request['variate'] as $variate => $type)
    {
      $variator = sfVariatorFactory::getVariator($type);
      $this->postVariate[$variate] = $variator->getVariate($variate, $this->postVariate, $this->variationIndex);
    }
  }
  
  public function getNextRequestParams() 
  {
    if(!array_key_exists('next_request_params', $this->request))
    {
      return null;
    } 

    $nextRequestParams = array();
    foreach ($this->request['next_request_params'] as $param) 
    {
      $nextRequestParams[$param] = $this->postVariate[$param];
    }
    
    return $nextRequestParams;
  }
}
