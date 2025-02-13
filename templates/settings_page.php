<?php
wp_enqueue_script('erw_create_id', plugins_url('/assets/js/createId.js', __DIR__));
?>

<div class="wrap">
  <h1>Roleta Eremim</h1>
  <form action="options.php" method="post">
    <?php
      settings_fields('erw_options');
      do_settings_sections('roleta_eremim_settings');
      submit_button();
    ?>
  </form>
</div>
