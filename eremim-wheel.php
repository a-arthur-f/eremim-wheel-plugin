<?php
/*
 * Plugin Name: Roleta Eremim
 */

if(!defined('ABSPATH')) {
  exit;
}

add_action('woocommerce_loaded', 'erw_loaded');

function erw_loaded() {
  require_once(plugin_dir_path(__FILE__) . '/eremim-wheel-settings.php');
  add_action('admin_menu', 'erw_add_settings_menu');
  add_action('admin_init', 'erw_init_settings_page');
  
  erw_render_wheel();
}

function erw_add_settings_menu() {
  add_menu_page(
    'Roleta Eremim',
    'Roleta Eremim',
    'manage_options',
    'roleta_eremim_settings',
    'erw_render_settings_page'
  );
}

function erw_render_settings_page() {
  require_once(plugin_dir_path(__FILE__) . '/templates/settings_page.php');
}

function erw_render_wheel() {
  $erw_id = get_option('erw_id');
  $erw_active = get_option('erw_active');
  $erw_start_date = get_option('erw_start_date');
  $erw_end_date = get_option('erw_end_date');
  $erw_img_wheel = get_option('erw_img_wheel');
  $erw_img_needle = get_option('erw_img_needle');

  if(erw_is_wheel_active(
    $erw_id, 
    $erw_active, 
    $erw_start_date, 
    $erw_end_date, 
    $erw_img_needle, 
    $erw_img_wheel
  )) {
    echo "<script>console.log('roleta ativa')</script>";
    require_once(plugin_dir_path(__FILE__) . '/templates/wheel.php');
    erw_render_wheel_template($erw_id, $erw_img_needle, $erw_img_wheel);
  }
}

function erw_is_wheel_active(
  $id,
  $active, 
  $start_date, 
  $end_date, 
  $img_needle, 
  $img_wheel
) {
  if(is_admin()) {
    return false;
  }

  if(empty($id)) {
    return false;
  }

  if($active != true) {
    return false;
  }

  if(!empty($start_date)) {
    $dateTime = new DateTimeImmutable($start_date);
    $now = time();

    if($dateTime->getTimestamp() > $now) {
      return false;
    }
  }

  if(!empty($end_date)) {
    $dateTime = new DateTimeImmutable($end_date);
    $now = time();

    if($dateTime->getTimestamp() < $now) {
      return false;
    }
  }

  if(empty($img_wheel)) {
    return false;
  }

  if(empty($img_needle)) {
    return false;
  }

  return true;
}
