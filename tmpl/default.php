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
			$document->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css');
		}

		#______________________MOBILE________________________
		if (trim($params->get('show_on_mobile')) == 1)
		{
			?>
			<div class="jssocial_mobile_view">
				<div class="buttons_container">
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
						else if ($Android) {
							$fb_url = 'fb://page/' . $facebook_id;
						}
						else
						{
							$fb_url = 'https://facebook.com/' . $facebook_id;
						}
						?>
						<a class="facebook" href="<?php echo $fb_url ?>" target="_blank">
							<i class="fab fa-facebook-f"></i>
						</a>
						<?php
					}
					if (!empty($twitter_id))
					{
						$sum++;
						?>
						<a class="twitter" href="https://twitter.com/<?php echo $twitter_id ?>" target="_blank">
							<i class="fab fa-twitter"></i>
						</a>
						<?php
					}
					$mobile_style = '.jssocial_mobile_view a, .jssocial_mobile_view a:focus, .jssocial_mobile_view a:hover { width: calc(100% / ' . $sum . ');}';
					$document->addStyleDeclaration($mobile_style);
				?>
				</div>
			</div>
			<?php
		}

		#______________________DESKTOP________________________
		?>
		<div class="jssocial_desktop_view" style="top: <?php echo $params->get('margintop') ?> !important;">
		<?php
			if (!empty($facebook_id))
			{
				?>
				<input id="facebookTab" type="radio" name="tabs" checked />
				<label for="facebookTab" class="facebook_icon" style="max-width: 32px;">
					<span>facebook</span>
					<i class="fab fa-facebook-f"></i>
				</label>
				<section id="facebookContent">
					<div class="facebook_box">
						<iframe
							src="https://www.facebook.com/plugins/page.php?href=https://www.facebook.com/<?php echo $params->get('facebook_login'); ?>&tabs=timeline&width=350&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId"
							width="350" height="500" style="border:none;overflow:hidden" scrolling="no" frameborder="0"
							allowfullscreen="true"
							allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
						</iframe>
					</div>
				</section>
				<?php
			}
			if (!empty($twitter_id))
			{
				?>
				<input id="twitterTab" type="radio" name="tabs" />
				<label for="twitterTab" class="twitter_icon" style="max-width: 32px;">
					<span>twitter</span>
					<i class="fab fa-twitter"></i>
				</label>
				<section id="twitterContent">
					<div class="twitter_box">
						<a class="twitter-timeline" data-width="350" data-height="500"
							href="https://twitter.com/<?php echo $twitter_id ?>">Tweets by <?php echo $twitter_id ?></a>
						<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
					</div>
				</section>
				<?php
			}
			if (trim($params->get('position')) == 1)
			{
				if (trim($params->get('buttons_shape')) == 1)
				{
					$buttons_shape = '.jssocial_desktop_view .facebook_icon, .jssocial_desktop_view .twitter_icon {border-radius: 0 7px 7px 0 !important;}';
					$document->addStyleDeclaration($buttons_shape);
				}
				$position_left = '.jssocial_desktop_view {left:-370px;}.jssocial_desktop_view:hover{transform: translateX(370px);}.jssocial_desktop_view .facebook_icon{float:right;right:-31px; clear: right;}.jssocial_desktop_view .twitter_icon{float:right; clear: right;right:-31px}';
				$document->addStyleDeclaration($position_left);
			}
			else
			{
				if (trim($params->get('position')) == 0)
				{
					if (trim($params->get('buttons_shape')) == 1)
					{
						$buttons_shape = '.jssocial_desktop_view .facebook_icon, .jssocial_desktop_view .twitter_icon {border-radius: 7px 0 0 7px !important;}';
						$document->addStyleDeclaration($buttons_shape);
					}
					$position_right = '.jssocial_desktop_view {right:-370px;}.jssocial_desktop_view:hover{transform: translateX(-370px);} .jssocial_desktop_view .facebook_icon{float:left;left:-31px; clear: left;}.jssocial_desktop_view .twitter_icon{float:left;left:-31px; clear: left;}';
					$document->addStyleDeclaration($position_right);
				}
			}
			?>
			<div class="copyrightlink">Designed with
				<span style="color: #f44336;">❤</span> by
				<a title="Joomla Extensions" target="_blank" href="https://jsns.eu" rel="noopener">jsns.eu</a>
			</div>
		</div>
		<?php
	}
}
?>
