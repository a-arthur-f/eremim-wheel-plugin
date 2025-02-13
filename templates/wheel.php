<?php
wp_enqueue_style("erw_wheel_style", plugins_url("/assets/css/wheel.css", __DIR__));

function erw_render_wheel_template($erw_id, $erw_img_needle, $erw_img_wheel) {
?>
  <div class="erw-wheel" data-id="<?php echo $erw_id ?>">
    <img class="erw-wheel__needle" src="<?php echo $erw_img_needle ?>" alt="Imagem da agulha"/>
    <img class="erw-wheel__wheel" src="<?php echo $erw_img_wheel ?>" alt="Imagem da roleta"/>
  </div>
<?php
}
?>
