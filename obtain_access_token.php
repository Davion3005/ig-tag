<?php

    // load graph-sdk files
    require_once __DIR__ . '/vendor/autoload.php';

    // facebook credentials array
    $creds = array(
        'app_id' => '333350948529732',
        'app_secret' => 'e1e9e8cad75db22bd7b88acedf0b7449',
    );

    // create facebook object
    $facebook = new Facebook($creds);

    // helper
    $helper = $facebook->getRedirectLoginHelper();

    // oauth object
    $oAuth2Client = $facebook->getOAuth2Client();

    if (isset($_GET['code'])) { // get access token
        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) { // graph error
            echo 'Graph returned an error ' . $e->getMessage;
        } catch (Facebook\Exceptions\FacebookSDKException $e) { // validation error
            echo 'Facebook SDK returned an error ' . $e->getMessage;
        }

        if (!$accessToken->isLongLived()) { // exchange short for long
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Error getting long lived access token ' . $e->getMessage();
            }
        }

        echo '<pre>';
        var_dump($accessToken);

        $accessToken = (string)$accessToken;
        echo '<h1>Long Lived Access Token</h1>';
        print_r($accessToken);
    } else { // display login url
        $permissions = [
            'public_profile',
            'instagram_basic',
            'pages_show_list',
            'instagram_manage_insights',
            'instagram_manage_comments',
            'manage_pages',
            'ads_management',
            'business_management',
            'instagram_content_publish',
            'pages_read_engagement'
        ];
        $loginUrl = $helper->getLoginUrl(FACEBOOK_REDIRECT_URI, $permissions);

        echo '<a href="' . $loginUrl . '">
                Login With Facebook
            </a>';
    }
