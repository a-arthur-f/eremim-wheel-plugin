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
  add_action('wp_loaded', 'erw_wp_loaded');
}

function erw_wp_loaded() {
  add_action('admin_menu', 'erw_add_settings_menu');
  add_action('admin_init', 'erw_init_settings_page');
  add_action('wp_ajax_erw_prize', 'erw_ajax_handler');
  add_action('wp_ajax_nopriv_erw_prize', 'erw_ajax_handler');
  add_action('woocommerce_add_to_cart', 'erw_add_to_cart');
  add_action('woocommerce_after_cart_item_quantity_update', 'erw_quantity_update', 10, 3);
  add_action('wp', 'erw_render_wheel');
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
  $erw_min_amount = get_option('erw_min_amount');
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

  $erw_min_amount = !empty($erw_min_amount) ? (float) $erw_min_amount : 0;

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
    erw_render_wheel_template($erw_id, $erw_min_amount, $erw_img_needle, $erw_img_wheel, $prizes);
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

function erw_ajax_handler() {
  $data = json_decode(file_get_contents('php://input'));
  $prize_type = $data->prize->type;
  $prize_value = $data->prize->value;
  
  if(!$prize_type || !$prize_value) {
    wp_die('', '', array('response' => 400));
  }

  $min_amount = (float) $data->minAmount;
  $cart_total = (float) WC()->cart->cart_contents_total;

  $added = false;

  $session_value = join('-', [$prize_type, $prize_value, $min_amount]);
  WC()->session->set('apply_prize', $session_value);

  if($cart_total >= $min_amount) {
    if($prize_type == "produto") {
      $added = erw_add_product($prize_value); 
    } else if($prize_type == "desconto") {
      $added = erw_add_discount($prize_value);
    }
  }

  wp_send_json(array('added' => $added), 200);
}

function erw_add_product($id) {
  $price = (float) wc_get_product($id)->get_price();

  $coupon = new WC_Coupon();
  $coupon->set_code(join('', ['erw', time()]));
  $coupon->set_usage_limit(1);
  $coupon->set_amount($price);
  $coupon->save();

  $added = WC()->cart->add_discount($coupon->get_code());

  if($added) {
    $added = WC()->cart->add_to_cart((int) $id);
  }

  return $added;
}

function erw_add_discount($coupon) {
  $added = WC()->cart->add_discount($coupon);
  return $added;
}

function erw_add_to_cart($item_key) {
  $session_value = WC()->session->get("apply_prize");
  if(empty($session_value)) {
    return;
  }
  
  [$type, $value, $min_amount] = explode('-', $session_value);

  $applied_coupon = '';
  $applied_coupons = WC()->cart->get_applied_coupons();

  foreach($applied_coupons as $coupon) {
    if(str_starts_with($coupon, 'erw') || $coupon == $value) {
      $applied_coupon = $coupon;
    }
  }
  
  $current_item_price = WC()->cart->get_cart_item($item_key)['data']->price;
  $cart_total = (float) WC()->cart->cart_contents_total + $current_item_price;

  if($cart_total < $min_amount && !empty($applied_coupon)) {
    WC()->cart->remove_coupon($applied_coupon);
    return;
  }
  
  if(empty($applied_coupon)) {
    $type == "produto" && erw_add_product($value) || 
    $type == "desconto" && erw_add_discount($value);
  }
}

function erw_quantity_update($item_key, $quantity, $old_quantity) {
  $session_value = WC()->session->get("apply_prize");
  if(empty($session_value)) {
    return;
  }
  
  [$type, $value, $min_amount] = explode('-', $session_value);

  $applied_coupon = '';
  $applied_coupons = WC()->cart->get_applied_coupons();

  foreach($applied_coupons as $coupon) {
    if(str_starts_with($coupon, 'erw') || $coupon == $value) {
      $applied_coupon = $coupon;
    }
  }
  
  $multiplier = $quantity < $old_quantity ? -1 : 1;
  $current_item_price = WC()->cart->get_cart_item($item_key)['data']->price;
  
  $cart_total = 0;
  
  if($type == "produto") {
    $cart_total = WC()->cart->cart_contents_total + $current_item_price * $multiplier;
  } else {
    $cart_total = WC()->cart->get_subtotal() + $current_item_price * $multiplier;
  }

  if($cart_total < $min_amount && !empty($applied_coupon)) {
    WC()->cart->remove_coupon($applied_coupon);
    return;
  }

  if(empty($applied_coupon)) {
    $type == "produto" && erw_add_product($value) || 
    $type == "desconto" && erw_add_discount($value);
  }
}
