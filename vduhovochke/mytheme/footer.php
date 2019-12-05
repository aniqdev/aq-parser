</div><!-- /.main-box -->
<footer class="footer">
	<?php if ($logo_footer_upload) { ?>
		<img src="<?php echo $logo_footer_upload; ?>" class="footer-logo" alt="<?php bloginfo('name'); ?>">
	<?php } ?>
	<?php
	$nav = wp_nav_menu(
	array(
	    'theme_location' =>'nav_footer', 
	    'container' => false,
	    'items_wrap' => '<nav class="footer-nav"><ul>%3$s</ul></nav>',  
	    'fallback_cb' => false,
	    'depth' => 1,
	    'echo' => false,
	)); 
	if ($nav) { 
		echo $nav; 
	} ?>
	<div class="footer-bottom">
		<div class="copy">© <?php echo date("Y") ?> Все права защищены</div>
		<?php 
		if ($social_ok || $social_yt || $social_fb || $social_gp || $social_tw || $social_in || $social_vk) {
			?>
			<div class="social-icon">
				<?php
				if ($social_ok) echo "<a href='". $social_ok ."' target='_blank' class='ok'>ok</a>";
				if ($social_yt) echo "<a href='". $social_yt ."' target='_blank' class='yt'>yt</a>";
				if ($social_fb) echo "<a href='". $social_fb ."' target='_blank' class='fb'>fb</a>";
				if ($social_gp) echo "<a href='". $social_gp ."' target='_blank' class='gp'>gp</a>";
				if ($social_tw) echo "<a href='". $social_tw ."' target='_blank' class='tw'>tw</a>";
				if ($social_in) echo "<a href='". $social_in ."' target='_blank' class='in'>in</a>";
				if ($social_vk) echo "<a href='". $social_vk ."' target='_blank' class='vk'>vk</a>";
				?>
			</div>
			<?php
		} ?>
	</div>
</footer>
</div><!-- /.wrapper -->
<?php wp_footer(); ?>
</div><!-- /#main -->


<!-- uSocial -->
<script async src="https://usocial.pro/usocial/usocial.js?v=6.1.4" data-script="usocial" charset="utf-8"></script>
<div class="uSocial-Share" data-pid="9d4b9b509e76811c5dd157080a8b8d1e" data-type="share" data-options="round-rect,style1,default,left,slide-down,size48,eachCounter1,eachCounter-bottom,counter0,upArrow-right" data-social="vk,ok,fb,mail,bookmarks" data-mobile="vi,wa,sms"></div>
<!-- /uSocial -->

</body>
</html>