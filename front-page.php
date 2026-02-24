<?php //front-page.php 

// Checks if there are more posts to show
// Moves to the next post and sets it as the current post
?>

<?php get_header(); ?>

<?php get_template_part('components/aside'); ?>

<article>
    <?php the_content(); ?>
</article>

<?php get_footer(); ?>