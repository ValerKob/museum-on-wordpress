<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php if (get_option('museum_header_enabled', 1)) : ?>

<header>
    <div class="site-header">
        <div class="site-logo">
            <?php
            echo esc_html(
                get_option(
                    'museum_header_label',
                    'ВИРТУАЛЬНЫЙ МУЗЕЙ'
                )
            );
            ?>
        </div>

        <div class="site-title">
            <?php
            echo esc_html(
                get_option(
                    'museum_header_title',
                    'НИКОЛАЯ РУБЦОВА'
                )
            );
            ?>
        </div>
    </div>
</header>
<?php endif; ?>