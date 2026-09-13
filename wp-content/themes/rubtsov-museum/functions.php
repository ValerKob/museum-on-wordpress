<?php

/*
 * Подключение стилей
 */
function rubtsov_museum_styles() {

    wp_enqueue_style(
        'rubtsov-museum-style',
        get_stylesheet_uri(),
        array(),
        '1.0'
    );

}

add_action('wp_enqueue_scripts', 'rubtsov_museum_styles');


/*
 * Подключение медиатеки WordPress
 */
function rubtsov_museum_admin_scripts($hook) {

    /*
     * Страница настроек музея
     */
    if (
        $hook === 'toplevel_page_rubtsov-museum-settings'
    ) {

        wp_enqueue_media();

        wp_enqueue_script(
            'rubtsov-museum-admin',
            get_template_directory_uri() . '/assets/js/admin.js',
            array('jquery'),
            '1.0',
            true
        );

        return;
    }


    /*
     * Редактирование вопроса квиза
     */
    if (
        $hook === 'post.php' ||
        $hook === 'post-new.php'
    ) {

        $screen = get_current_screen();

        if (
            $screen &&
            $screen->post_type === 'quiz_question'
        ) {

            wp_enqueue_media();

            wp_enqueue_script(
                'rubtsov-quiz-admin',
                get_template_directory_uri() . '/assets/js/quiz-admin.js',
                array('jquery'),
                '1.0',
                true
            );

        }

    }

}

add_action(
    'admin_enqueue_scripts',
    'rubtsov_museum_admin_scripts'
);

/*
 * Разделы музея
 */
function rubtsov_register_museum_sections() {

    register_post_type('museum_section', array(

        'labels' => array(
            'name'               => 'Разделы музея',
            'singular_name'      => 'Раздел музея',
            'add_new'            => 'Добавить раздел',
            'add_new_item'       => 'Добавить новый раздел',
            'edit_item'          => 'Редактировать раздел',
            'new_item'           => 'Новый раздел',
            'view_item'          => 'Посмотреть раздел',
            'search_items'       => 'Найти раздел',
            'not_found'          => 'Разделы не найдены',
            'menu_name'          => 'Разделы музея',
        ),

        'public' => true,

        'menu_icon' => 'dashicons-screenoptions',

        'supports' => array(
            'title',
            'editor',
            'thumbnail',
        ),

        'has_archive' => false,

        'rewrite' => array(
            'slug' => 'museum',
        ),

        'show_in_rest' => true,

    ));

}

add_action(
    'init',
    'rubtsov_register_museum_sections'
);

/*
 * Поле "Раздел на всю ширину"
 */
function rubtsov_section_full_width_metabox() {

    add_meta_box(
        'rubtsov_section_full_width',
        'Настройки раздела',
        'rubtsov_section_full_width_metabox_html',
        'museum_section',
        'side',
        'default'
    );

}

add_action(
    'add_meta_boxes',
    'rubtsov_section_full_width_metabox'
);


/*
 * Содержимое поля
 */
function rubtsov_section_full_width_metabox_html($post) {

    wp_nonce_field(
        'rubtsov_save_section_full_width',
        'rubtsov_section_full_width_nonce'
    );

    $full_width = get_post_meta(
        $post->ID,
        '_museum_section_full_width',
        true
    );

    ?>

    <label>
        <input
            type="checkbox"
            name="museum_section_full_width"
            value="1"
            <?php checked($full_width, '1'); ?>
        >

        Раздел на всю ширину
    </label>

    <p class="description">
        Если включено, этот раздел будет занимать всю ширину блока.
    </p>

    <?php
}


/*
 * Сохранение настройки
 */
