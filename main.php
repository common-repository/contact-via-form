<?php
/*
Plugin Name: Contact via Form
Plugin URI: #
Description: Simple WordPress Contact Form
Version: 1.0
Author: Debabrat Sharma
Author URI: https://www.facebook.com/debabrat.sharma.31
*/


/*html form */
function cvf_html_form_code() {
    echo '<div style="margin:10px;">';
    echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
    echo '<p>';
    echo 'Your Name (required) <br />';
    echo '<input type="text" name="ds-name" pattern="[a-zA-Z0-9 ]+"  size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Your Email (required) <br />';
    echo '<input type="email" name="ds-email"  size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Subject (required) <br />';
    echo '<input type="text" name="ds-subject" pattern="[a-zA-Z ]+"  size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Your Message (required) <br />';
    echo '<textarea rows="10" cols="35" name="ds-message"></textarea>';
    echo '</p>';
    echo wp_nonce_field( 'cvform', 'cvfromName' );
    echo '<p><input type="submit" name="ds-submitted" value="Send"/></p>';
    echo '</form>';
}   echo '</div>';
/*End of html form */


/*Deliver mail nad save data*/
function cvf_deliver_mail() {
global $wpdb;
    // if the submit button is clicked, send the email
    if ( isset( $_POST['ds-submitted'] ) || isset( $_POST['cvfromName'] ) || wp_verify_nonce( $_POST['cvform'], 'cvfromName' ) ) {

        // sanitize form values
        $name    = sanitize_text_field( $_POST["ds-name"] );
        $email   = sanitize_email( $_POST["ds-email"] );
        $subject = sanitize_text_field( $_POST["ds-subject"] );
        $message = esc_textarea( $_POST["ds-message"] );

        // get the blog administrator's email address
        $to = get_option( 'admin_email' );

        $headers = "From: $name <$email>" . "\r\n";

        // If email has been process for sending, display a success message
        if ( wp_mail( $to, $subject, $message, $headers ) ) {
            echo '<div>';
            echo '<p>Thanks for contacting me, expect a response soon.</p>';
            echo '</div>';
        } else {
            echo 'An unexpected error occurred';
        }
    }
}
/*End of Deliver mail nad save data*/



/*Final shortcode*/
function cvf_ds_shortcode() {
    ob_start();
    cvf_deliver_mail();
    cvf_html_form_code();
    return ob_get_clean();
}
add_shortcode( 'cvf_form', 'cvf_ds_shortcode' );
/*End of Final shortcode*/

    
?>