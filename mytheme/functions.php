<?php

/**
* Поддержка миниатюр
**/
add_theme_support( 'post-thumbnails' );

/**
* Подключение меню
**/
register_nav_menus( array(
  'main-menu' => __( 'main-menu' )
));

/**
* Подключение сайдбара
**/
register_sidebar( array(
  'name' => __( 'Sidebar', 'mytheme' ),
  'id' => 'sidebar',
  'description' => 'Правая колонка сайта.',
  'before_widget' => '<aside class="widget">',
  'after_widget' => '</aside>',
  'before_title' => '<div class="widget-title">',
  'after_title' => '</div>',
));

/**
* Функции вывода анонса поста (цитаты) на главной
**/
function new_excerpt_length( $length ) {
  return 100;//количество выводимых символов анонса (по умолчанию 55)
}
add_filter( 'excerpt_length', 'new_excerpt_length' );

/**
* Функции заменяет «[…]» на «...» в конце цитаты
**/
function new_excerpt_more( $more ) {
  return '...';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );

?>