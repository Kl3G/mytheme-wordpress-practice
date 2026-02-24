<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <header>
        <h1 class="site-title">
            <a href="/"><?php bloginfo('name'); ?></a>
        </h1>
        <nav>
            <?php $terms = get_terms([
                'taxonomy' => 'layer',
                'hide_empty' => false,
                'orderby' => 'slug',
                'order' => 'ASC'
            ]); ?>
            <!-- get_terms() = term 목록 -->
            <!-- get_term_link() = 특정 term의 URL -->
            <?php foreach ($terms as $term) : ?>
                <a href="<?php echo get_term_link($term); ?>">
                    <?php echo $term->name ?>
                </a>
            <?php endforeach; ?>
            <a href="<?php echo get_permalink(11); ?>">
                <?php echo get_the_title(11); ?>
            </a>
        </nav>
    </header>

    <main>