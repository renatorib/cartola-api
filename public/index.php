<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

function sendRequest($path, $options = array()){
  $options = array_merge(array(
    'base' => 'https://api.cartolafc.globo.com/',
    'body' => false,
    'token' => false
  ), $options);

  $c = curl_init();
  curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($c, CURLOPT_URL, $options['base'] . $path);

  if($options['body']){
    curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($c, CURLOPT_POST, true);
    curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($options['body']));
  } else {
    curl_setopt($c, CURLOPT_FRESH_CONNECT, true);
  }

  if($options['token']){
    curl_setopt($c, CURLOPT_HTTPHEADER, array('X-GLB-Token: ' . $options['token']));
    curl_setopt($c, CURLOPT_VERBOSE, false);
  }

  $result = curl_exec($c);
  curl_close($c);

  return $result;
}

function login($login, $password){
  $body = array('payload' => array(
    'email' => $login,
    'password' => $password,
    'serviceId' => 4728
  ));

  var_dump($body);

  return sendRequest('authentication', array(
    'base' => 'https://login.globo.com/api/',
    'body' => $body
  ));
}

function api($arguments){
  unset($arguments['p']);
  $path = $_GET['p'] . '?' . http_build_query($arguments);

  $results = sendRequest($path, array(
    'token' => isset($_GET['token']) ? $_GET['token'] : false,
    'body' => !empty($_POST) ? $_POST : false
  ));

  if(trim($results) == '404 page not found') {
    header('HTTP/1.0 404 Not Found');
  }

  echo $results;
}

if(isset($_GET['p'])){

  if($_GET['p'] == 'login' && isset($_GET['login']) && isset($_GET['password'])){
    echo login($_GET['login'], $_GET['password']);
  } else {
    echo api($_GET);
  }

}
