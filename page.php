<?php //page.php ?>

<?php get_header(); ?>

<article> 
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php the_content(); ?>
        <?php endwhile; ?>
    <?php endif; ?>
</article>
<!-- 
post가 1개만 와도 Loop는 쓰는 게 원칙이다.
왜냐면, WordPress는 기본적으로 아래 동작 흐름.
(WP_Query → 결과 배열 → Loop 순서)
the_content();만 단독으로 쓰면 나중에 변경 비용 커질 수 있다고 한다.
-->

<?php get_footer(); ?>