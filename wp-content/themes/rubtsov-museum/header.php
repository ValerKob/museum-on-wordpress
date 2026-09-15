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
        <?php
            $museum_header_logo = get_option(
                'museum_header_logo',
                ''
            );

            $museum_header_logo_position = get_option(
                'museum_header_logo_position',
                'left'
            );
            ?>

            <?php if ($museum_header_logo) : ?>

                <div class="museum-header-logo museum-header-logo-<?php echo esc_attr($museum_header_logo_position); ?>">

                    <a href="<?php echo esc_url(home_url('/')); ?>">

                        <img
                            src="<?php echo esc_url($museum_header_logo); ?>"
                            alt="<?php echo esc_attr(
                                get_option(
                                    'museum_header_title',
                                    'НИКОЛАЯ РУБЦОВА'
                                )
                            ); ?>"
                        >

                    </a>

                </div>

            <?php endif; ?>
        <a
            href="<?php echo esc_url(home_url('/')); ?>"
            class="site-logo"
        >
            <?php
            echo esc_html(
                get_option(
                    'museum_header_label',
                    'ВИРТУАЛЬНЫЙ МУЗЕЙ'
                )
            );
            ?>
        </a>

        <a
            href="<?php echo esc_url(home_url('/')); ?>"
            class="site-title"
        >
            <?php
            echo esc_html(
                get_option(
                    'museum_header_title',
                    'НИКОЛАЯ РУБЦОВА'
                )
            );
            ?>
        </a>
    </div>
</header>
<?php endif; ?>