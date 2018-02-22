<?php get_template_part('templates/page', 'header'); ?>

<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    <?php _e('Sorry, no results were found.', 'sage'); ?>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>

<?php
$args = array( 'numberposts' => 1 );
$lastposts = get_posts( $args );

foreach($lastposts as $post) : setup_postdata($post); ?>
<div id="press-hero" class="container-fluid">
    <div class="press-bg"></div>
</div>	
<div id="press" class="container">

    <div class="row row-01">
        <div class="col-md-6">
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <?php the_content(); ?>
            <a class="press-ft-link" href="#"><button class="orange-btn">More Information</button></a>
        </div>
        <div class="col-md-6 img-col"></div>
    </div>
</div>

<?php endforeach; ?>


<div id="press-blue-bg" class="container-fluid">
    <div class="blue-border"></div>
    <div class="container">
        <div class="row row-02">

<?php

$args = array( 
    'numberposts' => 10,
    'offset' => 0,
    'post__not_in' => array( $post )
);
$lastposts = get_posts( $args );

foreach($lastposts as $post) : setup_postdata($post); ?>
    <div class="col-md-4"><?php the_content(); ?></div>
<?php endforeach; ?>

        </div>
    </div>
</div>

<?php the_posts_navigation(); ?>