function rubtsov_save_section_full_width($post_id) {

    if (
        !isset($_POST['rubtsov_section_full_width_nonce'])
    ) {
        return;
    }

    if (
        !wp_verify_nonce(
            $_POST['rubtsov_section_full_width_nonce'],
            'rubtsov_save_section_full_width'
        )
    ) {
        return;
    }

    if (
        defined('DOING_AUTOSAVE') &&
        DOING_AUTOSAVE
    ) {
        return;
    }

    if (
        get_post_type($post_id) !== 'museum_section'
    ) {
        return;
    }

    if (
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    if (isset($_POST['museum_section_full_width'])) {

        update_post_meta(
            $post_id,
            '_museum_section_full_width',
            '1'
        );

    } else {

        delete_post_meta(
            $post_id,
            '_museum_section_full_width'
        );

    }

}

add_action(
    'save_post_museum_section',
    'rubtsov_save_section_full_width'
);

/*
 * Подразделы музея
 */
function rubtsov_register_museum_subsections() {

    register_post_type('museum_subsection', array(

        'labels' => array(
            'name'          => 'Подразделы музея',
            'singular_name' => 'Подраздел музея',
            'add_new'       => 'Добавить подраздел',
            'add_new_item'  => 'Добавить новый подраздел',
            'edit_item'     => 'Редактировать подраздел',
            'new_item'      => 'Новый подраздел',
            'view_item'     => 'Посмотреть подраздел',
            'search_items'  => 'Найти подраздел',
            'not_found'     => 'Подразделы не найдены',
            'menu_name'     => 'Подразделы музея',
        ),

        'public' => true,

        'menu_icon' => 'dashicons-index-card',

        'supports' => array(
            'title',
            'editor',
            'thumbnail',
            'page-attributes',
        ),

        'has_archive' => false,

        'rewrite' => array(
            'slug' => 'museum-subsection',
        ),

        'show_in_rest' => true,

    ));

}

add_action(
    'init',
    'rubtsov_register_museum_subsections'
);

/*
 * Мероприятия музея
 */
function rubtsov_register_museum_events() {

    register_post_type('museum_event', array(

        'labels' => array(
            'name'               => 'Мероприятия',
            'singular_name'      => 'Мероприятие',
            'add_new'            => 'Добавить мероприятие',
            'add_new_item'       => 'Добавить новое мероприятие',
            'edit_item'          => 'Редактировать мероприятие',
            'new_item'           => 'Новое мероприятие',
            'view_item'          => 'Посмотреть мероприятие',
            'search_items'       => 'Найти мероприятие',
            'not_found'          => 'Мероприятия не найдены',
            'menu_name'          => 'Мероприятия',
        ),

        'public' => true,

        'menu_icon' => 'dashicons-calendar-alt',

        'supports' => array(
            'title',
            'editor',
            'thumbnail',
        ),

        'has_archive' => false,

        'rewrite' => array(
            'slug' => 'museum-events',
        ),

        'show_in_rest' => true,

    ));

}

add_action(
    'init',
    'rubtsov_register_museum_events'
);

/*
 * Страница настроек музея
 */
function rubtsov_museum_settings_page() {

    add_menu_page(
        'Настройки музея',
        'Настройки музея',
        'manage_options',
        'rubtsov-museum-settings',
        'rubtsov_museum_settings_html',
        'dashicons-admin-customizer',
        25
    );

}

add_action(
    'admin_menu',
    'rubtsov_museum_settings_page'
);


/*
 * Содержимое страницы настроек
 */
function rubtsov_museum_settings_html() {
    ?>

    <div class="wrap">

        <h1>Настройки музея</h1>

        <form method="post">

            <?php
            wp_nonce_field(
                'rubtsov_museum_save_settings',
                'rubtsov_museum_settings_nonce'
            );
            ?>

            <h2>Header</h2>
            <p>
                <label>
                    <input
                        type="checkbox"
                        name="museum_header_enabled"
                        value="1"
                        <?php checked(
                            get_option('museum_header_enabled', 1),
                            1
                        ); ?>
                    >
                    Показывать шапку сайта
                </label>
            </p>

            <table class="form-table">

                <tr>
                    <th scope="row">
                        <label for="museum_header_title">
                            Название музея
                        </label>
                    </th>

                    <td>
                        <input
                            type="text"
                            id="museum_header_title"
                            name="museum_header_title"
                            value="<?php echo esc_attr(
                                get_option(
                                    'museum_header_title',
                                    'НИКОЛАЯ РУБЦОВА'
                                )
                            ); ?>"
                            class="regular-text"
                        >

                        <p class="description">
                            Это название будет отображаться в верхней части сайта.
                        </p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="museum_header_label">
                            Верхняя надпись
                        </label>
                    </th>

                    <td>
                        <input
                            type="text"
                            id="museum_header_label"
                            name="museum_header_label"
                            value="<?php echo esc_attr(
                                get_option(
                                    'museum_header_label',
                                    'ВИРТУАЛЬНЫЙ МУЗЕЙ'
                                )
                            ); ?>"
                            class="regular-text"
                        >

                        <p class="description">
                            Надпись слева в верхней части сайта.
                        </p>
                    </td>
                </tr>
                        </table>

            <hr>

                <h2>Раздел музея</h2>

                    <table class="form-table">

                        <tr>
                            <th scope="row">
                                <label for="museum_section_label">
                                    Надпись над названием раздела
                                </label>
                            </th>

                            <td>

                                <input
                                    type="text"
                                    id="museum_section_label"
                                    name="museum_section_label"
                                    value="<?php echo esc_attr(
                                        get_option(
                                            'museum_section_label',
                                            'РАЗДЕЛ ВИРТУАЛЬНОГО МУЗЕЯ'
                                        )
                                    ); ?>"
                                    class="regular-text"
                                >

                                <p class="description">
                                    Надпись над названием раздела. Оставьте поле пустым, если она не нужна.
                                </p>

                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="museum_subsection_label">
                                    Надпись над названием подраздела
                                </label>
                            </th>

                            <td>

                                <input
                                    type="text"
                                    id="museum_subsection_label"
                                    name="museum_subsection_label"
                                    value="<?php echo esc_attr(
                                        get_option(
                                            'museum_subsection_label',
                                            'ПОДРАЗДЕЛ ВИРТУАЛЬНОГО МУЗЕЯ'
                                        )
                                    ); ?>"
                                    class="regular-text"
                                >

                                <p class="description">
                                    Надпись над названием подраздела. Оставьте поле пустым, если она не нужна.
                                </p>

                            </td>
                        </tr>
                    </table>

                    <hr>

                

                <h2>Главная страница</h2>

                <table class="form-table">

                    <tr>
                        <th scope="row">
                            <label for="museum_home_subtitle">
                                Верхняя надпись
                            </label>
                        </th>

                        <td>
                            <input
                                type="text"
                                id="museum_home_subtitle"
                                name="museum_home_subtitle"
                                value="<?php echo esc_attr(
                                    get_option(
                                        'museum_home_subtitle',
                                        'ВИРТУАЛЬНЫЙ ШКОЛЬНЫЙ МУЗЕЙ'
                                    )
                                ); ?>"
                                class="regular-text"
                            >

                            <p class="description">
                                Надпись над главным названием музея.
                            </p>
                            <p>
                                <label for="museum_home_subtitle_size">
                                    Размер шрифта:
                                </label>

                                <input
                                    type="number"
                                    id="museum_home_subtitle_size"
                                    name="museum_home_subtitle_size"
                                    value="<?php echo esc_attr(
                                        get_option(
                                            'museum_home_subtitle_size',
                                            '14'
                                        )
                                    ); ?>"
                                    min="8"
                                    max="100"
                                    step="1"
                                    style="width:100px;"
                                >

                                px
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="museum_home_title">
                                Главное название
                            </label>
                        </th>

                        <td>
                            <input
                                type="text"
                                id="museum_home_title"
                                name="museum_home_title"
                                value="<?php echo esc_attr(
                                    get_option(
                                        'museum_home_title',
                                        'НИКОЛАЯ РУБЦОВА'
                                    )
                                ); ?>"
                                class="regular-text"
                            >

                            <p class="description">
                                Главное название на первом экране.
                            </p>
                            <p>
                                <label for="museum_home_title_size">
                                    Размер шрифта:
                                </label>

                                <input
                                    type="number"
                                    id="museum_home_title_size"
                                    name="museum_home_title_size"
                                    value="<?php echo esc_attr(
                                        get_option(
                                            'museum_home_title_size',
                                            '90'
                                        )
                                    ); ?>"
                                    min="20"
                                    max="150"
                                    step="1"
                                    style="width:100px;"
                                >

                                px
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="museum_home_description">
                                Описание
                            </label>
                        </th>

                        <td>
                            <input
                                type="text"
                                id="museum_home_description"
                                name="museum_home_description"
                                value="<?php echo esc_attr(
                                    get_option(
                                        'museum_home_description',
                                        'Поэт. Человек. История.'
                                    )
                                ); ?>"
                                class="regular-text"
                            >

                            <p class="description">
                                Текст под главным названием.
                            </p>
                            <p>
                                <label for="museum_home_description_size">
                                    Размер шрифта:
                                </label>

                                <input
                                    type="number"
                                    id="museum_home_description_size"
                                    name="museum_home_description_size"
                                    value="<?php echo esc_attr(
                                        get_option(
                                            'museum_home_description_size',
                                            '22'
                                        )
                                    ); ?>"
                                    min="10"
                                    max="60"
                                    step="1"
                                    style="width:100px;"
                                >

                                px
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="museum_home_background">
                                Фон главного блока
                            </label>
                        </th>

                        <td>

                            <?php
                            $museum_home_background = get_option(
                                'museum_home_background',
                                ''
                            );
                            ?>

                            <input
                                type="text"
                                id="museum_home_background"
                                name="museum_home_background"
                                value="<?php echo esc_attr(
                                    $museum_home_background
                                ); ?>"
                                class="regular-text"
                            >

                            <button
                                type="button"
                                class="button"
                                id="museum_home_background_button"
                            >
                                Выбрать изображение
                            </button>

                            <p class="description">
                                Выберите фотографию из медиатеки WordPress.
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="museum_site_background">
                                Общий фон сайта
                            </label>
                        </th>

                        <td>

                            <?php
                            $museum_site_background = get_option(
                                'museum_site_background',
                                ''
                            );
                            ?>

                            <input
                                type="text"
                                id="museum_site_background"
                                name="museum_site_background"
                                value="<?php echo esc_attr(
                                    $museum_site_background
                                ); ?>"
                                class="regular-text"
                            >

                            <button
                                type="button"
                                class="button"
                                id="museum_site_background_button"
                            >
                                Выбрать изображение
                            </button>

                            <p class="description">
                                Общий фон всего сайта. Изображение будет неподвижным,
                                а содержимое страниц будет прокручиваться поверх него.
                            </p>

                        </td>
                    </tr>

                </table>

            <h2>Footer</h2>
            <p>
                <label>
                    <input
                        type="checkbox"
                        name="museum_footer_enabled"
                        value="1"
                        <?php checked(
                            get_option('museum_footer_enabled', 1),
                            1
                        ); ?>
                    >
                    Показывать подвал сайта
                </label>
            </p>

            <table class="form-table">

                <tr>
                    <th scope="row">
                        <label for="museum_footer_title">
                            Название в Footer
                        </label>
                    </th>

                    <td>
                        <input
                            type="text"
                            id="museum_footer_title"
                            name="museum_footer_title"
                            value="<?php echo esc_attr(
                                get_option(
                                    'museum_footer_title',
                                    'Виртуальный музей Николая Рубцова'
                                )
                            ); ?>"
                            class="regular-text"
                        >

                        <p class="description">
                            Основная надпись в нижней части сайта.
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="museum_footer_subtitle">
                            Дополнительная надпись в Footer
                        </label>
                    </th>

                    <td>
                        <input
                            type="text"
                            id="museum_footer_subtitle"
                            name="museum_footer_subtitle"
                            value="<?php echo esc_attr(
                                get_option(
                                    'museum_footer_subtitle',
                                    'МБОУ СШ №4 · Архангельск'
                                )
                            ); ?>"
                            class="regular-text"
                        >

                        <p class="description">
                            Вторая строка в нижней части сайта.
                        </p>
                    </td>
                </tr>

            </table>

            <p class="submit">

                <button
                    type="submit"
                    name="rubtsov_museum_save"
                    class="button button-primary"
                >
                    Сохранить настройки
                </button>

            </p>

        </form>

    </div>

    <?php
}

