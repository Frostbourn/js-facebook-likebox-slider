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
		
		$facebook_id = $params->get('facebook_login');
		$twitter_id =  $params->get('twitter_login');
		$font_awesome_cdn = $params->get('fa_cdn');

		$document->addStyleSheet(JURI::root() . 'modules/mod_facebook_slide_likebox/tmpl/css/style.min' . '.css', 'text/css', null, array());
		if ($font_awesome_cdn == 1) {
			$document->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
		}
		
		#______________________MOBILE________________________
		if (trim($params->get('show_on_mobile')) == 1) 
		{
			?>
			<div class="social_mobile">
				<div class="top-left">
				<?php
					$sum = 0;
					if (!empty($facebook_id))
					{
						$sum++;
						$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
						$iPad    = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
						$Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
			
						if ($iPhone || $iPad) 
						{
							$fb_url = 'fb://profile/' . $facebook_id;
						} 
						else 
						{
							if ($Android) 
							{
								$fb_url = 'fb://page/' . $facebook_id;
							}
						}
							?>
							<a class="facebook" href="<?php echo $fb_url ?>" target="_blank">
								<i class="fa fa-facebook-f"></i>
							</a>
							<?php 
					}
					if (!empty($twitter_id))
					{
						$sum++;
						?>
						<a class="twitter" href="https://twitter.com/<?php echo $twitter_id ?>" target="_blank">
							<i class="fa fa-twitter"></i>
						</a>
						<?php 
					}
						$mobile_style = '.social_mobile a, .social_mobile a:focus, .social_mobile a:hover { width: calc(100% / ' . $sum . ');}';
						$document->addStyleDeclaration($mobile_style); 
				?>
				</div>
			</div>
			<?php 
		}

		#______________________DESKTOP________________________
		?>
		<div class="social_slider" style="top: <?php echo $params->get('margintop') ?> !important;">
		<?php
			if (!empty($facebook_id))
			{
				?>
				<input id="tab1" type="radio" name="tabs" checked />
				<label for="tab1" class="facebook_icon" style="max-width: 32px;"><span>facebook</span><i class="fa fa-facebook-f"></i></label>
				<section id="content1">
					<div class="facebook_box">
						<iframe src="https://www.facebook.com/plugins/page.php?href=https://www.facebook.com/<?php echo $params->get('facebook_login'); ?>&tabs=timeline,events,messages&width=350&height=470&small_header=false&adapt_container_width=false&hide_cover=false&show_facepile=true" width="350" height="470" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true">
						</iframe>
					</div>
				</section>
				<?php 
			}
			if (!empty($twitter_id))
			{
				?>
				<input id="tab2" type="radio" name="tabs" />
				<label for="tab2" class="twitter_icon" style="max-width: 32px;"><span>twitter</span><i class="fa fa-twitter"></i></label>
				<section id="content2">
					<div class="twitter_box">
						<a class="twitter-timeline" data-width="350" data-height="470" href="https://twitter.com/<?php echo $twitter_id ?>">Tweets by <?php echo $twitter_id ?></a>
						<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
					</div>
				</section>
				<?php
			}
			if (trim($params->get('position')) == 1) 
			{
				if (trim($params->get('buttons_shape')) == 1) 
				{
					$buttons_shape = '.social_slider .facebook_icon, .social_slider .twitter_icon {border-radius: 0 7px 7px 0 !important;}';
					$document->addStyleDeclaration($buttons_shape);
				}
				$position_left = '.social_slider {left:-370px;}.social_slider:hover{transform: translateX(370px);}.social_slider .facebook_icon{float:right;right:-31px; clear: right;}.social_slider .twitter_icon{float:right; clear: right;right:-31px}';
				$document->addStyleDeclaration($position_left);
			} 
			else 
			{
				if (trim($params->get('position')) == 0) 
				{
					if (trim($params->get('buttons_shape')) == 1) 
					{
						$buttons_shape = '.social_slider .facebook_icon, .social_slider .twitter_icon {border-radius: 7px 0 0 7px !important;}';
						$document->addStyleDeclaration($buttons_shape);
					}
					$position_right = '.social_slider {right:-370px;}.social_slider:hover{transform: translateX(-370px);} .social_slider .facebook_icon{float:left;left:-31px; clear: left;}.social_slider .twitter_icon{float:left;left:-31px; clear: left;}';
					$document->addStyleDeclaration($position_right);
				}
			} 
			?>
			<div class="copyrightlink">Designed with 
				<span style="color: #f44336;">❤</span> by 
				<a title="Joomla Extensions" target="_blank" href="https://jsns.eu" rel="noopener noreferrer">jsns.eu</a>
				</div>
			</div>
		</div>
		<?php		
	}
}
?>