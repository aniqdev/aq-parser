<?php 
require('header.php'); ?>
<div class="content-wrapper">
	<main class="content">
	    
	        <?php
                if (in_category(26)) {
                    $vd_itemtype = "<div itemscope itemtype='http://schema.org/Recipe'>";
                } else {
                    $vd_itemtype = "<div itemscope itemtype='http://schema.org/Article'>";    
                }
                echo $vd_itemtype;
            ?>
	        <meta itemprop="author" content="Шеф Повар">
	    
		<?php
		if (have_posts()) { 
			while (have_posts()) { the_post(); ?>
				<?php if ($show_date) { ?>
	    			<time itemprop="datePublished" class="post-info__time post-info__time_single" datetime="<?php the_time('Y-m-d') ?>"><?php the_time('d.m.Y') ?></time>
	    		<?php } else{ ?>
				    <time itemprop="datePublished" datetime="<?php the_time('Y-m-d') ?>"></time>
				<?php }?>
				<?php
				  $recipeYield = CFS()->get( 'vd_recipe_ing_recipeYield' );
				  $prep_time = artabr_convert_minute_hour( CFS()->get('vd_recipe_ing_prepTime') ); 
                  $cook_time = artabr_convert_minute_hour( CFS()->get('vd_recipe_ing_cookTime') ); 
				  $СookingMethod = CFS()->get( 'vd_recipe_ing_cookingMethod' );
				  $RecipeCuisine = CFS()->get( 'vd_recipe_ing_recipeCuisine' );
				  $vd_deskrip  = CFS()->get( 'vd_deskrip' );
				  $fields_art = CFS()->get('vd_recipe_ing');
				  $ing_subtitle = CFS()->get('vd_recipe_ing_subtitle');
				  $ing_subtitle_dop = CFS()->get('vd_recipe_ing_subtitle_dop');
				  $fields_art_dop = CFS()->get('vd_recipe_ing_dop');
				  $recipe_step = CFS()->get('vd_autor_stepbystep');
				  $post_id = get_the_ID();
				  
				 ?>
                
                 <article class="single">
				    <?php
				//	$title_img = get_post_meta( $post->ID, 'title_img', true ); 
				    $title_img = kama_thumb_src('w=660&h=300');
					if($title_img) { 
						?>
						<div class="title-img">
						<!--	<?php echo kama_thumb_img("src=".$title_img."&w=660&h=300&class=h1_img&alt=". get_the_title() .""); ?>-->
						<?php echo '<img itemprop="resultPhoto" src="'. kama_thumb_src('w=660&h=300') .'" class=h1_img alt="'. get_the_title() .'"" />';?>
						  	<h1 itemprop="name" class="single__title"><?php the_title(); ?></h1>
						</div>
						<?php 
					} else {
						?>
						<h1 itemprop="name" class="single__title"><?php the_title(); ?></h1>
					    <?php
					}	?>
					
					<!--место под заголовок-->
                       

				    <?php if ( $prep_time ) { ?>
                    	<div class="prep-time">
                    		<?php echo 'Время подготовки: '; ?>
                    		<span class="the-time"><strong><span class="dashicons dashicons-clock"></span> <?php echo $prep_time; ?></strong></span>
                    	</div>
                    <?php } ?>
                    
                    <?php if ( $cook_time ) { ?>
                    	<div class="prep-time">
                    		<?php echo 'Время приготовления: '; ?>
                    		<span class="the-time"><strong><span class="dashicons dashicons-clock"></span> <?php echo $cook_time; ?></strong></span>
                    	</div>
                     <?php } ?>
                     <meta itemprop="prepTime" content="<?php echo schema_prep_time(); ?>">
                     <meta itemprop="cookTime" content="<?php echo schema_cook_time(); ?>">
                     <meta itemprop="totalTime" content="<?php echo schema_total_time(); ?>">				 
				     <?php if ( $recipeYield ) { ?>
				       <div class="divclear"></div>
				       <div class="prep-time">
				       <?php echo "Порций: "?> <strong itemprop='recipeYield'><?php echo $recipeYield; ?></strong>
				       </div>
				   <?php }?>
				   	 
				   <?php if ( $RecipeCuisine ) { ?>
				       <div class="prep-time">
				   	    <?php echo "Кухня: "?><strong itemprop='recipeCuisine'><?php echo $RecipeCuisine;?></strong>
				   	    </div>
				   <?php }?>
				   
				   <?php if ( $СookingMethod ) {?> 
				       <div class="prep-time">
				   	  <?php  echo "Метод: "?> <strong itemprop='cookingMethod'><?php echo $СookingMethod;?></strong>
				   	  </div>
				   	<?php }?>
				   	 
				   	 <div class="divclear"></div>
				   	 
				   	 <div class="tagcloud"><?php the_tags('', ''); ?></div>
				   	  <div class="divclear"></div>
				   	 
				   	 <?php
				   	 if ($vd_deskrip){
				   	     echo '<div itemprop="description">'.$vd_deskrip.'</div>';
				   	 }?>
				   	 <?php
				   	 if ($fields_art) {
				       	echo '<h2>Ингредиенты</h2>';
				   	 }
				   	 if ($ing_subtitle) {
				       	echo '<h3>'.$ing_subtitle.'</h3>';
				   	 } ?>
				   	    <div class="recipe_ing">
				       	<div class="recipe_ing_opis">
				   	   <?php echo '<ul>';
				       	foreach ( $fields_art as $field_art ) {
				   	        echo '<li itemprop="recipeIngredient">'.$field_art['vd_recipe_ing_name'].' '.$field_art['vd_recipe_ing_count'].'</li>';
                        }
                        echo '</ul>';?>
                       </div> 
                       <div class="recipe_ing_ads">
                                                       
                            
                            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- eingebaut -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-4009372254971170"
     data-ad-slot="5777263411"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>

                        </div>
                        </div>
                        <div class="divclear"></div>
                        
                     <?php if ($ing_subtitle_dop) {
				       	echo '<h3>'.$ing_subtitle_dop.'</h3>';
				   	    echo '<ul>';
				       	foreach ( $fields_art_dop as $field_art_dop ) {
				   	        echo '<li itemprop="recipeIngredient">'.$field_art_dop['vd_recipe_ing_name_dop'].' '.$field_art_dop['vd_recipe_ing_count_dop'].'</li>';
                        }
                        echo '</ul>';
                      }  
                      ?>
                    <!--Вывод блока популярные рецепты-->
                    <?php
                                $args = array(
            			'posts_per_page' => 15,
            			'meta_key' => 'post_views_count',
            			'orderby' => 'meta_value_num',
            			'meta_query' => 
            			array(
            			    array(
            			        'key'     => 'slider',
            			        'compare' => 'NOT EXISTS'
            			    )
            			),
            		);
                    
                    $loop = new WP_Query($args); 
		if ($loop->have_posts()) { 
			?>
			<!--<div class="title">Популярные рецепты</div>-->

				<div class="slider-posts-wrap">
					<ul id="slider-posts" class="slider-posts">
						<?php 
						while ($loop->have_posts()) { $loop->the_post(); 
							?>
							<li>
								<div class="slider-posts__img">
								    <?php
								    $w = 210; $h = 131;
								    if (kama_thumb_src()){
								        echo '<img src="'.kama_thumb_src('w='.$w.'&h='.$h).'" width="'.$w.'" height="'.$h.'" alt="'.get_the_title().'" />';    
								    }else{
								        echo '<img src="'.get_stylesheet_directory_uri().'/images/210-131.jpg" width="'.$w.'" height="'.$h.'" alt="Изображение для публикации не задано">';
								    } ?>
						    	    <div class="post-info post-info_slider-posts">
						    	    	<?php
						    	    	$post_cat = get_the_category(); 
						    	    	$post_cat = $post_cat[0]->cat_ID;
						    	    	?>
						    	    	<div class="post-info__cat">
						    	    		<a href="<?php echo get_category_link($post_cat) ?>"><?php echo get_cat_name($post_cat); ?></a>
						    	    	</div>
						    	    	<?php if ($show_date) { ?>
						        			<time class="post-info__time" datetime="<?php the_time('Y-m-d') ?>"><?php the_time('d.m.Y') ?></time>
						        		<?php } ?>
						    	    </div>
					    	    </div>
							    <div class="slider-posts__title">
		    	   					<a href="<?php the_permalink() ?>"><?php the_title() ?></a>
	    	   					</div>
							</li>
							<?php
						} ?>
					</ul>
				</div>

			<?php 
		} wp_reset_query(); ?>	
    

                    <!--Вывод инструкции-->
                 <?php if ($recipe_step) {
                        echo '<h2>Пошаговый рецепт</h2>';?>
                        <?php $i_ads=0;//Переменная счетчик шагов рецепта?> 
                     	<?php echo '<ol itemprop="recipeInstructions">';
                	    foreach ($recipe_step as $step) { ?>  
                	    <div class="recipe_step">

                	       <?php if ($step['vd_autor_photo_step']) {?>
                	       <li>
                	           <div class="recipe_step_photo"><?php  echo '<a href="'.$step['vd_autor_photo_step'].'"><img itemprop="image" src="'. $step['vd_autor_photo_step'].'" width="250"></a>';?></div>
                	           <?php echo $step['vd_autor_text_step'];?>
                	       </li>
                	       <?php }else{?>
                	       <li><?php echo $step['vd_autor_text_step'];?></li>
                	       <?php }?>
                	       
                	       
                	      <div class="divclear"></div>
                	      <?php $i_ads++;?>

                	    </div>
                	    <!--Вывод рекламы между 3 и 4 шагом
                	       <?php if ($i_ads==3) {?>
                            <div class="recipe_step"> 
                             
                                                          </div> 
                           <?php }?>-->
                	<!--КОНЕЦ Вывода рекламы между 3 и 4 шагом-->      
                	    <?php }
                        echo '</ol>'; 
                    }?>          
     
                    <?php
                    the_content(""); 
				/*	edit_post_link('Редактировать', '<p>', '</p>'); */?>
				
                    <!--место под контентом -->
                    
				</article>
				<?php $category_post = get_the_category($post_id);
				      $category_post_id = $category_post[0]->term_id; ?>
	 
				<?php if (function_exists( 'perelink_after_content') ) {
				    ob_start();
				    perelink_after_content();
				    $perelink_content = ob_get_contents();
				    ob_end_clean();
				    
				    if ($perelink_content) { ?>
				    <div class="title"><span>Рекомендуемые рецепты:</span></div>
				    <div class="yarpp-related">
				        <!--<ul class="related">-->
				        	<?php 
				        	if ( class_exists('PerelinkPlugin') ) {
		                        PerelinkPlugin::getAfterText();
		                    } ?>
				        <!--</ul>-->
				    </div>
				    <?php 
					} else { 
				    	if ( function_exists('related_posts') ) {
					        ob_start();
					        related_posts(); 
					        $yarpp_content = ob_get_contents();
					        ob_end_clean();

					        $pos = strpos($yarpp_content, 'yarpp-related-none');
					        
					        if (!$pos) echo $yarpp_content;
						}	
					}
				} else {
				    if ( function_exists( 'related_posts') ) {
				    	ob_start();
				        related_posts(); 
				        $yarpp_content = ob_get_contents();
				        ob_end_clean();

				        $pos = strpos($yarpp_content, 'yarpp-related-none');

				        if (!$pos) echo $yarpp_content;
				    }
				} ?>
				
								<div class="post-meta">
					<?php if (function_exists('the_ratings')) { 
						?>
						<div class="post-rating">
							<div class="post-rating__title">Оцените статью:</div>
							<br>
							<?php the_ratings(); ?>
						</div>
						<?php 
						
					} ?>
					<br>
					<div class="post-share">
						<div class="post-share__title">Поделитесь рецептом с друзьями:</div><br>
						<script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8" async="async"></script><div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,gplus,twitter" data-counter=""></div>
					</div>
				</div>
				<!--Реклама Низ-->
				<ul class="breadcrumbs breadcrumbs_single">
					<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a class="home" href="<?php bloginfo('url'); ?>" itemprop="url"><span itemprop="title"><?php echo $bread_crumbs_home; ?></span></a></li>
					<?php if($category_post[0]->category_parent){ ?>
					<li itemprop="recipeCategory" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="<?php echo get_category_link($category_post[0]->category_parent); ?>" itemprop="url"><span itemprop="title"><?php echo get_cat_name($category_post[0]->category_parent) ?></span></a></li>
					<?php } ?>
					<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="<?php echo get_category_link($category_post[0]->term_id ); ?>" itemprop="url"><span itemprop="title"><?php echo $category_post[0]->cat_name; ?></span></a></li>
				</ul>
				<br>
				<?php
			} 
			if ( comments_open() ) { 
				?>
				<aside class="comments-block">
					<?php comments_template(); ?>
				</aside>
				<?php 
			} 
		} else { 
			?>
			<div class="single">
				<h2>Не найдено</h2>
				<p>Извините, по вашему запросу ничего не найдено.</p>
			</div>
			<?php 
		} ?>
	</main>	
	<?php 
	require('sidebar.php'); ?>
</div><!-- /.content-wrapper -->
<?php require('footer.php'); ?>
