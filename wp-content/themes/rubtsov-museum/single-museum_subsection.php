<?php
get_header();
?>

<main class="museum-single">

    <section class="museum-single-header">

        <div class="museum-single-header-content">

            <?php
                $museum_subsection_label = get_option(
                    'museum_subsection_label',
                    'ПОДРАЗДЕЛ ВИРТУАЛЬНОГО МУЗЕЯ'
                );
                ?>

                <?php if (!empty($museum_subsection_label)) : ?>

                    <p class="museum-single-label">
                        <?php echo esc_html($museum_subsection_label); ?>
                    </p>

            <?php endif; ?>

            <h1>
                <?php the_title(); ?>
            </h1>

        </div>

    </section>
    <?php

        $current_subsection_id = get_the_ID();

        $parent_subsection_id = get_post_meta(
            $current_subsection_id,
            '_museum_subsection_parent_id',
            true
        );

        if ($parent_subsection_id) {

            $back_url = get_permalink($parent_subsection_id);
            $back_title = get_the_title($parent_subsection_id);

        } else {

            $parent_section_id = get_post_meta(
                $current_subsection_id,
                '_museum_section_id',
                true
            );

            if ($parent_section_id) {

                $back_url = get_permalink($parent_section_id);
                $back_title = get_the_title($parent_section_id);

            } else {

                $back_url = home_url('/');
                $back_title = 'Главная';

            }

        }

        ?>

        <div class="museum-breadcrumb">

            <a href="<?php echo esc_url($back_url); ?>">
                ← <?php echo esc_html($back_title); ?>
            </a>

        </div>

    <section class="museum-single-content">

        <div class="museum-content">

            <?php
            while (have_posts()) :
                the_post();

                the_content();
            endwhile;
            ?>

        </div>

    </section>

</main>

<?php
get_footer();
?>