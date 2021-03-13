<?php
/**
 * Created by PhpStorm.
 * User: Виталий
 * Date: 10.03.2021
 * Time: 16:25
 */


class Widget_Class extends WP_Widget
{
    function __construct()
    {
        // Запускаем родительский класс
        parent::__construct(
            '',
            'Фильр коментариев',
            array('description' => 'фильтр коментариев')
        );

    }

    // Вывод виджета
    function widget($args, $instance)
    {
        global $wpdb;
        $comments = $wpdb->get_results("SELECT comment_author, user_id ,  COUNT(comment_author_email) AS comment_count
        FROM " . $wpdb->prefix . "comments  GROUP BY comment_author ORDER BY comment_count ASC  ");
        $comments2 = $wpdb->get_results("
        SELECT *
        FROM " . $wpdb->prefix . "comments  LEFT OUTER JOIN ".$wpdb->prefix . "users 
        ON  ".$wpdb->prefix . "comments.user_id = ".$wpdb->prefix . "users.ID
        GROUP BY ".$wpdb->prefix . "comments.comment_author  
        ORDER BY comment_author_email ASC
        ");


        $settingArr = get_option('plug_setting');
        $user_num = $settingArr['input'];
        $user_comm = $settingArr['checkbox'];
        $all_user_comm = $settingArr['checkbox_2'];


        if ($all_user_comm < 1):
        echo '<select id="users">';
        foreach ($comments as $key => $coment):
            $user_id = $coment->user_id;
            $user_role_meta = get_userdata( $user_id );
            $user_roles = $user_role_meta->roles;
            if ($key < $user_num):
                echo '<option>';
                echo $coment->comment_author;
                if ($user_comm !== 1):
                    if($user_roles != null):
                    echo '(' . $coment->comment_count. '): роль -'.$user_roles[0] ;
                else:
                    echo '(' . $coment->comment_count  . ')'. __('Пользователь не зарегистрирован') ;
                endif;
                endif;

                echo '</option>';
            endif;



        endforeach;

        echo '</select>';
        endif;

        if ($all_user_comm == 1):
            echo '<select id="users">';
            foreach ($comments2 as $key => $coment):
                $user_id = $coment->user_id;
                $user_role_meta = get_userdata( $user_id );
                $user_roles = $user_role_meta->roles;
                if ($key < $user_num):
                    echo '<option>';
                    echo $coment->comment_author;
                    if ($user_comm !== 1):
                        if($user_roles != null):
                            echo '(' . $coment->comment_count. '): роль -'.$user_roles[0] ;
                        else:
                            echo '(' . $coment->comment_count  . ')'. __('Пользователь не зарегистрирован') ;
                        endif;
                    endif;

                    echo '</option>';
                endif;



            endforeach;
            echo '</select>';
        endif;

    }


    // скрипт виджета
    function add_my_widget_scripts()
    {
        // фильтр чтобы можно было отключить скрипты
        if (!apply_filters('show_my_widget_script', true, $this->id_base))
            return;

        $theme_url = get_stylesheet_directory_uri();

        wp_enqueue_script('my_widget_script', $theme_url . '/my_widget_script.js');
    }


}
// Регистрация класса виджета



//Это рабочий  но не полный  запрос(не удачная попытка)

//$comments = $wpdb->get_results("SELECT comment_author ,  COUNT(comment_author_email) AS comment_count
//FROM " . $wpdb->prefix . "comments  GROUP BY comment_author ORDER BY comment_count ASC  ");


//^ получает  все  коментарии и сортирует
/*
$settingArr = get_option('plug_setting');
$user_num = $settingArr['input'];
$user_comm = $settingArr['checkbox'];
echo '<select id="users">';
foreach ($comments as $key => $coment):
    if ($key <= $user_num):
        echo '<option>';
        echo $coment->comment_author;
        if ($user_comm !== 1):
            echo '(' . $coment->comment_count . ')';
        endif;
        echo '</option>';
    endif;


endforeach;
*/