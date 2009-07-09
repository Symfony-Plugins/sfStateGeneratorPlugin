<?php
class sfStateGeneratorRequestSequence 
{
  private $requestSequence,
          $variationIndex,
          $browser,
          $authentication;
  
  public function __construct($requestSequence, $variationIndex, $authentication) 
  {
    $this->requestSequence = $requestSequence;
    $this->variationIndex = $variationIndex;
    $this->authentication = $authentication;

    $this->browser = new sfTestBrowser();
    $this->browser->initialize();    
  }
  
  public function executeRequests() 
  {
    $this->authenticateUser();
    $nextRequestParams = null;
    
    foreach ($this->requestSequence as $request) 
    {
      $className =  array_key_exists('requestProcessor', $request) ? $request['requestProcessor'] : 
                                                                     'sfStateGeneratorRequest'; 
      $requestObj = new $className($request, 
                                   $this->variationIndex, 
                                   $this->browser, 
                                   $nextRequestParams);
      
      $requestObj->executeRequest();
      $nextRequestParams = $requestObj->getNextRequestParams();
    }
  }

  private function authenticateUser()
  {
    if(empty($this->authentication))
    {
      return;
    }

    if (is_array($this->authentication))
    {
      $this->browser->
        get('/')->
        post('/login', array('username' => $this->authentication['username'], 
                             'password' => $this->authentication['password']))->
        isRedirected()->
        followRedirect();
        
      return;
    }
    // if a user is not specified, select a random user to create entity
    $query = 'SELECT masterid FROM users ORDER BY RAND() LIMIT 1';
    $con = Propel::getConnection();
    $stmt = $con->prepare($query);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $this->browser->
      get('/')->
      post('/login', array('username' => $user['masterid'], 
                           'password' => 'password'))->
      isRedirected()->
      followRedirect();

  }
  
}

