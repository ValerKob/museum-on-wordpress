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

                </table>

            <h2>Footer</h2>

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

    if (isset($_POST['museum_home_title'])) {

        update_option(
            'museum_home_title',
            sanitize_text_field(
                $_POST['museum_home_title']
            )
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

    if (isset($_POST['museum_home_background'])) {

    update_option(
        'museum_home_background',
        esc_url_raw(
            $_POST['museum_home_background']
        )
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
        ) . '" class="museum-child-subsection">';

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