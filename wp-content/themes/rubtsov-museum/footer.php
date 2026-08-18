<footer>
    <div class="site-footer">

        <p>
            <?php
            echo esc_html(
                get_option(
                    'museum_footer_title',
                    'Виртуальный музей Николая Рубцова'
                )
            );
            ?>
        </p>

        <p>
            <?php
            echo esc_html(
                get_option(
                    'museum_footer_subtitle',
                    'МБОУ СШ №4 · Архангельск'
                )
            );
            ?>
        </p>

    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>