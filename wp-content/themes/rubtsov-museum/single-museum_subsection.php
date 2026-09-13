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

            <?php if (get_the_ID() === 17) : ?>

                <div class="rubtsov-quiz" id="rubtsov-quiz">

                    <div class="rubtsov-quiz-intro">

                        <h2>Квиз «По следам Н. Рубцова»</h2>

                        <p>
                            Проверьте свои знания о жизни, творчестве
                            и судьбе Николая Рубцова.
                        </p>

                        <button type="button" id="rubtsov-quiz-start">
                            Начать квиз
                        </button>

                    </div>

                    <div
                        class="rubtsov-quiz-game"
                        id="rubtsov-quiz-game"
                        style="display:none;"
                    >

                        <?php

                        $quiz_questions = new WP_Query(array(
                            'post_type'      => 'quiz_question',
                            'post_status'    => 'publish',
                            'posts_per_page' => -1,
                            'orderby'        => 'date',
                            'order'          => 'ASC',
                        ));

                        ?>

                        <?php if ($quiz_questions->have_posts()) : ?>

                            <div class="rubtsov-quiz-questions">

                                <?php
                                $question_number = 1;
                                ?>

                                <?php while ($quiz_questions->have_posts()) : ?>

                                    <?php
                                    $quiz_questions->the_post();

                                    $question_id = get_the_ID();

                                    $question_text = get_post_meta(
                                        $question_id,
                                        '_quiz_question',
                                        true
                                    );

                                    $answers = array();

                                    for ($i = 1; $i <= 4; $i++) {

                                        $answers[$i] = get_post_meta(
                                            $question_id,
                                            '_quiz_answer_' . $i,
                                            true
                                        );

                                    }
                                    ?>

                                    <div
                                        class="rubtsov-quiz-question"
                                        data-question="<?php echo esc_attr($question_number); ?>"
                                    >

                                        <h3>
                                            <?php echo esc_html($question_number); ?>.
                                            <?php echo esc_html($question_text); ?>
                                        </h3>

                                        <div class="rubtsov-quiz-answers">

                                            <?php for ($i = 1; $i <= 4; $i++) : ?>

                                                <?php if (!empty($answers[$i])) : ?>

                                                    <label class="rubtsov-quiz-answer">

                                                        <input
                                                            type="radio"
                                                            name="quiz_question_<?php echo esc_attr($question_number); ?>"
                                                            value="<?php echo esc_attr($i); ?>"
                                                        >

                                                        <span>
                                                            <?php echo esc_html($answers[$i]); ?>
                                                        </span>

                                                    </label>

                                                <?php endif; ?>

                                            <?php endfor; ?>

                                        </div>

                                    </div>

                                    <?php
                                    $question_number++;
                                    ?>

                                <?php endwhile; ?>

                            </div>

                            <?php wp_reset_postdata(); ?>

                        <?php else : ?>

                            <p>
                                Вопросы квиза пока не добавлены.
                            </p>

                        <?php endif; ?>

                    </div>

                </div>

            <?php else : ?>

                <?php 
                    while (have_posts()) : 
                        the_post(); 

                        if (get_post_type() !== 'museum_event') { 
                            the_content(); 
                        } 

                    endwhile;
                ?>

            <?php endif; ?>

                <?php if (get_the_title() === 'Афиши мероприятий') : ?>

                <?php
                    $events = new WP_Query(array(
                        'post_type'      => 'museum_event',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'orderby'        => 'meta_value',
                        'meta_key'       => '_museum_event_date',
                        'order'          => 'ASC',
                    ));

                    if ($events->have_posts()) :
                    ?>
                <?php endif; ?>


    <div class="museum-events">

        <?php while ($events->have_posts()) : $events->the_post(); ?>

            <?php
                $event_date = get_post_meta(
                    get_the_ID(),
                    '_museum_event_date',
                    true
                );

                $event_content = get_post_field(
                    'post_content',
                    get_the_ID()
                );

                $event_description = wp_strip_all_tags(
                    $event_content
                );
                ?>

                <article class="museum-event-card">
                    <?php
                        $event_content = get_post_field(
                            'post_content',
                            get_the_ID()
                        );

                        $event_image = '';

                        if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $event_content, $matches)) {
                            $event_image = $matches[1];
                        }
                        ?>

                        <?php if ($event_image) : ?>

                            <div class="museum-event-image">
                                <img
                                    src="<?php echo esc_url($event_image); ?>"
                                    alt="<?php echo esc_attr(get_the_title()); ?>"
                                >
                            </div>

                        <?php endif; ?>

                    <?php
                        $event_image = get_the_post_thumbnail_url(
                            get_the_ID(),
                            'large'
                        );
                        ?>

                        <?php if (has_post_thumbnail()) : ?>

                            <div class="museum-event-image">
                                <?php the_post_thumbnail('large'); ?>
                            </div>

                        <?php endif; ?>

                    <div class="museum-event-content">

                        <h2 class="museum-event-title">
                            <?php the_title(); ?>
                        </h2>

                        <?php if ($event_date) : ?>

                            <div class="museum-event-date">

                                <span class="museum-event-date-icon">📅</span>

                                <?php
                                echo esc_html(
                                    date_i18n(
                                        'd F Y г. в H:i',
                                        strtotime($event_date)
                                    )
                                );
                                ?>

                            </div>

                        <?php endif; ?>

                        <div class="museum-event-description">
                            <?php echo wp_kses_post($event_description); ?>
                        </div>

                        <?php
                        $event_files = get_post_meta(
                            get_the_ID(),
                            '_museum_event_files',
                            true
                        );

                        if (
                            is_array($event_files) &&
                            !empty($event_files)
                        ) :
                        ?>

                            <div class="museum-event-files">

                                <div class="museum-event-files-title">
                                    Файлы для скачивания
                                </div>

                                <div class="museum-event-files-list">

                                    <?php foreach ($event_files as $file) : ?>

                                        <?php
                                        if (empty($file['url'])) {
                                            continue;
                                        }

                                        $file_name = !empty($file['name'])
                                            ? $file['name']
                                            : basename($file['url']);
                                        ?>

                                        <a
                                            href="<?php echo esc_url($file['url']); ?>"
                                            class="museum-event-file-download"
                                            download
                                            target="_blank"
                                            rel="noopener"
                                        >
                                            ↓
                                            <?php echo esc_html($file_name); ?>
                                        </a>

                                    <?php endforeach; ?>

                                </div>

                            </div>

                        <?php endif; ?>

                        </div>

                </article>

            <?php endwhile; ?>

        </div>

    <?php
        wp_reset_postdata();
    endif;
    ?>

        </div>

    </section>

</main>

<?php
get_footer();
?>