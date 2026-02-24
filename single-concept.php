<?php //single-concept.php ?>

<?php get_header(); ?>

<aside>
    <?php
    // front-page는 have_posts()가 page 정보를 가리킨다
    // 때문에, custom query 사용한다
    $latest = new WP_Query([
        'post_type' => 'concept',
        'posts_per_page' => -1,
    ]);
    ?>
    <?php if ($latest->have_posts()) : ?>
        <?php while ($latest->have_posts()) : $latest->the_post(); ?>
            <li>
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            </li>
        <?php endwhile; ?>
    <?php endif; ?>
    <?php wp_reset_postdata(); // 사용이 끝난 custom query는 초기화 하자 ?>
</aside>

<article>
    <h1 class="post-title"><?php the_title(); ?></h1>
    <?php the_content(); ?>
</article>

<?php get_footer(); ?>