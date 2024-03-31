<?php

namespace WooExtraAddonsInc;

class Assets
{
    public function register()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));

        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function enqueue_frontend_scripts(){
        wp_enqueue_style('extra-addon-frontend', SC_EXTRA_ADDONS_ASSETS . 'css/frontend-style.css', null, time());
    }

    public function enqueue_admin_scripts()
    {
        wp_enqueue_style('extra-addon-admin', SC_EXTRA_ADDONS_ASSETS . 'css/admin-style.css', null, time());

        wp_enqueue_script('extra-addon-admin', SC_EXTRA_ADDONS_ASSETS . 'js/admin-script.js', array('jquery'), time(), true );
    }
}
