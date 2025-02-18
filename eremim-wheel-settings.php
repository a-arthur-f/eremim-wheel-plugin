<?php
function erw_init_settings_page() {
  register_setting('erw_options', 'erw_active');
  register_setting('erw_options', 'erw_id');
  register_setting('erw_options', 'erw_end_date', array("sanitize_callback" => "sanitize_date"));
  register_setting('erw_options', 'erw_start_date', array("sanitize_callback" => "sanitize_date"));
  register_setting('erw_options', 'erw_img_needle');
  register_setting('erw_options', 'erw_img_wheel');
  register_setting('erw_options', 'erw_prize_1');
  register_setting('erw_options', 'erw_prize_2');
  register_setting('erw_options', 'erw_prize_3');
  register_setting('erw_options', 'erw_prize_4');
  register_setting('erw_options', 'erw_prize_type_1');
  register_setting('erw_options', 'erw_prize_type_2');
  register_setting('erw_options', 'erw_prize_type_3');
  register_setting('erw_options', 'erw_prize_type_4');

  add_settings_section(
    'erw_settings_section',
    'Opções da Roleta',
    '',
    'roleta_eremim_settings'
  );

  add_settings_field(
    'erw_field_active',
    'Roleta ativa',
    'erw_field_active_checkbox',
    'roleta_eremim_settings',
    'erw_settings_section'
  );
  add_settings_field(
    'erw_field_id',
    'ID da roleta',
    'erw_field_id_input',
    'roleta_eremim_settings',
    'erw_settings_section'
  );
  add_settings_field(

    'erw_field_start_date',
    'Data de inicio',
    'erw_field_start_date',
    'roleta_eremim_settings',
    'erw_settings_section'
  );
  add_settings_field(
    'erw_field_end_date',
    'Data de encerramento',
    'erw_field_end_date',
    'roleta_eremim_settings',
    'erw_settings_section'
  );
  add_settings_field(
    'erw_field_img_needle',
    'URL da imagem da agulha',
    'erw_field_img_needle',
    'roleta_eremim_settings',
    'erw_settings_section'
  );
  add_settings_field(
    'erw_field_img_wheel',
    'URL da imagem da roleta',
    'erw_field_img_wheel',
    'roleta_eremim_settings',
    'erw_settings_section'
  );

  add_settings_section(
    'erw_settings_prizes_section',
    '',
    'erw_prizes_section',
    'roleta_eremim_settings'
  );
  
  add_settings_field(
    'erw_field_prize_1',
    'Prêmio 1',
    'erw_field_prize',
    'roleta_eremim_settings',
    'erw_settings_prizes_section',
    array("number" => 1)
  );

  add_settings_field(
    'erw_field_prize_2',
    'Prêmio 2',
    'erw_field_prize',
    'roleta_eremim_settings',
    'erw_settings_prizes_section',
    array("number" => 2)
  );

  add_settings_field(
    'erw_field_prize_3',
    'Prêmio 3',
    'erw_field_prize',
    'roleta_eremim_settings',
    'erw_settings_prizes_section',
    array("number" => 3)
  );

  add_settings_field(
    'erw_field_prize_4',
    'Prêmio 4',
    'erw_field_prize',
    'roleta_eremim_settings',
    'erw_settings_prizes_section',
    array("number" => 4)
  );
}

function erw_field_active_checkbox() {
  $active = get_option('erw_active');
  ?>
    <input type="checkbox" name="erw_active" value="1" <?php checked($active) ?>/> 
  <?php 
}

function erw_field_id_input() {
  $id = get_option('erw_id');
  ?>
    <input type="text" size="26" name="erw_id" readonly required value="<?php echo isset($id) ? $id : ''?>"/> 
    <button id="gen-id" style="cursor: pointer;" type="button">Gerar novo id</button>
  <?php
}

function erw_field_start_date() {
  $date = get_option('erw_start_date');
  
  $datetime = "";

  if(isset($date) && !empty($date)) {
    $utc_timezone = new DateTimeZone("UTC");
    $utc_datetime = new DateTimeImmutable($date, $utc_timezone);

    $timezone = wp_timezone();
    $datetime = $utc_datetime->setTimezone($timezone);
    $datetime = $datetime->format("Y-m-d H:i");
  }

  ?>
    <input type="datetime-local" name="erw_start_date" value="<?php echo $datetime ?>"/> 
  <?php 
}

function erw_field_end_date() {
  $date = get_option('erw_end_date');

  $datetime = "";

  if(isset($date) && !empty($date)) {
    $utc_timezone = new DateTimeZone("UTC");
    $utc_datetime = new DateTimeImmutable($date, $utc_timezone);

    $timezone = wp_timezone();
    $datetime = $utc_datetime->setTimezone($timezone);
    $datetime = $datetime->format("Y-m-d H:i");
  }

  ?>
    <input type="datetime-local" name="erw_end_date" value="<?php echo $datetime ?>"/> 
  <?php 
}

function erw_field_img_needle() {
  $url = get_option('erw_img_needle');
  ?>
    <input required type="text" name="erw_img_needle" value="<?php echo isset($url) ? $url : ''?>"/> 
  <?php 
}

function erw_field_img_wheel() {
  $url = get_option('erw_img_wheel');
  ?>
    <input required type="text" name="erw_img_wheel" value="<?php echo isset($url) ? $url : ''?>"/> 
  <?php 
}

function erw_prizes_section() {
  ?>
    <p>Os prêmios são inseridos na ordem anti-horária da roleta</p>
  <?php
}

function erw_field_prize($args) {
  $prize = get_option("erw_prize_" . $args["number"]);
  $prize_type = get_option("erw_prize_type_" . $args["number"]);
  ?>
    <input 
      type="text" 
      name=<?php echo "erw_prize_" . $args["number"] ?>
      value=<?php echo isset($prize) ? $prize : '' ?>
    >
    <label>
      Desconto
      <input 
        type="radio"
        name=<?php echo "erw_prize_type_" . $args["number"] ?>
        value="desconto"
        checked
      >
    </label>
    <label>
      Produto
      <input 
        type="radio"
        name=<?php echo "erw_prize_type_" . $args["number"] ?>
        value="produto"
        <?php checked($prize_type, "produto") ?>
      >
    </label>
  <?php
}

function sanitize_date($date = null) {
  if(!isset($date) || empty($date)) {
    return "";
  }

  $timezone = wp_timezone();
  $datetime = new DateTimeImmutable($date, $timezone);

  $utc_timezone = new DateTimeZone("UTC");
  $utc_datetime = $datetime->setTimezone($utc_timezone);

  return $utc_datetime->format("Y-m-d H:i");
}
