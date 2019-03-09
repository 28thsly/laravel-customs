<?php

namespace App\Artinict;


class LaravelCustoms
{

  public static function __callStatic($class, $args)
  {

    $currentDir = dirname(__FILE__);
    $baseDir = dirname(dirname($currentDir));

    //Load customs configuration file
    $cusfigurationPath = $baseDir.'\\config\\cusfiguration.php';
    if( ! file_exists($cusfigurationPath) )
      if( ! @copy($currentDir.'\\cusfiguration.php', $cusfigurationPath))
          throw new \Exception("Cusfiguration.php NOT FOUND. Kindly reinstall the Laravel customs package.");

    $cusfiguration = require $cusfigurationPath;

    $APP_NAME = $cusfiguration['LC_APP_NAMESPACE'];
    $appDir = $baseDir.'\\'.$APP_NAME;

    //If class doesn't exist in the global array,
    if( ! @array_key_exists($class, $cusfiguration)){

      //Check for prefix and break down it down
      $pou = strrpos($class, '_');
      $prefix = substr($class, 0, $pou);
      $alias = substr($class, $pou + 1);

      //If prefix exists(Underscore)
      if( $prefix !== false){

        //if the Prefix exists in the global config
        if( @array_key_exists($prefix, $cusfiguration)){

          //if the Alias exists in the prefix config,
          if(array_key_exists($alias, $cusfiguration[$prefix]))
              $className = $cusfiguration[$prefix][$alias];
          else
            $className = static::autoResolveClassname($appDir, $appDir, $APP_NAME, $class);

        }
        else{
          $className = static::autoResolveClassname($appDir, $appDir, $APP_NAME, $class);
        }

      }
      else{
        $className = static::autoResolveClassname($appDir, $appDir, $APP_NAME, $class);
      }

    }
    else{
      $className = $cusfiguration[$class];
    }

    if($className === null)
      throw new \Exception("Class \"$class\" cannot be found. Add an alias: \"$class\" => Classname::class in config/cusfiguration.php");

    //If argument is NULL, it means the user just wants the class instance
    if($args == null)
      return $className;

    //Resolve method and arguments
      $methodName = $args[0]; //The first argument you pass is the method name.

      $params = [];
      for($i = 1; $i < count($args); $i++)
        $params[] = $args[$i];

      if(substr($methodName, 0, 2) == "::")
        $methodName = substr($methodName, 2);
      else
        $className = new $className;

      return call_user_func_array(array($className, $methodName), $params);


  }

  public static function autoResolveClassname($cacheBaseDir, $recursiveBaseDir, $appName, $class)
  {

    $namespaces = [];

    $dir = scandir($recursiveBaseDir);

    for($i = 2; $i < count($dir); $i++) { //skip . and ..

      $newBaseDir = $recursiveBaseDir.$dir[$i];

      if(! is_file($newBaseDir)){

        $newBaseDir .= '\\';
        static::autoResolveClassname($cacheBaseDir, $newBaseDir, $appName, $class);

      }
      else{

       $appNamespaced = str_replace($cacheBaseDir, $appName, $newBaseDir);
       $namespaced = substr($appNamespaced, 0, -4);
       $className = substr($appNamespaced, strrpos($appNamespaced, '\\') + 1, -4);

       if($class == $className)
          return $namespaced;

      }

    }

  }


}
