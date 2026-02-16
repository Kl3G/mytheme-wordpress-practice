<?php //home.php ?>

<?php get_header(); ?>

<h1>This is Home</h1>

<?php if (have_posts()) : // Checks if there are more posts to show ?>
    <?php while (have_posts()) : the_post(); // Moves to the next post and sets it as the current post ?>
        <div class="card-grid">
            <a class="card" href="<?php the_permalink(); ?>">
                <h2 class="card__title"><?php the_title(); ?></h2>
            </a>
        </div>
    <?php endwhile; ?>
<?php else : ?>
    <p>No posts.</p>
<?php endif; ?>

<?php get_footer(); ?>