function rubtsov_museum_save_settings() {

    if (
        !isset($_POST['rubtsov_museum_save']) ||
        !isset($_POST['rubtsov_museum_settings_nonce'])
    ) {
        return;
    }

    if (
        !wp_verify_nonce(
            $_POST['rubtsov_museum_settings_nonce'],
            'rubtsov_museum_save_settings'
        )
    ) {
        return;
    }

    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['museum_header_title'])) {

        update_option(
            'museum_header_title',
            sanitize_text_field(
                $_POST['museum_header_title']
            )
        );

    }
    if (isset($_POST['museum_header_label'])) {

        update_option(
            'museum_header_label',
            sanitize_text_field(
                $_POST['museum_header_label']
            )
        );

    }
    if (isset($_POST['museum_footer_title'])) {

        update_option(
            'museum_footer_title',
            sanitize_text_field(
                $_POST['museum_footer_title']
            )
        );

    }

    if (isset($_POST['museum_footer_subtitle'])) {

        update_option(
            'museum_footer_subtitle',
            sanitize_text_field(
                $_POST['museum_footer_subtitle']
            )
        );

    }

    if (isset($_POST['museum_section_label'])) {

        update_option(
            'museum_section_label',
            sanitize_text_field(
                $_POST['museum_section_label']
            )
        );

    }

    if (isset($_POST['museum_subsection_label'])) {

        update_option(
            'museum_subsection_label',
            sanitize_text_field(
                $_POST['museum_subsection_label']
            )
        );

    }

    if (isset($_POST['museum_home_subtitle'])) {

        update_option(
            'museum_home_subtitle',
            sanitize_text_field(
                $_POST['museum_home_subtitle']
            )
        );

    }

    if (isset($_POST['museum_home_subtitle_size'])) {
        update_option(
            'museum_home_subtitle_size',
            absint($_POST['museum_home_subtitle_size'])
        );
    }

    if (isset($_POST['museum_home_title'])) {

        update_option(
            'museum_home_title',
            sanitize_text_field(
                $_POST['museum_home_title']
            )
        );

    }

    if (isset($_POST['museum_home_title_size'])) {
        update_option(
            'museum_home_title_size',
            absint($_POST['museum_home_title_size'])
        );
    }

    if (isset($_POST['museum_home_description'])) {

        update_option(
            'museum_home_description',
            sanitize_text_field(
                $_POST['museum_home_description']
            )
        );

    }
    
    if (isset($_POST['museum_home_description_size'])) {
        update_option(
            'museum_home_description_size',
            absint($_POST['museum_home_description_size'])
        );
    }

    if (isset($_POST['museum_home_background'])) {

        update_option(
            'museum_home_background',
            esc_url_raw(
                $_POST['museum_home_background']
            )
        );
        

    }

    if (isset($_POST['museum_site_background'])) {

        update_option(
            'museum_site_background',
            esc_url_raw(
                $_POST['museum_site_background']
            )
        );

    }
    
    if (isset($_POST['museum_header_enabled'])) {

        update_option(
            'museum_header_enabled',
            1
        );

    } else {

        update_option(
            'museum_header_enabled',
            0
        );

    }


    if (isset($_POST['museum_footer_enabled'])) {

        update_option(
            'museum_footer_enabled',
            1
        );

    } else {

        update_option(
            'museum_footer_enabled',
            0
        );

    }

}

add_action(
    'admin_init',
    'rubtsov_museum_save_settings'
);

/*
 * Поле выбора раздела для подраздела
 */
function rubtsov_subsection_parent_metabox() {

    add_meta_box(
        'rubtsov_subsection_parent',
        'Раздел музея',
        'rubtsov_subsection_parent_metabox_html',
        'museum_subsection',
        'side',
        'high'
    );

}

add_action(
    'add_meta_boxes',
    'rubtsov_subsection_parent_metabox'
);


/*
 * Содержимое поля выбора раздела
 */
function rubtsov_subsection_parent_metabox_html($post) {

    wp_nonce_field(
        'rubtsov_save_subsection_parent',
        'rubtsov_subsection_parent_nonce'
    );

    $selected_section = get_post_meta(
        $post->ID,
        '_museum_section_id',
        true
    );

    $sections = get_posts(array(
        'post_type'      => 'museum_section',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ));

    ?>

    <select
        name="museum_section_id"
        style="width: 100%;"
    >

        <option value="">
            — Выберите раздел —
        </option>

        <?php foreach ($sections as $section) : ?>

            <option
                value="<?php echo esc_attr($section->ID); ?>"
                <?php selected(
                    $selected_section,
                    $section->ID
                ); ?>
            >

                <?php echo esc_html($section->post_title); ?>

            </option>

        <?php endforeach; ?>

    </select>

    <p class="description">
        Выберите раздел музея, к которому относится этот подраздел.
    </p>

    <?php
}


