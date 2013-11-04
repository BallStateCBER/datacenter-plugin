<?php 
// Only invoke Google Analytics if an ID is found and the page is not being served from the development server 
$google_analytics_id = Configure::read('google_analytics_id');
$not_localhost = isset($_SERVER['SERVER_NAME']) && stripos($_SERVER['SERVER_NAME'], 'localhost') === false;
if ($google_analytics_id && $not_localhost): ?>	
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		
		ga('create', '<?php echo $google_analytics_id; ?>', 'cberdata.org');
		ga('send', 'pageview');
	</script>
<?php endif; ?>