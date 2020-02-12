<?php

/**
 * @package    JS Like Box Slider
 * @license    GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link       https://jsns.eu
 */

defined('_JEXEC') or die('Restricted access');

modSlideLikebox::getLikebox($params);

class modSlideLikebox
{
	public static function getLikebox($params)
	{
		global $mainframe;
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root() . 'modules/mod_facebook_slide_likebox/tmpl/css/style.min' . '.css', 'text/css', null, array());

		#______________________MOBILE________________________

		if (trim($params->get('show_on_mobile')) == 1) 
		{

			$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
			$iPad    = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
			$Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");

			if ($iPhone || $iPad) 
			{
				$fb_url = 'fb://profile/' . $params->get('profile_id');
			} else if ($Android) {
				$fb_url = 'fb://page/' . $params->get('profile_id');
			}

			if (trim($params->get('facebook')) == 1) {
				$fb = 1;
			} else {
				$fb = 0;
			}

			if (trim($params->get('twitter')) == 1) {
				$tw = 1;
			} else {
				$tw = 0;
			}

			$sum = $fb + $tw;
			$mobile_style = '#social_mobile a {position: relative;float: left; width: calc(100% / ' . $sum . ');display:list-item; list-style-type: none;} #social_mobile a:focus, #social_mobile a:hover { width: calc(100% / ' . $sum . ');-moz-transition-property: none; -webkit-transition-property: none; -o-transition-property: none;transition-property: none;}';
			$document->addStyleDeclaration($mobile_style); 

			?>
			<div id="social_mobile">
				<div class="top-left">
				<?php
					if (trim($params->get('facebook')) == 1) 
					{ 	
						?>
						<a class="facebook pop-upper" href="<?php echo $fb_url ?>" target="_blank">
							<i class="fa fa-facebook-f"></i>
						</a>
						<?php 
					}
					if (trim($params->get('twitter')) == 1) 
					{ 
						?>
						<a class="twitter pop-upper" href="https://twitter.com/<?php echo $params->get('twitter_login'); ?>" target="_blank">
							<i class="fa fa-twitter"></i>
						</a>
						<?php 
					} 
				?>
				</div>
			</div>
			<?php 
		}

		#______________________DESKTOP________________________

		if (trim($params->get('position')) == 1) 
		{
			if (trim($params->get('buttons_shape')) == 1) 
			{
				$buttons_shape = '.social_slider .facebook_icon, .social_slider .twitter_icon {border-radius: 0 7px 7px 0 !important;}';
				$document->addStyleDeclaration($buttons_shape);
			}
			$position_left = '.social_slider {position: fixed; left:-370px; z-index:99997; transition: all .5s .2s;}.social_slider:hover{transition: all .5s .2s; transform: translateX(370px);}.social_slider .facebook_icon{float:right;background-color: #3289d9;right:-31px; clear: right;}.social_slider .twitter_icon{float:right; clear: right;background-color: #27a4d9;right:-31px}';
			$document->addStyleDeclaration($position_left);
		} else if (trim($params->get('position')) == 0) 
		{
			if (trim($params->get('buttons_shape')) == 1) 
			{
				$buttons_shape = '.social_slider .facebook_icon, .social_slider .twitter_icon {border-radius: 7px 0 0 7px !important;}';
				$document->addStyleDeclaration($buttons_shape);
			}
			$position_right = '.social_slider {position: fixed; right:-370px; z-index:99997; transition: all .5s .2s;}.social_slider:hover{transition: all .5s .2s; transform: translateX(-370px);} .social_slider .facebook_icon{float:left;left:-31px; background-color: #3289d9; clear: left;}.social_slider .twitter_icon{float:left;left:-31px; background-color: #27a4d9; clear: left;}';
			$document->addStyleDeclaration($position_right);
		} 

		?>
		<div class="social_slider" style="top: <?php echo $params->get('margintop') ?> !important;">
		<?php
			if (trim($params->get('facebook')) == 1) 
			{ 
				?>
				<input id="tab1" type="radio" name="tabs" checked />
				<label for="tab1" class="facebook_icon" style="max-width: 32px;"><span>facebook</span><i class="fa fa-facebook-f"></i></label>
				<section id="content1">
					<div class="facebook_box">
<<<<<<< HEAD
						<iframe src="https://www.facebook.com/plugins/page.php?href=https://www.facebook.com/<?php echo $params->get('profile_id'); ?>&tabs=timeline,events,messages&width=350&height=470&small_header=false&adapt_container_width=false&hide_cover=false&show_facepile=true" width="350" height="470" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true">
=======
						<iframe src="https://www.facebook.com/plugins/page.php?href=https://www.facebook.com/<?php echo $params->get('profile_id'); ?>&tabs=timeline&width=350&height=470&small_header=false&adapt_container_width=false&hide_cover=false&show_facepile=true" width="350" height="470" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true">
>>>>>>> 9a88f58efb67c4c7e14c75d1d6006c60075be7ea
						</iframe>
					</div>
				</section>
				<?php 
			}

			if (trim($params->get('twitter')) == 1) 
			{ 
				?>
				<input id="tab2" type="radio" name="tabs" />
				<label for="tab2" class="twitter_icon" style="max-width: 32px;"><span>twitter</span><i class="fa fa-twitter"></i></label>
				<section id="content2">
					<div class="twitter_box">
						<a class="twitter-timeline" data-width="350" data-height="470" href="https://twitter.com/<?php echo $params->get('twitter_login'); ?>">Tweets by <?php echo $params->get('twitter_login'); ?></a>
						<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
					</div>
				</section>
				<?php 
			} 
			?>
			<div class="copyrightlink">Designed with 
				<span style="color: #f44336;">❤</span> by 
				<a title="Joomla Extensions" target="_blank" href="https://jsns.eu" rel="noopener noreferrer">jsns.eu</a>
			</div>
		</div>
		<?php
	}
}
?>