/*
 * Сохранение выбранного раздела
 */
function rubtsov_save_subsection_parent($post_id) {

    if (
        !isset($_POST['rubtsov_subsection_parent_nonce'])
    ) {
        return;
    }

    if (
        !wp_verify_nonce(
            $_POST['rubtsov_subsection_parent_nonce'],
            'rubtsov_save_subsection_parent'
        )
    ) {
        return;
    }

    if (
        defined('DOING_AUTOSAVE') &&
        DOING_AUTOSAVE
    ) {
        return;
    }

    if (
        get_post_type($post_id) !== 'museum_subsection'
    ) {
        return;
    }

    if (
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    if (
        isset($_POST['museum_section_id'])
    ) {

        $section_id = absint(
            $_POST['museum_section_id']
        );

        if ($section_id > 0) {

            update_post_meta(
                $post_id,
                '_museum_section_id',
                $section_id
            );

        } else {

            delete_post_meta(
                $post_id,
                '_museum_section_id'
            );

        }

    }

}

add_action(
    'save_post_museum_subsection',
    'rubtsov_save_subsection_parent'
);

/*
 * Настройка ширины подраздела
 */
function rubtsov_subsection_full_width_metabox() {

    add_meta_box(
        'rubtsov_subsection_full_width',
        'Настройки подраздела',
        'rubtsov_subsection_full_width_metabox_html',
        'museum_subsection',
        'side',
        'default'
    );

}

add_action(
    'add_meta_boxes',
    'rubtsov_subsection_full_width_metabox'
);


/*
 * Содержимое поля
 */
function rubtsov_subsection_full_width_metabox_html($post) {

    wp_nonce_field(
        'rubtsov_save_subsection_full_width',
        'rubtsov_subsection_full_width_nonce'
    );

    $full_width = get_post_meta(
        $post->ID,
        '_museum_subsection_full_width',
        true
    );

    ?>

    <input
        type="hidden"
        name="museum_subsection_full_width"
        value="0"
    >

    <label>

        <input
            type="checkbox"
            name="museum_subsection_full_width"
            value="1"
            <?php checked($full_width, '1'); ?>
        >

        Подраздел на всю ширину

    </label>

    <p class="description">
        Если включено, этот подраздел будет занимать всю ширину блока.
    </p>

    <?php
}


/*
 * Сохранение настройки ширины подраздела
 */
function rubtsov_save_subsection_full_width($post_id) {

    if (get_post_type($post_id) !== 'museum_subsection') {
        return;
    }

    if (
        defined('DOING_AUTOSAVE') &&
        DOING_AUTOSAVE
    ) {
        return;
    }

    if (
        wp_is_post_revision($post_id)
    ) {
        return;
    }

    if (
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    if (
        !isset($_POST['rubtsov_subsection_full_width_nonce'])
    ) {
        return;
    }

    if (
        !wp_verify_nonce(
            $_POST['rubtsov_subsection_full_width_nonce'],
            'rubtsov_save_subsection_full_width'
        )
    ) {
        return;
    }

    $full_width = isset($_POST['museum_subsection_full_width'])
        ? sanitize_text_field($_POST['museum_subsection_full_width'])
        : '0';

    if ($full_width !== '1') {
        $full_width = '0';
    }

    update_post_meta(
        $post_id,
        '_museum_subsection_full_width',
        $full_width
    );
}

add_action(
    'save_post',
    'rubtsov_save_subsection_full_width',
    99
);

/*
 * Колонки "Раздел музея" и "Родительский подраздел"
 */
function rubtsov_subsection_columns($columns) {

    $new_columns = array();

    foreach ($columns as $key => $title) {

        $new_columns[$key] = $title;

        if ($key === 'title') {

            $new_columns['museum_section'] = 'Раздел музея';

            $new_columns['museum_parent'] = 'Родительский подраздел';

        }

    }

    return $new_columns;

}

add_filter(
    'manage_museum_subsection_posts_columns',
    'rubtsov_subsection_columns'
);


/*
 * Содержимое колонок "Раздел музея"
 * и "Родительский подраздел"
 */
function rubtsov_subsection_column_content(
    $column,
    $post_id
) {

    /*
     * Раздел музея
     */
    if ($column === 'museum_section') {

        $section_id = get_post_meta(
            $post_id,
            '_museum_section_id',
            true
        );

        if (!$section_id) {

            echo '<span style="color:#999;">Не указан</span>';

            return;

        }

        $section_title = get_the_title($section_id);

        if (!$section_title) {

            echo '<span style="color:#999;">Раздел удалён</span>';

            return;

        }

        echo '<strong>' . esc_html($section_title) . '</strong>';

        return;
    }


    /*
     * Родительский подраздел
     */
    if ($column === 'museum_parent') {

        $parent_id = get_post_meta(
            $post_id,
            '_museum_subsection_parent_id',
            true
        );

        if (!$parent_id) {

            echo '<span style="color:#999;">—</span>';

            return;

        }

        $parent_title = get_the_title($parent_id);

        if (!$parent_title) {

            echo '<span style="color:#999;">Подраздел удалён</span>';

            return;

        }

        echo '<strong>' . esc_html($parent_title) . '</strong>';

        return;
    }

}

add_action(
    'manage_museum_subsection_posts_custom_column',
    'rubtsov_subsection_column_content',
    10,
    2
);

/*
 * Ширина колонок подразделов
 */
function rubtsov_subsection_column_width() {

    echo '<style>

        .column-museum_section {
            width: 240px;
        }

        .column-museum_parent {
            width: 280px;
        }

    </style>';

}

add_action(
    'admin_head-edit.php',
    'rubtsov_subsection_column_width'
);

/*
 * Фильтр подразделов по разделу музея
 */
function rubtsov_subsection_filter() {

    global $typenow;

    if ($typenow !== 'museum_subsection') {
        return;
    }

    /*
     * Выбранный раздел
     */
    $selected_section = isset($_GET['museum_section_filter'])
        ? absint($_GET['museum_section_filter'])
        : 0;

    /*
     * Выбранный родительский подраздел
     */
    $selected_parent = isset($_GET['museum_parent_filter'])
        ? absint($_GET['museum_parent_filter'])
        : 0;


    /*
     * Получаем разделы музея
     */
    $sections = get_posts(array(
        'post_type'      => 'museum_section',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ));

    ?>

    <select name="museum_section_filter">

        <option value="">
            Все разделы
        </option>

        <?php foreach ($sections as $section) : ?>

            <option
                value="<?php echo esc_attr($section->ID); ?>"
                <?php selected(
                    $selected_section,
                    $section->ID
                ); ?>
            >

                <?php echo esc_html($section->post_title); ?>

            </option>

        <?php endforeach; ?>

    </select>


    <?php

    /*
     * Получаем подразделы
     */
    $subsection_args = array(
        'post_type'      => 'museum_subsection',
        'post_status'    => array('publish', 'draft'),
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    );

    if ($selected_section) {

        $subsection_args['meta_query'] = array(
            array(
                'key'     => '_museum_section_id',
                'value'   => $selected_section,
                'compare' => '=',
                'type'    => 'NUMERIC',
            ),
        );

    }

    $subsections = get_posts($subsection_args);

    ?>

    <select name="museum_parent_filter">

        <option value="">
            Все родительские подразделы
        </option>

        <?php foreach ($subsections as $subsection) : ?>

            <option
                value="<?php echo esc_attr($subsection->ID); ?>"
                <?php selected(
                    $selected_parent,
                    $subsection->ID
                ); ?>
            >

                <?php echo esc_html($subsection->post_title); ?>

            </option>

        <?php endforeach; ?>

    </select>

    <?php
}

add_action(
    'restrict_manage_posts',
    'rubtsov_subsection_filter'
);


/*
 * Фильтрация подразделов по разделу
 * и родительскому подразделу
 */
function rubtsov_filter_subsections_by_section($query) {

    if (!is_admin()) {
        return;
    }

    if (!$query->is_main_query()) {
        return;
    }

    if ($query->get('post_type') !== 'museum_subsection') {
        return;
    }

    $meta_query = array();


    /*
     * Фильтр по разделу музея
     */
    if (
        isset($_GET['museum_section_filter']) &&
        $_GET['museum_section_filter'] !== ''
    ) {

        $section_id = absint(
            $_GET['museum_section_filter']
        );

        if ($section_id) {

            $meta_query[] = array(
                'key'     => '_museum_section_id',
                'value'   => $section_id,
                'compare' => '=',
                'type'    => 'NUMERIC',
            );

        }

    }


    /*
     * Фильтр по родительскому подразделу
     */
    if (
        isset($_GET['museum_parent_filter']) &&
        $_GET['museum_parent_filter'] !== ''
    ) {

        $parent_id = absint(
            $_GET['museum_parent_filter']
        );

        if ($parent_id) {

            $meta_query[] = array(
                'key'     => '_museum_subsection_parent_id',
                'value'   => $parent_id,
                'compare' => '=',
                'type'    => 'NUMERIC',
            );

        }

    }


    /*
     * Применяем фильтры
     */
    if (!empty($meta_query)) {

        $query->set(
            'meta_query',
            $meta_query
        );

    }

}

add_action(
    'pre_get_posts',
    'rubtsov_filter_subsections_by_section'
);

/*
 * Родительский подраздел
 */
function rubtsov_subsection_parent_box() {

    add_meta_box(
        'museum_subsection_parent',
        'Родительский подраздел',
        'rubtsov_subsection_parent_box_html',
        'museum_subsection',
        'side',
        'default'
    );

}

add_action(
    'add_meta_boxes',
    'rubtsov_subsection_parent_box'
);


/*
 * Содержимое поля родительского подраздела
 */
function rubtsov_subsection_parent_box_html($post) {

    $parent_id = get_post_meta(
        $post->ID,
        '_museum_subsection_parent_id',
        true
    );

    $subsections = get_posts(array(
        'post_type'      => 'museum_subsection',
        'post_status'    => array('publish', 'draft'),
        'posts_per_page' => -1,
        'post__not_in'   => array($post->ID),
        'orderby'        => 'title',
        'order'          => 'ASC',
    ));

    wp_nonce_field(
        'rubtsov_save_subsection_parent',
        'rubtsov_subsection_parent_box_nonce'
    );

    ?>

    <select
        name="museum_subsection_parent_id"
        style="width:100%;"
    >

        <option value="0">
            — Без родительского подраздела —
        </option>

        <?php foreach ($subsections as $subsection) : ?>

            <option
                value="<?php echo esc_attr($subsection->ID); ?>"
                <?php selected(
                    $parent_id,
                    $subsection->ID
                ); ?>
            >

                <?php echo esc_html(
                    $subsection->post_title
                ); ?>

            </option>

        <?php endforeach; ?>

    </select>

    <p class="description">
        Если это вложенная вкладка, выберите её родительский подраздел.
    </p>

    <?php
}


/*
 * Сохранение родительского подраздела
 */
function rubtsov_save_subsection_parent_box($post_id) {

    if (
        !isset(
            $_POST['rubtsov_subsection_parent_box_nonce']
        )
    ) {
        return;
    }

    if (
        !wp_verify_nonce(
            $_POST['rubtsov_subsection_parent_box_nonce'],
            'rubtsov_save_subsection_parent'
        )
    ) {
        return;
    }

    if (
        defined('DOING_AUTOSAVE') &&
        DOING_AUTOSAVE
    ) {
        return;
    }

    if (
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    $parent_id = isset(
        $_POST['museum_subsection_parent_id']
    )
        ? absint(
            $_POST['museum_subsection_parent_id']
        )
        : 0;

    update_post_meta(
        $post_id,
        '_museum_subsection_parent_id',
        $parent_id
    );

}

add_action(
    'save_post_museum_subsection',
    'rubtsov_save_subsection_parent_box'
);

/*
 * Отдельная страница подраздела музея
 */
function rubtsov_subsection_template($template) {

    if (is_singular('museum_subsection')) {

        $custom_template = get_template_directory() . '/single-museum_subsection.php';

        if (file_exists($custom_template)) {
            return $custom_template;
        }

    }

    return $template;
}

add_filter(
    'single_template',
    'rubtsov_subsection_template'
);

/*
 * Вывод подподразделов внутри подраздела
 */
function rubtsov_show_child_subsections($content) {

    if (!is_singular('museum_subsection')) {
        return $content;
    }

    global $post;

    $children = get_posts(array(
        'post_type'      => 'museum_subsection',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_key'       => '_museum_subsection_parent_id',
        'meta_value'     => $post->ID,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ));

    if (empty($children)) {
        return $content;
    }

    $output = '<div class="museum-child-subsections">';

    foreach ($children as $child) {

       $output .= '<a href="' . esc_url(
            get_permalink($child->ID)
        ) . '" class="museum-child-subsection ' .
        (
            get_post_meta(
                $child->ID,
                '_museum_subsection_full_width',
                true
            ) === '1'
                ? 'museum-child-subsection-full'
                : ''
        ) .
        '">';;

        $output .= esc_html($child->post_title);

        $output .= '</a>';

    }

    $output .= '</div>';

    return $output . $content;
}

add_filter(
    'the_content',
    'rubtsov_show_child_subsections'
);

function rubtsov_museum_admin_media() {

    $screen = get_current_screen();

    if (
        $screen &&
        $screen->id === 'toplevel_page_rubtsov-museum-settings'
    ) {

        wp_enqueue_media();

        wp_enqueue_script(
            'rubtsov-museum-admin-media',
            get_template_directory_uri() . '/assets/js/museum-admin-media.js',
            array('jquery'),
            '1.0',
            true
        );

    }

}

add_action(
    'admin_enqueue_scripts',
    'rubtsov_museum_admin_media'
);

/*
 * Подключение медиатеки и JS для файлов мероприятий
 */
function rubtsov_event_files_admin_scripts() {

    $screen = get_current_screen();

    if (
        !$screen ||
        $screen->post_type !== 'museum_event'
    ) {
        return;
    }

    wp_enqueue_media();

    wp_enqueue_script(
        'rubtsov-event-files',
        get_template_directory_uri() . '/assets/js/event-files.js',
        array('jquery'),
        '1.0',
        true
    );

}

add_action(
    'admin_enqueue_scripts',
    'rubtsov_event_files_admin_scripts'
);

/*
 * Поле даты мероприятия
 */
function rubtsov_event_date_metabox() {

    add_meta_box(
        'rubtsov_event_date',
        'Дата мероприятия',
        'rubtsov_event_date_metabox_html',
        'museum_event',
        'side',
        'high'
    );

}

add_action(
    'add_meta_boxes',
    'rubtsov_event_date_metabox'
);


/*
 * Вывод поля даты
 */
function rubtsov_event_date_metabox_html($post) {

    wp_nonce_field(
        'rubtsov_save_event_date',
        'rubtsov_event_date_nonce'
    );

    $event_date = get_post_meta(
        $post->ID,
        '_museum_event_date',
        true
    );

    ?>

    <input
        type="datetime-local"
        name="museum_event_date"
        value="<?php echo esc_attr($event_date); ?>"
        style="width:100%;"
    >

    <p class="description">
        Укажите дату и время проведения мероприятия.
    </p>

    <?php
}


/*
 * Сохранение даты
 */
function rubtsov_save_event_date($post_id) {

    if (
        !isset($_POST['rubtsov_event_date_nonce'])
    ) {
        return;
    }

    if (
        !wp_verify_nonce(
            $_POST['rubtsov_event_date_nonce'],
            'rubtsov_save_event_date'
        )
    ) {
        return;
    }

    if (
        defined('DOING_AUTOSAVE') &&
        DOING_AUTOSAVE
    ) {
        return;
    }

    if (
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    if (
        get_post_type($post_id) !== 'museum_event'
    ) {
        return;
    }

    if (isset($_POST['museum_event_date'])) {

        update_post_meta(
            $post_id,
            '_museum_event_date',
            sanitize_text_field(
                $_POST['museum_event_date']
            )
        );

    }

}

add_action(
    'save_post_museum_event',
    'rubtsov_save_event_date'
);

/*
 * Файлы мероприятия
 */
function rubtsov_event_files_metabox() {

    add_meta_box(
        'rubtsov_event_files',
        'Файлы для скачивания',
        'rubtsov_event_files_metabox_html',
        'museum_event',
        'normal',
        'high'
    );

}

add_action(
    'add_meta_boxes',
    'rubtsov_event_files_metabox'
);


/*
 * Вывод файлов мероприятия
 */
function rubtsov_event_files_metabox_html($post) {

    wp_nonce_field(
        'rubtsov_save_event_files',
        'rubtsov_event_files_nonce'
    );

    $files = get_post_meta(
        $post->ID,
        '_museum_event_files',
        true
    );

    if (!is_array($files)) {
        $files = array();
    }

    ?>

    <div id="rubtsov-event-files">

        <?php foreach ($files as $index => $file) : ?>

            <div
                class="rubtsov-event-file"
                style="
                    display:flex;
                    gap:10px;
                    align-items:center;
                    margin-bottom:10px;
                "
            >

                <input
                    type="text"
                    name="museum_event_files[<?php echo esc_attr($index); ?>][url]"
                    value="<?php echo esc_url($file['url'] ?? ''); ?>"
                    class="regular-text"
                    readonly
                >

                <input
                    type="text"
                    name="museum_event_files[<?php echo esc_attr($index); ?>][name]"
                    value="<?php echo esc_attr($file['name'] ?? ''); ?>"
                    placeholder="Название файла"
                    class="regular-text"
                >

                <button
                    type="button"
                    class="button rubtsov-remove-event-file"
                >
                    Удалить
                </button>

            </div>

        <?php endforeach; ?>

    </div>

    <button
        type="button"
        class="button button-primary"
        id="rubtsov-add-event-file"
    >
        Добавить файл
    </button>

    <p class="description">
        К мероприятию можно прикрепить любое количество файлов.
    </p>

    <?php
}

/*
 * Сохранение файлов мероприятия
 */
function rubtsov_save_event_files($post_id) {

    if (
        !isset($_POST['rubtsov_event_files_nonce'])
    ) {
        return;
    }

    if (
        !wp_verify_nonce(
            $_POST['rubtsov_event_files_nonce'],
            'rubtsov_save_event_files'
        )
    ) {
        return;
    }

    if (
        defined('DOING_AUTOSAVE') &&
        DOING_AUTOSAVE
    ) {
        return;
    }

    if (
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    if (
        get_post_type($post_id) !== 'museum_event'
    ) {
        return;
    }

    $files = isset($_POST['museum_event_files'])
        ? $_POST['museum_event_files']
        : array();

    $clean_files = array();

    if (is_array($files)) {

        foreach ($files as $file) {

            $url = isset($file['url'])
                ? esc_url_raw($file['url'])
                : '';

            $name = isset($file['name'])
                ? sanitize_text_field($file['name'])
                : '';

            if (!$url) {
                continue;
            }

            $clean_files[] = array(
                'url'  => $url,
                'name' => $name,
            );

        }

    }

    update_post_meta(
        $post_id,
        '_museum_event_files',
        $clean_files
    );

}

add_action(
    'save_post_museum_event',
    'rubtsov_save_event_files'
);


/*
 * Поле краткого описания мероприятия
 */
function rubtsov_event_description_metabox() {

    add_meta_box(
        'rubtsov_event_description',
        'Краткое описание',
        'rubtsov_event_description_metabox_html',
        'museum_event',
        'normal',
        'high'
    );

}

add_action(
    'add_meta_boxes',
    'rubtsov_event_description_metabox'
);


/*
 * Вывод поля краткого описания
 */
function rubtsov_event_description_metabox_html($post) {

    wp_nonce_field(
        'rubtsov_save_event_description',
        'rubtsov_event_description_nonce'
    );

    $description = get_post_meta(
        $post->ID,
        '_museum_event_description',
        true
    );

    ?>

    <textarea
        name="museum_event_description"
        rows="5"
        style="width:100%;"
        placeholder="Кратко опишите мероприятие..."
    ><?php echo esc_textarea($description); ?></textarea>

    <p class="description">
        Краткое описание, которое будет отображаться на карточке мероприятия.
    </p>

    <?php
}


/*
 * Сохранение краткого описания
 */
function rubtsov_save_event_description($post_id) {

    if (
        !isset($_POST['rubtsov_event_description_nonce'])
    ) {
        return;
    }

    if (
        !wp_verify_nonce(
            $_POST['rubtsov_event_description_nonce'],
            'rubtsov_save_event_description'
        )
    ) {
        return;
    }

    if (
        defined('DOING_AUTOSAVE') &&
        DOING_AUTOSAVE
    ) {
        return;
    }

    if (
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    if (
        get_post_type($post_id) !== 'museum_event'
    ) {
        return;
    }

    if (isset($_POST['museum_event_description'])) {

        update_post_meta(
            $post_id,
            '_museum_event_description',
            sanitize_textarea_field(
                $_POST['museum_event_description']
            )
        );

    }

}

add_action(
    'save_post_museum_event',
    'rubtsov_save_event_description'
);

/*
 * Колонки мероприятий в админке
 */
function rubtsov_event_columns($columns) {

    return array(
        'cb'          => '<input type="checkbox" />',
        'title'       => 'Название мероприятия',
        'event_image' => 'Афиша',
        'event_date'  => 'Дата и время',
        'event_desc'  => 'Описание',
        'date'        => 'Опубликовано',
    );

}

add_filter(
    'manage_museum_event_posts_columns',
    'rubtsov_event_columns'
);

/*
 * Содержимое колонок мероприятий
 */
function rubtsov_event_column_content($column, $post_id) {

    /*
     * Афиша
     */
    if ($column === 'event_image') {

        $content = get_post_field(
            'post_content',
            $post_id
        );

        $image = '';

        if (
            preg_match(
                '/<img[^>]+src=["\']([^"\']+)["\']/i',
                $content,
                $matches
            )
        ) {
            $image = $matches[1];
        }

        if ($image) {

            echo '<img
                src="' . esc_url($image) . '"
                style="
                    width:80px;
                    height:60px;
                    object-fit:cover;
                    border-radius:6px;
                "
            >';

        } else {

            echo '<span style="color:#999;">Нет фото</span>';

        }

    }


    /*
     * Дата и время
     */
    if ($column === 'event_date') {

        $event_date = get_post_meta(
            $post_id,
            '_museum_event_date',
            true
        );

        if ($event_date) {

            echo esc_html(
                date_i18n(
                    'd F Y · H:i',
                    strtotime($event_date)
                )
            );

        } else {

            echo '<span style="color:#999;">Не указана</span>';

        }

    }


    /*
     * Описание
     */
    if ($column === 'event_desc') {

        $content = get_post_field(
            'post_content',
            $post_id
        );

        $description = wp_strip_all_tags($content);

        if ($description) {

            echo esc_html(
                wp_trim_words(
                    $description,
                    15,
                    '…'
                )
            );

        } else {

            echo '<span style="color:#999;">Нет описания</span>';

        }

    }

}

add_action(
    'manage_museum_event_posts_custom_column',
    'rubtsov_event_column_content',
    10,
    2
);

/*
 * Сортировка мероприятий по дате
 */
function rubtsov_event_sortable_columns($columns) {

    $columns['event_date'] = 'museum_event_date';

    return $columns;

}

add_filter(
    'manage_edit-museum_event_sortable_columns',
    'rubtsov_event_sortable_columns'
);


function rubtsov_event_orderby($query) {

    if (!is_admin()) {
        return;
    }

    if (
        !$query->is_main_query() ||
        $query->get('post_type') !== 'museum_event'
    ) {
        return;
    }

    if (
        $query->get('orderby') === 'museum_event_date'
    ) {

        $query->set(
            'meta_key',
            '_museum_event_date'
        );

        $query->set(
            'orderby',
            'meta_value'
        );

    }

}

add_action(
    'pre_get_posts',
    'rubtsov_event_orderby'
);

/*
 * Вопросы квиза
 */
function rubtsov_register_quiz_questions() {

    register_post_type('quiz_question', array(

        'labels' => array(
            'name'          => 'Вопросы квиза',
            'singular_name' => 'Вопрос квиза',
            'add_new'       => 'Добавить вопрос',
            'add_new_item'  => 'Добавить новый вопрос',
            'edit_item'     => 'Редактировать вопрос',
            'new_item'      => 'Новый вопрос',
            'view_item'     => 'Посмотреть вопрос',
            'search_items'  => 'Найти вопрос',
            'not_found'     => 'Вопросы не найдены',
            'menu_name'     => 'Вопросы квиза',
        ),

        'public' => true,

        'show_ui' => true,

        'menu_icon' => 'dashicons-editor-help',

        'supports' => array(
            'title',
        ),

        'show_in_rest' => true,
        'rest_base' => 'quiz_question',

    ));

}

add_action(
    'init',
    'rubtsov_register_quiz_questions'
);

function rubtsov_quiz_register_rest_fields() {

    register_rest_field(
        'quiz_question',
        'quiz_data',
        array(
            'get_callback' => function ($post) {

                $answers = array();

                for ($i = 1; $i <= 4; $i++) {
                    $answers[] = get_post_meta(
                        $post['id'],
                        '_quiz_answer_' . $i,
                        true
                    );
                }

                return array(
                    'question' => get_post_meta(
                        $post['id'],
                        '_quiz_question',
                        true
                    ),

                    'answers' => $answers,

                    'correct' => (int) get_post_meta(
                        $post['id'],
                        '_quiz_correct_answer',
                        true
                    ),

                    'image' => get_post_meta(
                        $post['id'],
                        '_quiz_question_image',
                        true
                    ),
                );
            },
            'schema' => null,
        )
    );

}

add_action(
    'rest_api_init',
    'rubtsov_quiz_register_rest_fields'
);

/*
 * Поля вопроса квиза
 */
function rubtsov_quiz_question_metabox() {

    add_meta_box(
        'rubtsov_quiz_question_fields',
        'Настройки вопроса',
        'rubtsov_quiz_question_fields_html',
        'quiz_question',
        'normal',
        'high'
    );

}

add_action(
    'add_meta_boxes',
    'rubtsov_quiz_question_metabox'
);


/*
 * Вывод полей вопроса
 */
function rubtsov_quiz_question_fields_html($post) {

    wp_nonce_field(
        'rubtsov_save_quiz_question',
        'rubtsov_quiz_question_nonce'
    );

    $question = get_post_meta(
        $post->ID,
        '_quiz_question',
        true
    );

    $answers = array();

    for ($i = 1; $i <= 4; $i++) {

        $answers[$i] = get_post_meta(
            $post->ID,
            '_quiz_answer_' . $i,
            true
        );

    }

    $correct = get_post_meta(
        $post->ID,
        '_quiz_correct_answer',
        true
    );

    $image = get_post_meta(
        $post->ID,
        '_quiz_question_image',
        true
    );

    ?>

    <p>
        <label>
            <strong>Текст вопроса</strong>
        </label>
    </p>

    <textarea
        name="quiz_question"
        rows="4"
        style="width:100%;"
        placeholder="Введите текст вопроса..."
    ><?php echo esc_textarea($question); ?></textarea>

    <hr>

    <p>
        <label>
            <strong>Изображение вопроса</strong>
        </label>
    </p>

    <input
        type="text"
        name="quiz_question_image"
        id="quiz_question_image"
        value="<?php echo esc_attr($image); ?>"
        style="width:100%;"
    >

    <button
        type="button"
        class="button"
        id="quiz_question_image_button"
    >
        Выбрать изображение
    </button>

    <?php if ($image) : ?>

        <p style="margin-top:15px;">
            <img
                src="<?php echo esc_url($image); ?>"
                style="
                    max-width:300px;
                    height:auto;
                    display:block;
                    border-radius:8px;
                "
            >
        </p>

    <?php endif; ?>

    <p class="description">
        Изображение необязательно. Если его не выбрать,
        вопрос будет отображаться без фотографии.
    </p>
    <hr>


    <?php for ($i = 1; $i <= 4; $i++) : ?>

        <p>
            <label>
                <strong>
                    Вариант ответа <?php echo $i; ?>
                </strong>
            </label>
        </p>

        <input
            type="text"
            name="quiz_answer_<?php echo $i; ?>"
            value="<?php echo esc_attr($answers[$i]); ?>"
            style="width:100%;"
            placeholder="Введите вариант ответа..."
        >

    <?php endfor; ?>


    <hr>


    <p>
        <label>
            <strong>Правильный ответ</strong>
        </label>
    </p>

    <select
        name="quiz_correct_answer"
        style="width:100%;"
    >

        <option value="">
            — Выберите правильный ответ —
        </option>

        <?php for ($i = 1; $i <= 4; $i++) : ?>

            <option
                value="<?php echo $i; ?>"
                <?php selected($correct, $i); ?>
            >
                Вариант <?php echo $i; ?>
            </option>

        <?php endfor; ?>

    </select>

    <?php
}

/*
 * Сохранение вопроса квиза
 */
function rubtsov_save_quiz_question($post_id) {

    if (
        !isset($_POST['rubtsov_quiz_question_nonce'])
    ) {
        return;
    }

    if (
        !wp_verify_nonce(
            $_POST['rubtsov_quiz_question_nonce'],
            'rubtsov_save_quiz_question'
        )
    ) {
        return;
    }

    if (
        defined('DOING_AUTOSAVE') &&
        DOING_AUTOSAVE
    ) {
        return;
    }

    if (
        get_post_type($post_id) !== 'quiz_question'
    ) {
        return;
    }

    if (
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }


    /*
     * Текст вопроса
     */
    if (isset($_POST['quiz_question'])) {

        update_post_meta(
            $post_id,
            '_quiz_question',
            sanitize_textarea_field(
                $_POST['quiz_question']
            )
        );

    }


    /*
     * Варианты ответов
     */
    for ($i = 1; $i <= 4; $i++) {

        if (isset($_POST['quiz_answer_' . $i])) {

            update_post_meta(
                $post_id,
                '_quiz_answer_' . $i,
                sanitize_text_field(
                    $_POST['quiz_answer_' . $i]
                )
            );

        }

    }


    /*
     * Правильный ответ
     */
    if (isset($_POST['quiz_correct_answer'])) {

        $correct_answer = absint(
            $_POST['quiz_correct_answer']
        );

        if ($correct_answer >= 1 && $correct_answer <= 4) {

            update_post_meta(
                $post_id,
                '_quiz_correct_answer',
                $correct_answer
            );

        } else {

            delete_post_meta(
                $post_id,
                '_quiz_correct_answer'
            );

        }

    }

    /*
    * Изображение вопроса
    */
    if (isset($_POST['quiz_question_image'])) {

        $image = esc_url_raw(
            $_POST['quiz_question_image']
        );

        if ($image) {

            update_post_meta(
                $post_id,
                '_quiz_question_image',
                $image
            );

        } else {

            delete_post_meta(
                $post_id,
                '_quiz_question_image'
            );

        }

    }

}

add_action(
    'save_post_quiz_question',
    'rubtsov_save_quiz_question'
);

/*
 * JavaScript квиза
 */
function rubtsov_quiz_scripts() {

    if (is_singular('museum_subsection') && get_the_ID() === 17) {

        wp_enqueue_script(
            'rubtsov-museum-quiz',
            get_template_directory_uri() . '/assets/js/museum-quiz.js',
            array(),
            '1.0',
            true
        );

    }

}

add_action(
    'wp_enqueue_scripts',
    'rubtsov_quiz_scripts'
);

/*
 * Колонки в списке вопросов квиза
 */

function rubtsov_quiz_question_columns($columns) {

    $new_columns = array();

    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = 'Название';

    $new_columns['quiz_question'] = 'Текст вопроса';
    $new_columns['quiz_answer_1'] = 'Ответ 1';
    $new_columns['quiz_answer_2'] = 'Ответ 2';
    $new_columns['quiz_answer_3'] = 'Ответ 3';
    $new_columns['quiz_answer_4'] = 'Ответ 4';
    $new_columns['quiz_correct'] = 'Правильный ответ';

    $new_columns['date'] = $columns['date'];

    return $new_columns;
}

add_filter(
    'manage_quiz_question_posts_columns',
    'rubtsov_quiz_question_columns'
);


/*
 * Заполняем колонки данными
 */

function rubtsov_quiz_question_column_content(
    $column,
    $post_id
) {

    switch ($column) {

        case 'quiz_question':

            echo esc_html(
                get_post_meta(
                    $post_id,
                    '_quiz_question',
                    true
                )
            );

            break;


        case 'quiz_answer_1':

            echo esc_html(
                get_post_meta(
                    $post_id,
                    '_quiz_answer_1',
                    true
                )
            );

            break;


        case 'quiz_answer_2':

            echo esc_html(
                get_post_meta(
                    $post_id,
                    '_quiz_answer_2',
                    true
                )
            );

            break;


        case 'quiz_answer_3':

            echo esc_html(
                get_post_meta(
                    $post_id,
                    '_quiz_answer_3',
                    true
                )
            );

            break;


        case 'quiz_answer_4':

            echo esc_html(
                get_post_meta(
                    $post_id,
                    '_quiz_answer_4',
                    true
                )
            );

            break;


        case 'quiz_correct':

            $correct = get_post_meta(
                $post_id,
                '_quiz_correct_answer',
                true
            );

            if ($correct) {

                echo 'Вариант ' . esc_html($correct);

            } else {

                echo '—';

            }

            break;
    }

}

add_action(
    'manage_quiz_question_posts_custom_column',
    'rubtsov_quiz_question_column_content',
    10,
    2
);

/*
 * Общий фон сайта
 */
function rubtsov_museum_site_background() {

    $background = get_option(
        'museum_site_background',
        ''
    );

    if (!$background) {
        return;
    }

    ?>

    <style>
        body {
            background-image: url('<?php echo esc_url($background); ?>');
            background-position: center center;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>

    <?php
}

add_action(
    'wp_head',
    'rubtsov_museum_site_background'
);