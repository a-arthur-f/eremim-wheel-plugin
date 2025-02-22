<?php
wp_enqueue_style("erw_wheel_style", plugins_url("/assets/css/wheel.css", __DIR__));
wp_enqueue_script("erw_wheel_script", plugins_url("/assets/js/wheel.js", __DIR__));

function erw_render_wheel_template($erw_id, $erw_img_needle, $erw_img_wheel, $prizes) {
$prize_1 = $prizes[0]['type'] . '-' . $prizes[0]['value'];
$prize_2 = $prizes[1]['type'] . '-' . $prizes[1]['value'];
$prize_3 = $prizes[2]['type'] . '-' . $prizes[2]['value'];
$prize_4 = $prizes[3]['type'] . '-' . $prizes[3]['value'];

?>
  <div 
      class="erw-wheel" 
      data-id="<?php echo $erw_id ?>"
      data-prize-1="<?php echo $prize_1 ?>"
      data-prize-2="<?php echo $prize_2 ?>"
      data-prize-3="<?php echo $prize_3 ?>"
      data-prize-4="<?php echo $prize_4 ?>"
  >
    <img class="erw-wheel__needle" src="<?php echo $erw_img_needle ?>" alt="Imagem da agulha"/>
    <img class="erw-wheel__wheel" src="<?php echo $erw_img_wheel ?>" alt="Imagem da roleta"/>
  </div>
<?php
}
?>
