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
  
  $erw_prize_1 = get_option('erw_prize_1');
  $erw_prize_type_1 = get_option('erw_prize_type_1');
  $erw_prize_2 = get_option('erw_prize_2');
  $erw_prize_type_2 = get_option('erw_prize_type_2');
  $erw_prize_3 = get_option('erw_prize_3');
  $erw_prize_type_3 = get_option('erw_prize_type_3');
  $erw_prize_4 = get_option('erw_prize_4');
  $erw_prize_type_4 = get_option('erw_prize_type_4');

  $prizes = [
    [
      "type" => $erw_prize_type_1,
      "value" => $erw_prize_1
    ],
    [
      "type" => $erw_prize_type_2,
      "value" => $erw_prize_2
    ],
    [
      "type" => $erw_prize_type_3,
      "value" => $erw_prize_3
    ],
    [
      "type" => $erw_prize_type_4,
      "value" => $erw_prize_4
    ],
  ];

  if(erw_is_wheel_active(
    $erw_id, 
    $erw_active, 
    $erw_start_date, 
    $erw_end_date, 
    $erw_img_needle, 
    $erw_img_wheel,
    $prizes
  )) {
    echo "<script>console.log('roleta ativa')</script>";
    require_once(plugin_dir_path(__FILE__) . '/templates/wheel.php');
    erw_render_wheel_template($erw_id, $erw_img_needle, $erw_img_wheel, $prizes);
  }
}

function erw_is_wheel_active(
  $id,
  $active, 
  $start_date, 
  $end_date, 
  $img_needle, 
  $img_wheel,
  $prizes
) {
  if(is_admin() || is_login()) {
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

  for($i = 0; $i < count($prizes); $i++) {
    if(empty($prizes[$i]['type']) || empty($prizes[$i]['value'])) {
      return false;
    }
  }

  return true;
}
