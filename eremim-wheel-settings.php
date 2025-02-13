<?php
function erw_init_settings_page() {
  register_setting('erw_options', 'erw_active');
  register_setting('erw_options', 'erw_id');
  register_setting('erw_options', 'erw_end_date');
  register_setting('erw_options', 'erw_start_date');
  register_setting('erw_options', 'erw_img_needle');
  register_setting('erw_options', 'erw_img_wheel');

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
    <input type="text" size="26" name="erw_id" readonly  value="<?php echo isset($id) ? $id : ''?>"/> 
    <button id="gen-id" style="cursor: pointer;" type="button">Gerar novo id</button>
  <?php
}

function erw_field_start_date() {
  $date = get_option('erw_start_date');
  ?>
    <input type="date" name="erw_start_date" value="<?php echo isset($date) ? $date : ''?>"/> 
  <?php 
}

function erw_field_end_date() {
  $date = get_option('erw_end_date');
  ?>
    <input type="date" name="erw_end_date" value="<?php echo isset($date) ? $date : ''?>"/> 
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
