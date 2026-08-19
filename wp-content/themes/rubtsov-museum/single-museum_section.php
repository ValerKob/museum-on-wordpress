<?php
get_header();
?>

<main class="museum-single">

    <section class="museum-single-header">

        <div class="museum-single-header-content">

            <div class="museum-breadcrumb">

                <a href="<?php echo esc_url(home_url('/')); ?>">
                    ← Главная
                </a>

            </div>

            <?php
                $museum_section_label = get_option(
                    'museum_section_label',
                    'РАЗДЕЛ ВИРТУАЛЬНОГО МУЗЕЯ'
                );
                ?>

                <?php if (!empty($museum_section_label)) : ?>

                    <p class="museum-single-label">
                        <?php echo esc_html($museum_section_label); ?>
                    </p>

            <?php endif; ?>

            <h1>
                <?php the_title(); ?>
            </h1>

        </div>

    </section>


    <section class="museum-single-content <?php
        echo get_post_meta(
            get_the_ID(),
            '_museum_section_full_width',
            true
        ) === '1'
            ? 'museum-section-full-width'
            : '';
    ?>">

        <div class="museum-content">

            <?php
            while (have_posts()) :
                the_post();
            ?>

                <?php the_content(); ?>
                <?php

                    $museum_section_id = get_the_ID();

                    $subsections = get_posts(array(
                        'post_type'      => 'museum_subsection',
                        'post_status'    => 'publish',
                        'posts_per_page' => -1,
                        'meta_query'     => array(
                            'relation' => 'AND',

                            array(
                                'key'     => '_museum_section_id',
                                'value'   => $museum_section_id,
                                'compare' => '=',
                                'type'    => 'NUMERIC',
                            ),

                            array(
                                'relation' => 'OR',

                                array(
                                    'key'     => '_museum_subsection_parent_id',
                                    'compare' => 'NOT EXISTS',
                                ),

                                array(
                                    'key'     => '_museum_subsection_parent_id',
                                    'value'   => '0',
                                    'compare' => '=',
                                ),
                            ),
                        ),
                        'orderby' => 'menu_order',
                        'order'   => 'ASC',
                    ));

                    ?>

                    <?php if (!empty($subsections)) : ?>

                        <nav class="museum-subsections">

                            <?php foreach ($subsections as $subsection) : ?>

                                <a
                                    href="<?php echo esc_url(
                                        get_permalink($subsection->ID)
                                    ); ?>"
                                    class="museum-subsection-link <?php
                                        echo get_post_meta(
                                            $subsection->ID,
                                            '_museum_subsection_full_width',
                                            true
                                        ) === '1'
                                            ? 'museum-subsection-link-full'
                                            : '';
                                    ?>"
                                >
                                    <?php echo esc_html(
                                        $subsection->post_title
                                    ); ?>
                                </a>

                            <?php endforeach; ?>

                        </nav>

                    <?php endif; ?>

            <?php
            endwhile;
            ?>

        </div>

    </section>

</main>

<?php
get_footer();
?>