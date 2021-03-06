<?php
// usage:
// composer install #to install facebook api
// /usr/local/bin/php --file createTestUser.php

include_once("utils.php");

$APPLICATION_ID = "10152756866328132"; // Test app
$APPLICATION_SECRET = "3d7248d5b83857bf5a02972dfebdbd66"; // Test app secret

// FIXME: ACCESS_TOKEN changes often and should be parameterized
// regenerate using the graph api explorer:
// https://developers.facebook.com/tools/explorer?method=GET&path=me%3Ffields%3Did%2Cname&version=v2.1
$ACCESS_TOKEN = "CAACEdEose0cBAAhZBfi2okbv8gq8pOzqAcr3TJm6kKl5ADyFL0VvaxTj9Gtifnf9l7GCOaZCvTJPhHsCHypb2ekVa835FduzZCTjgRahnXWmAWXx0VqzZC2P98ji1yiJM3tG5ZCzTeL6HSu8mBWuOaCgPvRJCd413hqtOCY4ZAfzam5CdeUg6NP5MKuZB2ZBO7S9HFI00o1aIDk6sdoPalOf";

setupAutoLoad();
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

FacebookSession::setDefaultApplication($APPLICATION_ID,$APPLICATION_SECRET);

// Use one of the helper classes to get a FacebookSession object.
//   FacebookRedirectLoginHelper
//   FacebookCanvasLoginHelper
//   FacebookJavaScriptLoginHelper
// or create a FacebookSession with a valid access token:
$session = new FacebookSession($ACCESS_TOKEN);

// Get the GraphUser object for the current user:

try {
  $me = (new FacebookRequest(
			     $session, 'GET', '/me'
			     ))->execute()->getGraphObject(GraphUser::className());
  echo $me->getName();

  $token_url =    "https://graph.facebook.com/oauth/access_token?" .
    "client_id=" . $APPLICATION_ID .
    "&client_secret=" . $APPLICATION_SECRET .
    "&grant_type=client_credentials";
  $tmp = file_get_contents($token_url);
  //  echo $tmp;
  $ary=preg_split('/=/',$tmp);
  print_r($ary);
  $APP_ACCESS_TOKEN=$ary[1];

  $params = array(
		  'access_token' => $APP_ACCESS_TOKEN,
		  'installed' => 'true',
		  'permissions' => 'read_page_mailboxes,manage_pages,read_mailbox,read_insights,read_friendlists,read_stream,publish_actions,user_photos,user_status'
		  );

  /* make the API call */
  $request = new FacebookRequest(
				 $session,
				 'POST',
				 "/$APPLICATION_ID/accounts/test-users",
				 $params
				 );

  $response = $request->execute();
  $graphObject = $response->getGraphObject();
  $user = $graphObject->asArray();
  print_r($user);

  /* handle the result */

} catch (FacebookRequestException $e)
{
  // The Graph API returned an error

  echo "Exception occured, code: " . $e->getCode();
  echo " with message: " . $e->getMessage();

}
catch (\Exception $e)
{
  // Some other error occurred
  // test
}
