<div class="wrap">
    <h1>Flexie WooCommerce Tracking</h1>
    <form method="post" action="options.php">
        <?php settings_fields( 'flexie-crm-settings' ); ?>
        <?php do_settings_sections( 'flexie-crm-settings' ); ?>
        <table class="form-table">
            <tr><?php settings_errors(); ?></tr>        
            <tr valign="top">
            <th scope="row">Your Flexie subdomain</th>
            <td><input type="text" id="flexie-subdomain-id" name="flexie_subdomain" value="<?php echo esc_attr( get_option('flexie_subdomain') ); ?>" /></td>
            </tr>
            <tr valign="top">
            <th scope="row">Your API key</th>
            <td><input type="text" name="flexie_api_key" value="<?php echo esc_attr( get_option('flexie_api_key') ); ?>" /></td>
            </tr>     
            <tr valign="top">
            <th scope="row">Track Products</th>
            <td><input type="checkbox" name="flexie_track_products" <?php checked(1, get_option('flexie_track_products'), true); ?> value="1" /></td>
            </tr>      
            <tr valign="top">
            <th scope="row">Track Cart</th>
            <td><input type="checkbox" name="flexie_track_cart" <?php checked(1, get_option('flexie_track_cart'), true); ?> value="1" /></td>
            </tr>
            <tr valign="top">
            <th scope="row">Track Order</th>
            <td><input type="checkbox" name="flexie_track_order" <?php checked(1, get_option('flexie_track_order'), true); ?> value="1" /></td>
            </tr>
        </table>
        <?php submit_button('Save Configuration', 'primary', 'flexie-crm-save-settings' ); ?>
    </form>
    </div>