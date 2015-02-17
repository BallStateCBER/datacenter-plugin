<?php
	$on_localhost = stripos($_SERVER['HTTP_HOST'], 'localhost') !== false;
	$domain = $on_localhost ? '' : 'http://cberdata.org';
	$plugin_path = $domain.'/data_center';
?>
<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
	<head>
		<meta charset="utf-8" />
		<link rel="dns-prefetch" href="//ajax.googleapis.com" />
		<title>
			<?php
				$title = Configure::read('data_center_subsite_title');
				if (isset($title_for_layout) && $title_for_layout) {
					$title = $title_for_layout.' - '.$title;
				}
				echo $title;
			?>
		</title>
		<meta name="title" content="<?php echo $title; ?>" />
		<meta name="description" content="" />
		<meta name="author" content="Center for Business and Economic Research, Ball State University" />
		<meta name="language" content="en" />
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="imagetoolbar" content="false" />
		<?php echo $this->fetch('meta'); ?>
		<link href='http://fonts.googleapis.com/css?family=Asap:400,400italic,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet/less" type="text/css" href="<?php echo $plugin_path; ?>/css/datacenter.less" />
		<link rel="stylesheet/less" type="text/css" href="/css/style.less" />
		<link rel="icon" type="image/png" href="<?php echo $plugin_path; ?>/img/icons/chart.png" />
		<?php if (Configure::read('debug') != 0): ?>
			<script type="text/javascript">
				less = { env: 'development' };
			</script>
		<?php endif; ?>
		<script src="<?php echo $plugin_path; ?>/js/less-1.7.0.min.js" type="text/javascript"></script>
		<?php echo $this->fetch('css'); ?>
		<script src="<?php echo $plugin_path; ?>/js/modernizr-2.5.3.min.js"></script>
		<?php echo $this->fetch('scriptTop'); ?>
	</head>
	<body>
		<?php echo $this->fetch('content'); ?>

		<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="<?php echo $plugin_path; ?>/js/jquery-1.7.1.min.js"><\/script>')</script>

		<!-- scripts concatenated and minified via build script -->
		<script src="<?php echo $plugin_path; ?>/js/datacenter.js"></script>
		<script src="/js/script.js"></script>
		<?php echo $this->fetch('script'); ?>

  		<?php if (Configure::read('google_analytics_id')): ?>
			<?php echo $this->element('DataCenter.analytics'); ?>
		<?php endif; ?>

		<?php echo $this->Js->writeBuffer(); ?>
	</body>
</html>