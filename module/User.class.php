<?php
/*
 * User - provides various user functionalities
 *
 */
namespace module;
class User extends Module implements iModule
{
    public function __construct()
    {
        $routes   = array();
        
        // Sign in
        $routes[] = array('pattern'  => '#^/user/sign-in$#',
                          'callback' => array($this, 'DisplaySignIn'));
                          
        // User auth
        $routes[] = array('pattern'  => '#^/user/authenticate/openid$#',
                          'callback' => array($this, 'AuthenticateUser'));
                          
        // Set credentials            
        $routes[] = array('pattern' => '#^/user/setCredentials(.*)$#',
                          'callback' => array($this, 'SetCredentials'));
                          
        // Auth cancel           
        $routes[] = array('pattern' => '#^/user/authenticate/cancel$#',
                          'callback' => array($this, 'AuthCancel'));
                          
        // Sign out         
        $routes[] = array('pattern' => '#^/user/sign-out$#',
                          'callback' => array($this, 'SignOut'));
                          
        $this->SetRoutes($routes);
    }
    
    public function SignOut()
    {
        $this->GetUserSession()->SignOut();
        header('Location: /user/sign-in');
        die;
    }
    
    public function AuthCancel()
    {
        return $this->GetView()->Display(array('tpl' => '../view/User/UserAuthCancel.template.php'));
    }
    
    public function SetCredentials()
    {
        // Check if logged in
        $objOpenID    = new LightOpenID(BS_DOMAIN);
        $userSignedIn = $objOpenID->validate();
        $attr         = $objOpenID->getAttributes();
        $userLogin    = $userSignedIn ? $attr['contact/email'] : '';
        $identity     = isset($objOpenID->data['openid_identity']) ? $objOpenID->data['openid_identity'] : false; 
        $claimedID    = isset($objOpenID->data['openid_claimed_id']) ? $objOpenID->data['openid_claimed_id'] : false; 
        
        // Sign in successful
        if( $userSignedIn && $claimedID && $userLogin )
        {
            $objUserSession = new UserSession();
            $objUserSession->SignIn(array('userID'    => $claimedID,
                                          'userLogin' => $userLogin));
                                          
            header('Location: /issues');
            die;
        }
        // Something went wrong
        else
        {
            header('Location: /user/sign-in');
            die;
        }
    }
    
    /*
     * Displays sign in screen
     *
     */
    public function DisplaySignIn()
    {
        return $this->GetView()->Display(array('tpl' => '../view/User/UserSignIn.template.php'));
    }
    
    /*
     * Authenticates using OpenID
     *
     */
    public function AuthenticateUser()
    {
        try
        {
            $objOpenID = new LightOpenID(BS_DOMAIN);
            
            switch( $objOpenID->mode )
            {
                case 'cancel':
                    header('Location: /user/authenticate/cancel');
                    die;
                break;
                
                default:
                    $objOpenID->required  = array('namePerson/friendly', 'contact/email');
                    $objOpenID->identity  = sprintf('https://www.google.com/accounts/o8/id?site-xrds?hd=%s', BS_DOMAIN);
                    $objOpenID->returnUrl = sprintf('http://%s/user/setCredentials', BS_DOMAIN);
                    header(sprintf('Location: %s', $objOpenID->authUrl()));
                    die;
            }
        }
        catch(Exception $e)
        {
            die($e->getMessage());
        }
    }
    
    public function GetRoutes()
    {
        return $this->routes;
    }
}