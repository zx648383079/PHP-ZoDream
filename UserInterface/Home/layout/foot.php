
<div class="footer">
	<a class="ms-Link" href="http://www.miitbeian.gov.cn/" target="_blank">湘ICP备16003508号</a>
</div>
<?php
$this->jcs(array(
    'before' => array(
        'system/system.min',
        'jquery/jquery-2.2.2.min',
        'jquery/jquery.fabric.min'
     ),
    function() {?>
<script type="text/javascript">
System.config({
    baseURL: '/assets/js/',
    paths: {
      'jquery/*': 'jquery/*.js',
      'zodream/*': 'zodream/*.js',
      'angular2/*': 'angular2/*.min.js',
      '*': '*.js'
    },
    map:{
        Vue: "vue/vue"
    }
});
System.import('jquery/Jquery.NavBar');
$('.ms-NavBar').NavBar();
</script>
  <?php }
));
?>
    </body>
</html>
