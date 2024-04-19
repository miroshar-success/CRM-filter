<?php

/**
* Aliases for League Provider Classes
* Make sure you have added these to your composer.json and run `composer install`
* Plenty to choose from here:
* @see http://oauth2-client.thephpleague.com/providers/thirdparty/
*/
//@see https://github.com/thephpleague/oauth2-google
use League\OAuth2\Client\Provider\Google;

class Smtp_oauth_google extends AdminController
{
    public function token()
    {
        $providerName = 'Google';
        $clientId     = get_option('google_mail_client_id');
        $clientSecret = $this->encryption->decrypt(get_option('google_mail_client_secret'));
        
        if (!$clientId && !$clientSecret) {
            die('Add client ID and Client Secret in settings.');
        }
        
        $redirectUri = admin_url('smtp_oauth_google/token');
        
        $params = [
            'clientId'     => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri'  => $redirectUri,
            'accessType'   => 'offline',
        ];
        
        $options  = [];
        $provider = null;
        
        switch ($providerName) {
            
            case 'Google':
                $provider = new Google($params);
                $options = ['scope' => [ 'https://mail.google.com/' ]];
                    
                break;
        }

        if (null === $provider) {
            exit('Provider missing');
        }
                
        if (!isset($_GET['code'])) {
            //If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl($options);
            $this->session->set_userdata(['oauth2state' => $provider->getState()]);
            header('Location: ' . $authUrl);
            exit;
        //Check given state against previously stored one to mitigate CSRF attack
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $this->session->userdata('oauth2state'))) {
            $this->session->unset_userdata('oauth2state');
            exit('Invalid state');
        }

        try {
            //Try to get an access token (using the authorization code grant)
            $token = $provider->getAccessToken(
                'authorization_code',
                [
                    'code' => $_GET['code'],
                ]
            );

            //Use this to interact with an API on the users behalf
            //Use this to get a new access token if the old one expires
            if($refreshToken = $token->getRefreshToken()) {
                update_option('google_mail_refresh_token', $refreshToken);
            }

            update_option('smtp_email', $provider->getResourceOwner($token)->getEmail());
             
        } catch(Exception $e) {
            set_alert('warning', $e->getMessage());
        }

        redirect(admin_url('settings?group=email'));
    }
}
