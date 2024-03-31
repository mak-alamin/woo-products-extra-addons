<?php

namespace WooExtraAddonsInc;

class Extra_Addons
{
    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'add_custom_product_addons_meta_box'));

        add_action('save_post_product', array($this, 'save_custom_product_addons_meta'));

        add_action('woocommerce_before_add_to_cart_button', array($this, 'display_custom_product_addons'));

        add_filter('woocommerce_add_cart_item_data', array($this, 'add_addon_price_to_cart_item_data'), 10, 3);

        add_filter('woocommerce_get_item_data', array($this, 'get_item_data'), 10, 2);

        add_action('woocommerce_before_calculate_totals', array($this, 'calculate_total_price'), 10, 2);

        add_action('woocommerce_new_order_item',array($this, 'add_extra_item_to_order'),10,3);
    }
   
    function add_extra_item_to_order( $item_id, $item, $order_id)
    {
        $addon_title = '';
        $addon_price = '';
        
        if(isset($item->legacy_values['addon_option'])){
            $_item = $item->legacy_values;

            $addon_items = get_post_meta($item['product_id'], 'product_extra_addons', true);
            
            if (isset($addon_items[$_item['addon_option']])) {
                $addon_title = $addon_items[$_item['addon_option']]['title'];
                
                $addon_price = wc_price($addon_items[$_item['addon_option']]['price']);
            }

            wc_add_order_item_meta($item_id, 'Extra Item', $addon_title . ' (' . $addon_price . ')');
        }
    }

    /**
     * Display addon item data in the cart
     */
    function get_item_data($item_data, $cart_item_data)
    {
        if (isset($cart_item_data['addon_option'])) {
            $addon_items = get_post_meta($cart_item_data['product_id'], 'product_extra_addons', true);

            if (isset($addon_items[$cart_item_data['addon_option']])) {

                $addon_title = $addon_items[$cart_item_data['addon_option']]['title'];

                $addon_price = $addon_items[$cart_item_data['addon_option']]['price'];

                $item_data[] = array(
                    'key'   => __('Extra Item', 'woo-products-extra-addons'),
                    'value' => wc_clean($addon_title) . ' (' . wc_price($addon_price) . ')'
                );
            }
        }
        return $item_data;
    }

    public function add_custom_product_addons_meta_box()
    {
        add_meta_box(
            'custom_product_addons',
            __('Product Addons', 'products-extra-addons'),
            array($this, 'custom_product_addons_meta_box_callback'),
            'product',
            'normal',
            'high'
        );
    }

    public function custom_product_addons_meta_box_callback($post)
    {
        $addons = get_post_meta($post->ID, 'product_extra_addons', true);
?>
        <div id="addons-container">
            <?php
            if (empty($addons)) {
                echo 'No item founds.';
            } else {
                foreach ($addons as $index => $addon) { ?>
                    <div class="addon-row">
                        <div>
                            <label for="addon_title_<?php echo $index; ?>"><?php _e('Addon Title', 'products-extra-addons'); ?></label>
                            <input type="text" name="product_extra_addons[<?php echo $index; ?>][title]" id="addon_title_<?php echo $index; ?>" value="<?php echo esc_attr($addon['title']); ?>" required>
                        </div>
                        <div>
                            <label for="addon_price_<?php echo $index; ?>"><?php _e('Addon Price', 'products-extra-addons'); ?></label>
                            <input type="number" step="0.01" min="0" name="product_extra_addons[<?php echo $index; ?>][price]" id="addon_price_<?php echo $index; ?>" value="<?php echo esc_attr($addon['price']); ?>">
                        </div>
                        <div><span class="dashicons dashicons-trash remove-addon"></span></div>
                    </div>
            <?php }
            } ?>
        </div>
        <button type="button" id="add-new-addon" class="button"><?php _e('Add Item', 'products-extra-addons'); ?></button>
        <?php
    }

    public function save_custom_product_addons_meta($post_id)
    {
        if (isset($_POST['product_extra_addons'])) {
            update_post_meta($post_id, 'product_extra_addons', $_POST['product_extra_addons']);
        } else {
            update_post_meta($post_id, 'product_extra_addons', array());
        }
    }

    public function display_custom_product_addons()
    {
        global $product;
        $addons = get_post_meta($product->get_id(), 'product_extra_addons', true);

        if ($addons) {
            echo '<div class="product-extra-addons">';
            echo '<h3>Extra Items: </h3>';
            foreach ($addons as $addon_sl => $addon) {
        ?>
                <label class="extra-addons">
                    <span class="title">
                        <input type="radio" name="addon_option" value="<?php echo esc_attr($addon_sl); ?>">

                        <strong><?php echo esc_html($addon['title']); ?></strong>
                    </span>

                    <span class="price">
                        <?php printf('+ $%s', number_format(floatval($addon['price']), 2)); ?>
                    </span>
                </label>
<?php
            }

            echo '</div>';
        }
    }

    public function calculate_total_price($cart)
    {
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
            $addon_items = get_post_meta($cart_item['product_id'], 'product_extra_addons', true);

            $addon_price = 0;
            
            if ( isset($cart_item['addon_option']) && isset($addon_items[$cart_item['addon_option']])) {
                $addon_price = isset($addon_items[$cart_item['addon_option']]['price']) ? $addon_items[$cart_item['addon_option']]['price'] : 0;
            }

            $product = wc_get_product($cart_item['product_id']);

            $new_price = (floatval($product->get_price()) + floatval($addon_price));

            $cart_item['data']->set_price($new_price);
        }
    }

    public function add_addon_price_to_cart_item_data($cart_item_data, $product_id, $variation_id)
    {
        if (isset($_POST['addon_option'])) {
            $cart_item_data['addon_option'] = $_POST['addon_option'];
        }
        return $cart_item_data;
    }
}
