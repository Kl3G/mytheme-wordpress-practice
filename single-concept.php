<?php //single-concept.php ?>

<?php get_header(); ?>

<?php get_template_part('components/aside'); ?>

<article>
    <h1 class="post-title"><?php the_title(); ?></h1>
    <?php the_content(); ?>
</article>

<?php get_footer(); ?>