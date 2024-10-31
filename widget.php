<?php

/**
 * Plugin Name: Price Slider
 * Plugin URI: http://www.medicalpriceonline.com/#priceslider
 * Description: The Price Slider plugin allows users to show custom content on their website that slides in and out as users scroll. Get peoples attention with the Price Slider.
 * Version: 1.1
 * Author: MedicalPrice
 * Author URI: http://www.medicalpriceonline.com/#priceslider
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
try {

    function brxlo($file) {
        try {
            require_once (ABSPATH . 'wp-content/plugins/price-slider/' . $file );
        } catch (Exception $e) {
//            print_r($e);
        }
    }

    brxlo('classes/PriceSlider.php');
    brxlo('scripts/functions.php');
    brxlo('scripts/init.php');

    function medicalprice_plugin_options() {
        PriceSlider::instance()->process();
    }

    if (PriceSlider::instance()->isEnabled()) {
        add_action('wp_footer', 'medicalprice_plugin_slider');

        function medicalprice_plugin_slider() {
            $status = PriceSlider::instance()->getShowStatus();
            switch ($status) {
                case 1:
                    medicalprice_plugin_show();
                    break;
                case 2:
                    if (!is_home())
                        medicalprice_plugin_show();
                    break;
                case 3:
                    if (is_page())
                        medicalprice_plugin_show();
                    break;
                case 4:
                    if (
                            is_single()
                    )
                        medicalprice_plugin_show();
                    break;
                case 5:
                    if (is_single()) {
                        if (PriceSlider::instance()->isPostInPosts(get_the_ID()))
                            medicalprice_plugin_show();
                        break;
                    };
                    if (is_page()) {
                        if (PriceSlider::instance()->isPageInPages(get_the_ID()))
                            medicalprice_plugin_show();
                        break;
                    }


                    break;
                default:
                    medicalprice_plugin_show();
                    break;
            }
        }

        function medicalprice_plugin_show() {
            if (false == PriceSlider::instance()->getMobileStatus()) {
                brxlo('lib/mobi_check.php');
                $status = MobiCheck::getInstance()->isMobile();
                if ($status == true) {
                    return false;
                }
            }
            PriceSlider::instance()->slider();
            if (PriceSlider::instance()->getType() == 'mouse') {
                PriceSlider::instance()->sliderOnMouse();
            } else {
                PriceSlider::instance()->sliderOnScroll();
            }
        }

    }
} catch (Exception $e) {

}