<?php //taxonomy-layer.php ?>

<?php get_header(); ?>

<?php get_template_part('components/aside'); ?>

<section>
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('components/card'); ?>
        <?php endwhile; ?>
    <?php endif; ?>
</section>

<?php get_footer(); ?>