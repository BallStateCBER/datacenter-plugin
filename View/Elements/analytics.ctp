<?php 
// Only invoke Google Analytics if an ID is found and the page is not being served from the development server 
$google_analytics_id = Configure::read('google_analytics_id');
$not_localhost = isset($_SERVER['SERVER_NAME']) && stripos($_SERVER['SERVER_NAME'], 'localhost') === false;
if ($google_analytics_id && $not_localhost): ?>
	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', '<?php echo $google_analytics_id; ?>']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();  
	</script>
<?php endif; ?>