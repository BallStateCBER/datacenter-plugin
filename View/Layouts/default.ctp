<!DOCTYPE html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
	<head>
		<?php echo $this->Html->charset(); ?>
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
		<?php /*
			Optional tags for <head>

			Facebook Open Graph Data
			<meta property="og:title" content="" />
			<meta property="og:description" content="" />
			<meta property="og:image" content="" />

			<link rel="author" href="humans.txt" />

			More useful tag suggestions at http://html5boilerplate.com/docs/head-Tips/
			Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons
		*/ ?>
		<link rel="shortcut icon" href="/data_center/img/favicon.ico" />
		<link href='http://fonts.googleapis.com/css?family=Asap:400,400italic,700' rel='stylesheet' type='text/css'>
		<?php
			if (Configure::read('debug') == 0) {
				echo $this->Html->css('DataCenter.datacenter');
				echo $this->Html->css('style');
			} else {

				//Trying to keep things relative; and not domain name dependent:
				//but right now css helper doesn't really like less; that's why the next two lines use the assetUrl.
				//echo $this->Html->css('DataCenter.datacenter.less?','stylesheet/less');
				//echo $this->Html->css('DataCenter.style.less','stylesheet/less');


				echo '<link rel="stylesheet/less" type="text/css" href="'.$this->Html->assetUrl('DataCenter.datacenter.less', array('pathPrefix' => CSS_URL)).'" />';
				echo '<link rel="stylesheet/less" type="text/css" href="'.$this->Html->assetUrl('style.less', array('pathPrefix' => CSS_URL)).'" />';
				echo '<script type="text/javascript">less = { env: \'development\' };</script>';
				echo $this->Html->script('DataCenter.less-1.7.0.min');
			}
			echo $this->fetch('css');
			echo $this->fetch('scriptTop');
		?>

		<!--[if lt IE 9]>
			<script src="/data_center/js/html5shiv-printshiv.js"></script>
		<![endif]-->
	</head>
	<body>
		<?php /*
			Prompt IE 6 users to install Chrome Frame. Remove this if you support IE 6.
			chromium.org/developers/how-tos/chrome-frame-getting-started
		*/ ?>
		<!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->

		<div id="above_footer">
			<header id="header_top">
				<div class="max_width">
					<h1>
						<a href="http://bsu.edu/cber">
							Center for Business and Economic Research
						</a>
						-
						<a href="http://bsu.edu">
							Ball State University
						</a>
					</h1>
					<?php /*
					<div id="search">
						<form>
							<input type="text" name="search" value="Search CBERData.org" />
							<input type="submit" value="Go" />
						</form>
					</div>

					<div id="login_out">
						<a href="#">Login / Logout</a>
					</div>
					*/ ?>
					<br class="clear" />
					<a href="http://cberdata.org/" id="data_center_nameplate">
						CBER Data Center
					</a>

					<nav>
						<?php
							$tabs = array();
							$tabs[] = array(
								'Projects and<br />Publications',
								'http://projects.cberdata.org'
							);
							$tabs[] = array(
								'Economic<br />Indicators',
								'http://indicators.cberdata.org'
							);
							$tabs[] = array(
								'Weekly<br />Commentary',
								'http://commentaries.cberdata.org'
							);
							$tabs[] = array(
								'Illinois-to-Indiana<br />Tax Savings Calculator',
								'http://tax-comparison.cberdata.org'
							);
							$tabs[] = array(
								'County<br />Profiles',
								'http://profiles.cberdata.org'
							);
							$tabs[] = array(
								'Community<br />Asset Inventory',
								'http://asset.cberdata.org'
							);
							$tabs[] = array(
								'Brownfield Grant<br />Writers\' Tool',
								'http://brownfield.cberdata.org'
							);
							$tabs[] = array(
								'Conexus Indiana<br />Report Card',
								'http://conexus.cberdata.org'
							);
							$this_subsite_url = Configure::read('data_center_subsite_url');
							foreach ($tabs as $tab) {
								echo "<a href=\"$tab[1]\"";
								if ($tab[1] == $this_subsite_url) {
									echo ' class="selected"';
								}
								echo ">$tab[0]</a>";
							}
						?>

					</nav>
					<br class="clear" />
				</div>
			</header>

			<?php if ($this->fetch('subsite_title')): ?>
				<?php echo $this->fetch('subsite_title'); ?>
			<?php else: ?>
				<h1 id="subsite_title" class="max_width_padded">
					<?php echo Configure::read('data_center_subsite_title'); ?>
				</h1>
			<?php endif; ?>

			<div id="content_wrapper" class="max_width">
				<?php if ($this->fetch('sidebar')): ?>
					<div id="two_col_wrapper">
						<?php /*
							These two col_stretcher divs ensure that both the sidebar and content
							area have the appearance of having the same height.
						*/ ?>
						<div id="menu_col_stretcher" class="col_stretcher"></div>
						<div id="content_col_stretcher" class="col_stretcher"></div>
						<div id="menu_column" class="col">
							<?php echo $this->fetch('sidebar'); ?>
						</div>
						<div id="content_column" class="col">
							<?php echo $this->fetch('content'); ?>
							<br class="clear" />
						</div>
					</div>
				<?php else: ?>
					<?php echo $this->fetch('content'); ?>
					<br class="clear" />
				<?php endif; ?>
			</div>
		</div>
		<footer id="footer">
			<div class="max_width">
				<div id="cberlogo_copyright">
					<?php
						echo $this->Html->image('DataCenter.BallStateCBER-red.png',array('url'=>'http://www.bsu.edu/cber'));?>
					<p>
						&copy; Center for Business and Economic Research, Ball State University
					</p>
				</div>
				<section>
					<section>
						<?php if ($this->fetch('footer_about')): ?>
							<?php echo $this->fetch('footer_about'); ?>
						<?php else: ?>
							<h3>About Ball State CBER Data Center</h3>
							<p>
								Ball State CBER Data Center is one-stop shop for economic data including demographics, education, health, and social
								capital. Our easy-to-use, visual web tools offer data collection and analysis for grant writers, economic developers, policy
								makers, and the general public.
							</p>
							<p>
								Ball State CBER Data Center (<a href="http://www.cberdata.org">www.cberdata.org</a>) is a product of the Center for Business and Economic Research at Ball State
								University. CBER's mission is to conduct relevant and timely public policy research on a wide range of economic issues
								affecting the state and nation. <a href="http://www.bsu.edu/cber">Learn more</a>.
							</p>
						<?php endif; ?>
					</section>
					<section>
						<h3>Center for Business and Economic Research</h3>
						<address>
							Ball State University &bull; Whitinger Business Building, room 149<br />
							2000 W. University Ave.<br />
							Muncie, IN 47306-0360
						</address>
						<dl>
							<dt>Phone:</dt>
							<dd>765-285-5926</dd>

							<dt>Email:</dt>
							<dd><a href="mailto:cber@bsu.edu">cber@bsu.edu</a></dd>

							<dt>Website:</dt>
							<dd><a href="http://www.bsu.edu/cber">www.bsu.edu/cber</a></dd>

							<dt>Facebook:</dt>
							<dd><a href="http://www.facebook.com/BallStateCBER">www.facebook.com/BallStateCBER</a></dd>

							<dt>Twitter:</dt>
							<dd><a href="http://www.twitter.com/BallStateCBER">www.twitter.com/BallStateCBER</a></dd>
						</dl>
					</section>
				</section>
			</div>
		</footer>

		<noscript>
			<div id="noscript" style="background-color: #FFCBAF; border: 1px solid #7F0000; border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px; box-shadow: 2px 2px 2px rgba(0, 0, 0, .3); -moz-box-shadow: 2px 2px 2px rgba(0, 0, 0, .3); -webkit-box-shadow: 2px 2px 2px rgba(0, 0, 0, .3); color: #7F0000; font-weight: bold; left: 15px; padding: 20px; position: absolute; right: 15px; top: 15px; z-index: 10;">
				JavaScript is currently disabled in your browser.
				For full functionality of this website, JavaScript must be enabled.
				If you need assistance, <a href="http://www.enable-javascript.com/" target="_blank">Enable-JavaScript.com</a> provides instructions.
			</div>
		</noscript>

		<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="/data_center/js/jquery-1.9.1.min.js"><\/script>')</script>

		<?php
			echo $this->Html->script('http://cberdata.org/data_center/js/datacenter.js');
			echo $this->fetch('script');
			echo $this->Js->writeBuffer();
			echo $this->element('DataCenter.analytics');
		?>

		<script type="text/javascript">
			var $buoop = {vs:{i:9,f:20,o:11,s:5,n:9}}
			$buoop.ol = window.onload;
			window.onload=function(){
				try {if ($buoop.ol) $buoop.ol();}catch (e) {}
				var e = document.createElement("script");
				e.setAttribute("type", "text/javascript");
				e.setAttribute("src", "http://browser-update.org/update.js");
				document.body.appendChild(e);
			}
		</script>
	</body>
</html>