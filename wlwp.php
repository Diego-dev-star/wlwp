<?php
/*
Plugin Name: white label
Description:  сортировка коментариев и  создание  виджета
Author: Vitali B
Version: 0.1

*/
require plugin_dir_path( __FILE__ ) . '/class/Widget_Class.php';

function add_plugin_page(){
    add_options_page( 'Плагин для White Label', 'wlwp Setings', 'manage_options', 'wl', 'wlwp_settings_page' );
}


function wlwp_settings_page(){
    ?>
    <div class="wrap">
        <h2><?php echo get_admin_page_title() ?></h2>

        <form action="options.php" method="POST">
            <?php
            settings_fields( 'option_group' );
            do_settings_sections( 'wlwp' ); // секции с настройками (опциями). У нас она всего одна 'section_id'
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

add_action('admin_menu', 'add_plugin_page');

/**
 * Регистрируем настройки.
 * Настройки будут храниться в массиве, а не одна настройка = одна опция.
 */

function plugin_settings(){
    // параметры: $option_group, $option_name, $sanitize_callback
    register_setting( 'option_group', 'plug_setting', 'sanitize_callback' );

    // параметры: $id, $title, $callback, $page
    add_settings_section( 'section_id', 'Настройки плагина', '', 'wlwp' );
    // параметры: $id, $title, $callback, $page, $section, $args
    add_settings_field('users-field', 'Сколько пользователей', 'users', 'wlwp', 'section_id' );
    add_settings_field('commenе-null', 'Выключить подсчет коментариев', 'NullOrNot', 'wlwp', 'section_id' );
    add_settings_field('no-coment', 'Пользователи  без  коментариев', 'Null', 'wlwp', 'section_id' );
}
add_action('admin_init', 'plugin_settings');

## Заполняем опцию 1
function users(){
    $val = get_option('plug_setting');
    $val = $val ? $val['input'] : null;
    ?>
    <input type="number" name="plug_setting[input]" value="<?php echo esc_attr( $val ) ?>" />
    <?php
}

## Заполняем опцию 2
function NullOrNot(){
    $val = get_option('plug_setting');
    $val = $val ? $val['checkbox'] : null;
    ?>
    <label><input type="checkbox" name="plug_setting[checkbox]" value="1" <?php checked( 1, $val ) ?> /> <?php __('количество  коментариев пользователя')?></label>
    <?php
}
## Заполняем опцию 3
function Null(){
    $val = get_option('plug_setting');
    $val = $val ? $val['checkbox_2'] : null;
    ?>
    <label><input type="checkbox" name="plug_setting[checkbox_2]" value="1" <?php checked( 1, $val ) ?> /> <?php __('Выводить пользователей без соментариев ?')?></label>
    <?php
}
function sanitize_callback( $options ){

    foreach( $options as $name => & $val ){
        if( $name == 'input' )
            $val = strip_tags( $val );

        if( $name == 'checkbox' )
            $val = intval( $val );

        if( $name == 'checkbox_2' )
            $val = intval( $val );
    }

    //Проверка die(print_r( $options )); // Array ( [input] => aaaa [checkbox] => 1 )

    return $options;
}
function registr() {
    $widget_class = new Widget_Class();
    register_widget( $widget_class );
}

add_action( 'widgets_init', 'registr' );


