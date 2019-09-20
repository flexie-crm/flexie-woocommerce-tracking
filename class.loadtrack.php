<?php
/**
 * Registers flexie_metadata_script and sets the parameters 
 * for the object flexie_metadata_script_object used in DOM
 * 
 * @link       https://flexie.io/
 * @since      1.0.0
 *
 * @package    Flexie-CRM
 * @author     Flexie CRM
 */

class LoadTrack {  
    /**
     * Called when WooCommerce registered Webhooks are fired.  
     * Details are set in metadata key passed to flexie_medatada_script_object 
     */
    public static function trackMetaData( $data, $type, $cookie_set ){
        wp_enqueue_script( 'flexie_metadata_script', plugin_dir_url(__FILE__).'assets/js/flexie_metadata_script.js', array(), null, false );
        if( $cookie_set ) {
            $params = array( 
                'objectType'    =>$type,
                'metadata'      => $data
            ); 
        } else {
            $params = array( 
                'objectType'    =>$type,
                'metadata'      => $data,
                'email'         => wp_get_current_user()->user_email
            ); 
        }      
        wp_localize_script( 'flexie_metadata_script', 'flexie_metadata_script_object', 
            $params
        );	
    }
    /**
     * Called when there is no tracking cookie set and the user is logged in.  
     */
    public static function trackPageHit($email_is_set){
        wp_enqueue_script( 'flexie_metadata_script', plugin_dir_url(__FILE__).'assets/js/flexie_metadata_script.js', array(), null, false );
        if ( $email_is_set ){   
            wp_localize_script( 'flexie_metadata_script', 'flexie_metadata_script_object',
                array(
                    'pageHit'       => true,
                    'registerEmail' => wp_get_current_user()->user_email
                )
            );
        }       	
    }
}