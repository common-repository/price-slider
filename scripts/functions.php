<?php

if (!function_exists('extEcho')) {

    /**
     * extended echo
     * @param $in
     * @return unknown_type
     */
    function extEcho($in, $label = false) {

        $allow = false;
        $allow = true;

        /*
          if (isset($_SERVER['REMOTE_ADDR'])) {
          if ($_SERVER['REMOTE_ADDR'] == '83.3.229.114')
          $allow = true;
          if ($_SERVER['REMOTE_ADDR'] == '94.152.135.225')
          $allow = true;
          if ($_SERVER['REMOTE_ADDR'] == '91.150.221.126')
          $allow = true;
          if ($_SERVER['REMOTE_ADDR'] == '91.150.201.251')
          $allow = true;
          }

          if (isset($_SERVER['SHELL'])) {
          if ($_SERVER['SHELL'] == '/bin/bash')
          $allow = true;
          if ($_SERVER['SHELL'] == '/bin/sh')
          $allow = true;
          }
         */

        if ($allow) {
            echo "\n<div style='text-align: left;'>";
            echo "<pre>";
            if ($label)
                echo "\n{$label}";
            echo "\n";
//  echo "zmienna: '". ${$in} . "' : ";
            var_dump($in);
            echo "</pre>";
            echo "</div>\n";
        }
    }

}
