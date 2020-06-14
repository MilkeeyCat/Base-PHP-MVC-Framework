<?php

	/**
	*	Router 
	*	* _ *
	*/

	class Router
	{

		protected static $routes = [];
		protected static $route = [];

		public static function add($regexp, $route = [])
		{
			self::$routes[$regexp] = $route;
		}

		public static function getRoutes()
		{
			return self::$routes;
		}

		public static function getRoute()
		{
			return self::$route;
		}

		private static function matchRoute($url)
		{
			foreach (self::$routes as $pattern => $route) {
				if(preg_match("#$pattern#i", $url, $matches)) {
					foreach($matches as $key => $val) {
						if(is_string($key)) {
							$route[$key] = $val;
						}
					}
					if(!isset($route['action'])) {
						$route['action'] = 'index';
					}
					self::$route = $route;
					return true;
				}
			}
			return false;
		}

		public static function dispatch($url)
		{	
			if(self::matchRoute($url)) {
				$controller = self::uppserCamelCase(self::$route['controller']);
				if(class_exists($controller)) {
					$controller_object = new $controller;
					$action = self::lowerCamelCase(self::$route['action']) . 'Action';
					if(method_exists($controller_object, $action)) {
						$controller_object->$action();
					} else {
						echo "Method $action Not Found";
					}
				} else {
					echo "Controller '$controller' does mot exist";
				}
			} else {
				http_response_code(404);
			}
		}

		protected static function uppserCamelCase($name) {
			$name = str_replace('-', ' ', $name);
			$name = ucwords($name);
			$name = str_replace(' ', '', $name);
			return $name;
		}

		protected static function lowerCamelCase($name) {
			return lcfirst(self::uppserCamelCase($name));
		}

	}