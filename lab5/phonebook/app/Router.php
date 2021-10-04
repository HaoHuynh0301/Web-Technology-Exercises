<?php

namespace App;

/**
 * @method static Router get(string $route, Callable $callback)
 * @method static Router post(string $route, Callable $callback)
 * @method static Router put(string $route, Callable $callback)
 * @method static Router delete(string $route, Callable $callback)
 * @method static Router options(string $route, Callable $callback)
 * @method static Router head(string $route, Callable $callback)
 */
class Router {
  public static $halts = false;
  public static $routes = array();
  public static $methods = array();
  public static $callbacks = array();
  public static $patterns = array(
      ':any' => '[^/]+',
      ':num' => '[0-9]+',
      ':all' => '.*'
  );
  public static $errorCallback;

  /**
   * Defines a route w/ callback and method
   */
  public static function __callstatic($method, $params) {
    $uri = $params[0];
    $callback = $params[1];

    self::$routes[] = $uri;
    self::$methods[] = strtoupper($method);
    self::$callbacks[] = $callback;
  }

  /**
   * Defines callback if route is not found
  */
  public static function error($callback) {
    self::$errorCallback = $callback;
  }

  public static function haltOnMatch($flag = true) {
    self::$halts = $flag;
  }

  /**
   * Runs the callback for the given request
   */
  public static function dispatch(){
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];

    $searches = array_keys(static::$patterns);
    $replaces = array_values(static::$patterns);

    $foundRoute = false;

    // self::$routes = preg_replace('/\/+/', '/', self::$routes);

    // Check if route is defined without regex
    if (in_array($uri, self::$routes)) {
      $routePos = array_keys(self::$routes, $uri);
      foreach ($routePos as $route) {
        // Using an ANY option to match both GET and POST requests
        if (self::$methods[$route] == $method || self::$methods[$route] == 'ANY') {
          $foundRoute = true;

          // If route is not an object
          if (!is_object(self::$callbacks[$route])) {

            // Grab all parts based on a / separator
            $parts = explode('/',self::$callbacks[$route]);

            // Collect the last index of the array
            $last = end($parts);

            // Grab the controller name and method call
            $segments = explode('@',$last);

            // Instanitate controller
            $controller = new $segments[0]();

            // Call method
            $controller->{$segments[1]}();

            if (self::$halts) return;
          } else {
            // Call closure
            call_user_func(self::$callbacks[$route]);

            if (self::$halts) return;
          }
        }
      }
    } else {
      // Check if defined with regex
      $pos = 0;
      foreach (self::$routes as $route) {
        if (strpos($route, ':') !== false) {
          $route = str_replace($searches, $replaces, $route);
        }

        if (preg_match('#^' . $route . '$#', $uri, $matched)) {
          if (self::$methods[$pos] == $method || self::$methods[$pos] == 'ANY') {
            $foundRoute = true;

            // Remove $matched[0] as [1] is the first parameter.
            array_shift($matched);

            if (!is_object(self::$callbacks[$pos])) {

              // Grab all parts based on a / separator
              $parts = explode('/', self::$callbacks[$pos]);

              // Collect the last index of the array
              $last = end($parts);

              // Grab the controller name and method call
              $segments = explode('@', $last);

              // Instanitate controller
              $controller = new $segments[0]();

              // Call method
              call_user_func_array(array($controller, $segments[1]), $matched);

              if (self::$halts) return;
            } else {
              call_user_func_array(self::$callbacks[$pos], $matched);

              if (self::$halts) return;
            }
          }
        }
        $pos++;
      }
    }

    // Run the error callback if the route was not found
    if ($foundRoute == false) {
      if (!self::$errorCallback) {
        self::$errorCallback = function() {
          http_response_code(404);
          echo '404';
        };
      } else {
        if (is_string(self::$errorCallback)) {
          self::get($uri, self::$errorCallback);
          self::$errorCallback = null;
          self::dispatch();
          return ;
        }
      }
      call_user_func(self::$errorCallback);
    }
  }
}
