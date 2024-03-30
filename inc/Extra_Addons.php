<?php

namespace WooExtraAddonsInc;

class Extra_Addons {
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_custom_product_addons_meta_box'));
        add_action('save_post_product', array($this, 'save_custom_product_addons_meta'));

        add_action('woocommerce_before_add_to_cart_button', array($this, 'display_custom_product_addons'));

        add_action('woocommerce_before_calculate_totals', array($this, 'calculate_total_price'));

        add_filter('woocommerce_add_cart_item_data', array($this, 'add_addon_price_to_cart_item_data'), 10, 3);

        add_action('admin_footer', array($this, 'add_new_addon_button'));
        add_action('admin_footer', array($this, 'add_new_addon_script'));
    }

    public function add_custom_product_addons_meta_box() {
        add_meta_box(
            'custom_product_addons',
            __('Product Addons', 'products-extra-addons'),
            array($this, 'custom_product_addons_meta_box_callback'),
            'product',
            'normal',
            'high'
        );
    }

    public function custom_product_addons_meta_box_callback($post) {
        $addons = get_post_meta($post->ID, 'product_extra_addons', true);
        if (!$addons) {
            $addons = array(array('title' => '', 'price' => ''));
        }
        ?>
        <div id="addons-container">
            <?php foreach ($addons as $index => $addon) : ?>
                <div class="addon-row">
                    <div>
                        <label for="addon_title_<?php echo $index; ?>"><?php _e('Addon Title', 'products-extra-addons'); ?></label>
                        <input type="text" name="product_extra_addons[<?php echo $index; ?>][title]" id="addon_title_<?php echo $index; ?>" value="<?php echo esc_attr($addon['title']); ?>">
                    </div>
                    <div>
                        <label for="addon_price_<?php echo $index; ?>"><?php _e('Addon Price', 'products-extra-addons'); ?></label>
                        <input type="number" step="0.01" min="0" name="product_extra_addons[<?php echo $index; ?>][price]" id="addon_price_<?php echo $index; ?>" value="<?php echo esc_attr($addon['price']); ?>">
                    </div>
                    <?php if ($index > 0) : ?>
                        <div><button type="button" class="remove-addon"><?php _e('Remove', 'products-extra-addons'); ?></button></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" id="add-new-addon"><?php _e('Add New', 'products-extra-addons'); ?></button>
        <?php
    }

    public function save_custom_product_addons_meta($post_id) {
        if (isset($_POST['product_extra_addons'])) {
            update_post_meta($post_id, 'product_extra_addons', $_POST['product_extra_addons']);
        }
    }

    public function display_custom_product_addons() {
        global $product;
        $addons = get_post_meta($product->get_id(), 'product_extra_addons', true);

        if ($addons) {
            foreach ($addons as $addon) {
                ?>
                <div>
                    <h3><?php echo esc_html($addon['title']); ?></h3>
                    <label>
                        <input type="radio" name="addon_option" value="<?php echo esc_attr($addon['price']); ?>">
                        <?php printf('+ $%s', number_format($addon['price'], 2)); ?>
                    </label>
                </div>
                <?php
            }
        }
    }

    public function calculate_total_price($cart) {
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
            $addon_price = isset($cart_item['addons_price']) ? $cart_item['addons_price'] : 0;
            $product = $cart_item['data'];
            $product_price = $product->get_price();
            $cart_item['data']->set_price($product_price + $addon_price);
        }
    }

    public function add_addon_price_to_cart_item_data($cart_item_data, $product_id, $variation_id) {
        if (isset($_POST['addon_option'])) {
            $cart_item_data['addons_price'] = floatval($_POST['addon_option']);
        }
        return $cart_item_data;
    }

    public function add_new_addon_button() {
        ?>
        <script>
            function addNewAddonRow() {
                var container = document.getElementById('addons-container');
                var index = container.querySelectorAll('.addon-row').length;
                var newRow = document.createElement('div');
                newRow.classList.add('addon-row');
                newRow.innerHTML = `
                    <div>
                        <label for="addon_title_${index}"><?php _e('Addon Title', 'products-extra-addons'); ?></label>
                        <input type="text" name="product_extra_addons[${index}][title]" id="addon_title_${index}" value="">
                    </div>
                    <div>
                        <label for="addon_price_${index}"><?php _e('Addon Price', 'products-extra-addons'); ?></label>
                        <input type="number" step="0.01" min="0" name="product_extra_addons[${index}][price]" id="addon_price_${index}" value="">
                    </div>
                    <div><button type="button" class="remove-addon"><?php _e('Remove', 'products-extra-addons'); ?></button></div>
                `;
                container.appendChild(newRow);
            }

            document.getElementById('add-new-addon').addEventListener('click', addNewAddonRow);
        </script>
        <?php
    }

    public function add_new_addon_script() {
        ?>
        <script>
            document.addEventListener('click', function (e) {
                if (e.target && e.target.classList.contains('remove-addon')) {
                    e.target.closest('.addon-row').remove();
                }
            });
        </script>
        <?php
    }
}
