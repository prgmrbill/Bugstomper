<?php
/*
 * Router - parses request data to bridge resources and routes
 *
 */
namespace application;
use module\NotFoundException as NotFoundException;

class Router
{
	private $debug			   = false;
	private $errorHandlers     = array();
	private $routes 		   = array();
	private $routeInfo		   = array();
	
	public function __construct()
	{

	}
	
	public function AddRoute($route)
	{
		$this->routes[] = $route;
	}
    
    public function SetRoutes($routes)
    {
        $this->routes = $routes;
    }
	
	// @desc - Sets callbacks for particular errors
	public function SetErrorHandler($statusCode, $callback)
	{
		$this->errorHandlers[$statusCode] = $callback;
	}
	
	// @desc - Retrieves callback for a particular error
	public function GetErrorHandler($statusCode)
	{
		return isset($this->errorHandlers[$statusCode]) && $this->errorHandlers[$statusCode]
			   ?
			   $this->errorHandlers[$statusCode]
			   :
			   false;
	}
	
	public function Route()
	{
		$route 	   		 = $this->GetRouteComponents();
		$this->routeInfo = $this->FindRoute($route);
		return $this->LoadRoute($this->routeInfo);
	}

	public function LoadRoute($route)
	{
		$callback     = $route && isset($route['callback']) ? $route['callback'] : false;
		$beforeFilter = $route && isset($route['before']) ? $route['before'] : false;
		
		if( is_array($beforeFilter) && count($beforeFilter) == 2 )
        {
            // TODO: maybe I should do something when/if this returns
            // false
            call_user_func($beforeFilter);
        }
        
		// Callback found!
        if( is_array($callback) && count($callback) == 2 )
        {
            $callbackResult = call_user_func($callback);
            
            if( $callbackResult )
            {
                return true;
            }
        }
        
        // Didn't find a valid callback, so resource is non-existent
        throw new NotFoundException('Page not found');
	}
	
	/*
	 * Finds corresponding callback in map
	 * using route from URI
	 *
	 */
	public function FindRoute($route)
	{	
		if( $this->routes )
		{
			$acceptFormats = explode(',', $_SERVER['HTTP_ACCEPT']);
			
			foreach( $this->routes as $k => $m )
			{
				$pattern = isset($m['pattern']) ? $m['pattern'] : '#^/$#';
				$method  = isset($m['method'])  ? $m['method']  : 'GET';
				$accept  = isset($m['accept'])  ? $m['accept']  : 'text/html';
				
				// Check method
				if( $method == $route['method'] )
				{
					// Check pattern
					if( preg_match_all($pattern, $route['path'], $matches) )
					{
						if( in_array($accept, $acceptFormats) )
						{
							$m['matches'] = $matches;
                            
							return $m;
						}
					}
				}
			}
		}
       
		return false;
	}
	
	public function GetRouteComponents()
	{
		$method		 = strtoupper($_SERVER['REQUEST_METHOD']);
		$scheme      = isset($parts['scheme'])  ? strtolower($parts['scheme']) : 'http';

		return array('method'  => $method,
					 'path'    => $_SERVER['REQUEST_URI'],
					 'scheme'  => $scheme);
	}

	public function SetDebug($debug)
	{
		$this->debug = $debug;
	}
	
	public function GetIDFromURI($pattern)
	{
		// Get bug ID out of URI
		preg_match_all($pattern, 
					   $_SERVER['REQUEST_URI'], 
					   $matches);
		
		return isset($matches[1][0]) ? $matches[1][0] : 0;
	}
	
	public function GetRouteInfo()
	{
		return $this->routeInfo;
	}
}
