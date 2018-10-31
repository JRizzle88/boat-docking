<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://joshdaleriley.com
 * @since      1.0.0
 *
 * @package    Boat_Docking
 * @subpackage Boat_Docking/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap boat-docking-admin-header">
    <h2>Boat Docking Reservations</h2>
</div>
<div class="wrap boat-docking-admin">
    <div id="icon-themes" class="icon32"></div>

        <?php
            settings_errors();

            $settings_data = Boat_Docking_Admin::get_settings_data();

            if( isset( $_GET[ 'tab' ] ) ) {
                $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : reset($settings_data)->id;
            } else {
                $active_tab = reset($settings_data)->id;
            }
        ?>
        <h2 class="nav-tab-wrapper">
            <?php
                foreach ($settings_data as $setting) {
                    echo '<a href="?page=boat-docking&tab=' . $setting->id . '" class="nav-tab ' . ($active_tab == $setting->id ? 'nav-tab-active' : '') . '">' . $setting->title . '</a>';
                }
            ?>
        </h2>

        <form method="post" action="options.php">

            <?php
                foreach ($settings_data as $setting) {
                    if ($active_tab == $setting->id) {
                        settings_fields($setting->page);
                        do_settings_sections($setting->page);
                    }
                }
                submit_button();
            ?>

        </form>

    </div>
