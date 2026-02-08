<?php //page.php ?>

<?php get_header(); ?>

<h1>This is page.php (Static Page)</h1>

<?php if (have_posts()) : // Checks if there are more posts to show ?>
    <?php while (have_posts()) : the_post(); // Moves to the next post and sets it as the current post ?>
        <div>
            <h2><?php the_title(); ?> (page name)</h2>
            <?php the_content(); ?>
        </div>
    <?php endwhile; ?>
<?php else : ?>
    <p>No posts.</p>
<?php endif; ?>

<?php get_footer(); ?>