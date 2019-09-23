<div class="wrap">
    <h1>Flexie WooCommerce Tracking</h1>
    <form method="post" action="options.php">
        <?php settings_fields( 'flexie-crm-settings' ); ?>
        <?php do_settings_sections( 'flexie-crm-settings' ); ?>
        <table class="form-table">
            <tr><?php settings_errors(); ?></tr>        
            <tr valign="top">
            <th scope="row">Your Flexie subdomain <span style="color: red;">*</span></th>
            <td><input type="text" id="flexie-subdomain-id" name="flexie_subdomain" value="<?php echo esc_attr( get_option('flexie_subdomain') ); ?>" />.flexie.io</td>
            </tr>
            <tr valign="top">
            <th scope="row">Your API key <span style="color: red;">*</span></th>
            <td><input type="text" name="flexie_api_key" value="<?php echo esc_attr( get_option('flexie_api_key') ); ?>" /></td>
            </tr>     
            <tr valign="top">
            <th scope="row">Track Product</th>
            <td><input type="checkbox" name="flexie_track_product" <?php checked(1, get_option('flexie_track_product'), true); ?> value="1" /></td>
            </tr>      
            <tr valign="top">
            <th scope="row">Track Cart</th>
            <td><input type="checkbox" name="flexie_track_cart" <?php checked(1, get_option('flexie_track_cart'), true); ?> value="1" /></td>
            </tr>
            <tr valign="top">
            <th scope="row">Track Order</th>
            <td><input type="checkbox" name="flexie_track_order" <?php checked(1, get_option('flexie_track_order'), true); ?> value="1" /></td>
            </tr>
            <tr valign="top">
            <th scope="row">Track PageHit</th>
            <td><input type="checkbox" name="flexie_track_pagehit" <?php checked(1, get_option('flexie_track_pagehit'), true); ?> value="1" /></td>
            </tr>
            <tr valign="top">
            <th colspan = 2 scope="row" style="font-size:12px; font-style:italic; color: red;">
            NOTE: Login to your Flexie CRM account with the domain above and under your Account Name (in the top, righthand corner) > Account > API Settings, you will find your API Key.
            </th></tr>
        </table>
        <?php submit_button('Save Configuration', 'primary', 'flexie-crm-save-settings' ); ?>
    </form>
    </div>