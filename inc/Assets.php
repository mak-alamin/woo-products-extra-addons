<?php

namespace WooExtraAddonsInc;

class Assets
{
    public function register()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
    }

    public function enqueue_admin_styles()
    {
        wp_enqueue_style('extra-addon-styles', SC_EXTRA_ADDONS_ASSETS . 'css/admin-style.css', null, time());
    }
}
