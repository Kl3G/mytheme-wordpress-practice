<?php //archive-concept.php ?>

<?php get_header(); ?>

<h1>this is archive-concept.php</h1>

<?php if (have_posts()) : // Checks if there are more posts to show ?>
    <?php while (have_posts()) : the_post(); // Moves to the next post and sets it as the current post ?>
        <article>
            <a href="<?php the_permalink(); ?>">
                <h2><?php the_title(); ?> (post title)</h2>
            </a>
        </article>
    <?php endwhile; ?>
<?php else : ?>
    <p>No posts.</p>
<?php endif; ?>

<?php get_footer(); ?>