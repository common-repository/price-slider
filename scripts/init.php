<?php



add_action('admin_menu', 'medicalprice_plugin_menu');

function medicalprice_plugin_menu() {
    add_options_page('Price Slider Options', 'Price Slider', 'manage_options', 'price_slider', 'medicalprice_plugin_options');
}

?>