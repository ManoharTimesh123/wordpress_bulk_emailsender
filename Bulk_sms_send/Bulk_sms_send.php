<?php
/**
 * Plugin Name: Bulk_sms_send
 * Plugin URI: https://wordpress.org/plugins/Bulk_sms_send/
 * Description: A powerful Bulk SMS Messaging/Texting plugin for WordPress - This plugin is a fork from https://wordpress.org/plugins/wp-sms/ by VeronaLabs
 * Version: 1.0.7
 * Author: SMS.to
 * Author URI: https://sms.to
 * Text Domain: Bulk_sms_send
 * Domain Path: /languages
 */




register_activation_hook(__FILE__,'form_data_activate');
register_deactivation_hook(__FILE__,'form_data_deactivate');


function form_data_activate()
{
    global $wpdb;
    global $table_prefix;
    $data_user=$table_prefix.'users';
    $data_sql=$wpdb->get_results("SELECT * FROM $data_user");
    $table=$table_prefix.'Sms_data';
    $sql ="CREATE TABLE $table (message TEXT NOT NULL, visible INT NOT NULL DEFAULT 1 ) AS SELECT ID, user_nicename, user_email FROM $data_user";

    $wpdb->query($sql);
}

function form_data_deactivate()
{
    global $wpdb;
    global $table_prefix;
    $table=$table_prefix.'Sms_data';
    $sql="DROP TABLE $table";
    $wpdb->query($sql);

}


add_action('admin_menu','sms_send');

function sms_send(){
    add_menu_page('Bulk_Sms_Send','Bulk_Sms_Send',8,__FILE__,'sms_send2');

}
function sms_send2(){
    require_once('sms_send.php');

}


?>
