<?php

/**
 * Template footer
 * @package WordPress
 * @subpackage I'LL

 */
?>




<script src="/wp-content/themes/rampup/assets/js/jquery.jaticker_1.0.0.js"></script>

<script>
$('#jaticker-2').jaticker({
  'autoStart': false
});

$('#jaticker-1').jaticker({
  'hideCursor': true,
  'onFinished': function() {
    $('#jaticker-2').jatickerStart();
    $(function() {
      $('.mv_img_copy p').css('background-color', 'black');
    });
  }
});
$(".date-box").hide();
$(".other-box").hide();

$('#check-box .wpcf7-list-item input').click(function() {

  $(".date-box").slideDown();
});
$('#check-box .wpcf7-list-item:not(.first) input').click(function() {
  $(".other-box").slideToggle(this.checked);
});
</script>

<script>
$(window).on('resize', function() {
  headerHeight = $('.l--header').height();
  $('#header').css({
    'height': headerHeight + 'px'
  });
})
</script>
<?php wp_footer(); ?>
</body>
