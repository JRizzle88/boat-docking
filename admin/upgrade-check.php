<?php

function boat_docking_upgradecheck() {
   //Version of currently activated plugin
   $current_version = '1.0';
   //Database version - this may need upgrading.
   $installed_version = get_option('boat_docking_version');

   if( !$installed_version ){
       //No installed version - we'll assume its just been freshly installed
       add_option('boat_docking_version', $current_version);

   } elseif( $installed_version != $current_version ){
         /*
          * If this is an old version, perform some updates.
          */

         //Installed version is before 1.1 - upgrade to 1.1
         //if( version_compare('1.1', $installed_version) ){
             //Code to upgrade to version 1.1
         //}

         //Installed version is before 1.3 - upgrade to 1.3
         //if( version_compare('1.3', $installed_version) ){
             //Code to upgrade to version 1.3
         //}

         //Database is now up to date: update installed version to latest version
         update_option('boat_docking_version', $current_version);
   }
}
