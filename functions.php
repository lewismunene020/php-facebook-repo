<?php

	include 'definitions.php';

	function makeApiCall($endpoint, $type, $params)
	{	
		$ch = curl_init(); 
		$apiEndPoint = $endpoint. '?' . http_build_query($params);
	
		curl_setopt($ch, CURLOPT_URL, $apiEndPoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		curl_close($ch);

		return array(
			'type' => $type,
			'endpoint' => $endpoint,
			'params' => $params,
			'api_endpoint' => $apiEndPoint,
			'data' => json_decode($response, true)  );
	}

	function getFacebookLoginUrl( $permissions, $state){
		$endpoint = 'https://www.facebook.com/' . fbGraphVersion . '/dialog/oauth';
		$params = array(
			'client_id' => fbAppId,
			'redirect_uri' => fbRedirectUri,
			'permissions' => $permissions,
			'state' => $state,
			'auth_type' => 'rerequest'
		);
		return $endpoint . '?' .http_build_query($params);
	}

	function getAccessTokenWithCode($code){
		$endpoint = fbGraphDomain. fbGraphVersion. '/oauth/access_token';
		$params = array(
			'client_id' => fbAppId,
			'cleint_secret' => fbAppSecret,
			'redirect_url' => fbRedirectUri,
			'code' => $code,
		);
		return makeApiCall($endpoint, 'GET' , $params);
	}