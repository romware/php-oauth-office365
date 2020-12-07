<?php

require_once('vendor/autoload.php');
session_start();

use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

define("TENANT_ID",     "INSERT FROM AZURE PORTAL");
define("CLIENT_ID",     "INSERT FROM AZURE PORTAL");
define("CLIENT_SECRET", "INSERT FROM AZURE PORTAL");

define("URL_REDIRECT",  "https://mydomain.com/path/to/this/file");
define("URL_AUTHORIZE", "https://login.microsoftonline.com/" . TENANT_ID . "/oauth2/v2.0/authorize");
define("URL_TOKEN",     "https://login.microsoftonline.com/" . TENANT_ID . "/oauth2/v2.0/token");

/*******************************************************************************
 * 
 * STEP 3: Check if user has already signed in and retrieve account email
 * information.
 * 
 * ****************************************************************************/
if(isset($_SESSION["email"]))
{
    echo "Logged in as " . $_SESSION["email"];
}

/*******************************************************************************
 * 
 * STEP 2: Check if user has just signed in and returning from the authorisation
 * page with an authorisation code to retrieve account information.
 * 
 * ****************************************************************************/
elseif(isset($_GET['code']))
{
    $params = 
        "client_id="      . CLIENT_ID .
        "&redirect_uri="  . urlencode(URL_REDIRECT) . 
        "&client_secret=" . urlencode(CLIENT_SECRET) . 
        "&code="          . $_GET['code'] . 
        "&grant_type="    . "authorization_code";
    
    try
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, URL_TOKEN);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);    
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
        ));
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $output = curl_exec($ch);
    }
    catch (Exception $e)
    {
        var_dump($e);
        exit;
    }
    
    $response = json_decode($output, true);
    
    $graph = new Graph();
    $graph->setAccessToken($response['access_token']);
    $user = $graph->createRequest("GET", "/me")
                  ->setReturnType(Model\User::class)
                  ->execute();
    
    if(isset($user->getMail()))
    {
        $_SESSION["email"] = $user->getMail();
    }
    elseif(isset($user->getUserPrincipalName()))
    {
        $_SESSION["email"] = $user->getUserPrincipalName();
    }
    
    header('Location: ' . URL_REDIRECT);
    exit;
}

/*******************************************************************************
 * 
 * STEP 1: Check if user has not signed in yet and redirect them to the
 * authorisation page.
 * 
 * ****************************************************************************/
else
{
    $params = 
        "client_id="      . CLIENT_ID .
        "&redirect_uri="  . urlencode(URL_REDIRECT) . 
        "&response_type=" . "code" . 
        "&scope="         . urlencode(
            "openid profile offline_access user.read files.readwrite"
        );
    
    header('Location: ' . URL_AUTHORIZE . '?' . $params);
    exit;
}
