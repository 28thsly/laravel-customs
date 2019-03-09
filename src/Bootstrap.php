<?php

namespace Artinict;

use Composer\Installer\PackageEvent;

class Bootstrap
{

    public static function postPackageInstall(PackageEvent $event)
    {

      $currentDir = dirname(__FILE__);
      $baseDir = dirname(dirname($currentDir));

      //copy customs configuration file to the config directory
      $cusfigurationPath = $baseDir.'\\config\\cusfiguration.php';

      if( ! file_exists($cusfigurationPath) )
         @copy($currentDir.'\\cusfiguration.php', $cusfigurationPath);

    }

}
