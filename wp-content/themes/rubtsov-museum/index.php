<?php
get_header();

$museum_home_background = get_option('museum_home_background', '');
?>

<main class="museum-home">

    <section
        class="museum-hero"
        style="background-image:
            linear-gradient(
                rgba(20, 18, 15, 0.55),
                rgba(20, 18, 15, 0.55)
            ),
            url('<?php echo esc_url(get_option('museum_home_background', '')); ?>');"
    >

        <div class="museum-hero-content">

            <p class="museum-subtitle">
                <?php
                echo esc_html(
                    get_option(
                        'museum_home_subtitle',
                        'ВИРТУАЛЬНЫЙ ШКОЛЬНЫЙ МУЗЕЙ'
                    )
                );
                ?>
            </p>

            <h1>
                <?php
                echo esc_html(
                    get_option(
                        'museum_home_title',
                        'НИКОЛАЯ РУБЦОВА'
                    )
                );
                ?>
            </h1>

            <p class="museum-description">
                <?php
                echo esc_html(
                    get_option(
                        'museum_home_description',
                        'Поэт. Человек. История.'
                    )
                );
                ?>
            </p>

        </div>

    </section>


    <section class="museum-sections">

        <?php

        $museum_sections = new WP_Query(array(
            'post_type'      => 'museum_section',
            'posts_per_page' => -1,
            'orderby'         => 'date',
            'order'           => 'ASC',
        ));

        if ($museum_sections->have_posts()) :

            $number = 1;

            while ($museum_sections->have_posts()) :

                $museum_sections->the_post();

                ?>

                <a
                    class="museum-card"
                    href="<?php the_permalink(); ?>"
                >

                    <span>
                        <?php echo sprintf('%02d', $number); ?>
                    </span>

                    <h2>
                        <?php the_title(); ?>
                    </h2>

                </a>

                <?php

                $number++;

            endwhile;

            wp_reset_postdata();

        endif;

        ?>

    </section>

</main>

<?php
get_footer();
?>