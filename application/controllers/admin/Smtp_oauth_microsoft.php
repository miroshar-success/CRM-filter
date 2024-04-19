<?php
/**
 * Aliases for League Provider Classes
 * Make sure you have added these to your composer.json and run `composer install`
 * Plenty to choose from here:
 * @see http://oauth2-client.thephpleague.com/providers/thirdparty/
 */
use Greew\OAuth2\Client\Provider\Azure;
//@see https://github.com/greew/oauth2-azure-provider
use Stevenmaguire\OAuth2\Client\Provider\Microsoft;

class Smtp_oauth_microsoft extends AdminController
{
    public function token()
    {
        $providerName = 'Microsoft';
        $clientId     = get_option('microsoft_mail_client_id');
        $clientSecret = $this->encryption->decrypt(get_option('microsoft_mail_client_secret'));
        $tenantId     = get_option('microsoft_mail_azure_tenant_id');

        if (!$clientId && !$clientSecret) {
            die('Add client ID and Client Secret in settings.');
        }

        if ($tenantId) {
            $providerName = 'Azure';
        }

        $redirectUri = admin_url('smtp_oauth_microsoft/token');

        $params = [
            'clientId'     => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri'  => $redirectUri,
            'accessType'   => 'offline',
        ];

        $options  = [];
        $provider = null;
     
        switch ($providerName) {

            case 'Microsoft':

                $provider = new Microsoft($params);

                $options  = [
                    'scope' => [
                        'wl.imap',
                        'wl.offline_access',
                    ],
                ];

                break;
            case 'Azure':
                $params['tenantId'] = $tenantId;

                $provider = new Azure($params);
                $options  = [
                    'scope' => [
                        'https://outlook.office.com/SMTP.Send',
                        'offline_access',
                    ],
                ];

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
            update_option('microsoft_mail_refresh_token', $token->getRefreshToken());
        } catch(Exception $e) {
            set_alert('danger', $e->getMessage());
        }
    
        redirect(admin_url('settings?group=email'));
    }
}
