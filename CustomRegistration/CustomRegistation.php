<?php
/*
  Plugin Name: Custom Registration
  Description: Ручная форма регистрации.
  Version: 1.0
  Author: УчебныйЦентр
 */

function registration_form( $username, $password, $email, $first_name, $last_name, $nickname, $bio ) 
{
    echo '
    <style>
    div {
        margin-bottom:2px;
    }
 
    input{
        margin-bottom:4px;
    }
    </style>
    ';
 
    echo '
    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
    
    <div>
    <label for="username">Имя пользователя <strong>*</strong></label>
    <input type="text" name="username" value="' . ( isset( $_POST['username'] ) ? $username : null ) . '">
    </div>
 
    <div>
    <label for="password">Пароль <strong>*</strong></label>
    <input type="password" name="password" value="' . ( isset( $_POST['password'] ) ? $password : null ) . '">
    </div>
 
    <div>
    <label for="email">E-mail <strong>*</strong></label>
    <input type="text" name="email" value="' . ( isset( $_POST['email']) ? $email : null ) . '">
    </div>
 
    <div>
    <label for="firstname">Имя</label>
    <input type="text" name="fname" value="' . ( isset( $_POST['fname']) ? $first_name : null ) . '">
    </div>
 
    <div>
    <label for="website">Фамилия</label>
    <input type="text" name="lname" value="' . ( isset( $_POST['lname']) ? $last_name : null ) . '">
    </div>
 
    <div>
    <label for="nickname">Ник</label>
    <input type="text" name="nickname" value="' . ( isset( $_POST['nickname']) ? $nickname : null ) . '">
    </div>
 
    <div>
    <label for="bio">Обо мне / Биография</label>
    <textarea name="bio">' . ( isset( $_POST['bio']) ? $bio : null ) . '</textarea>
    </div>
    <input type="submit" name="submit" value="Register"/>
    </form>
    ';
}

function registration_validation( $username, $password, $email, $first_name, $last_name, $nickname, $bio )  {
    global $reg_errors;
    $reg_errors = new WP_Error;
    if ( empty( $username ) || empty( $password ) || empty( $email ) ) 
    {
        $reg_errors->add('field', 'Не заполнены обязательные поля');
    }
    if ( 4 > strlen( $username ) ) 
    {
        $reg_errors->add( 'username_length', 'Имя пользователя должно быть не менее 4х символов' );    
    }
    if ( username_exists( $username ) )
        $reg_errors->add('user_name', 'Это имя уже занято');
    if ( ! validate_username( $username ) ) 
    {
        $reg_errors->add( 'username_invalid', 'Такое имя использовать нельзя' );
    }
    if ( 5 > strlen( $password ) ) {
        $reg_errors->add( 'password', 'Пароль должен быть больше 5 символов' );
    }
    if ( !is_email( $email ) ) {
    $reg_errors->add( 'email_invalid', 'Недопустимый почтовый адрес' );
    }
    if ( email_exists( $email ) ) {
    $reg_errors->add( 'email', 'Такой почтовый адрес уже зарегестрирован' );
    }
    if ( is_wp_error( $reg_errors ) ) 
    { 
        foreach ( $reg_errors->get_error_messages() as $error ) 
        {
            echo '<div>';
            echo '<strong>ERROR</strong>:';
            echo $error . '<br/>';
            echo '</div>';
 
        }
    }
}

function complete_registration() {
    global $reg_errors, $username, $password, $email, $first_name, $last_name, $nickname, $bio;
    if ( 1 > count( $reg_errors->get_error_messages() ) ) {
        $userdata = array(
        'user_login'    =>   $username,
        'user_email'    =>   $email,
        'user_pass'     =>   $password,
        'first_name'    =>   $first_name,
        'last_name'     =>   $last_name,
        'user_nicename'      =>   $nickname,
        'description'   =>   $bio,
        );
        $user = wp_insert_user( $userdata );
        echo 'Регистрация завершена. Перейдите на <a href="' . get_site_url() . '/wp-login.php">login page</a>.';   
    }
}

function custom_registration_function() {
    if ( isset($_POST['submit'] ) ) {
        registration_validation(
        $_POST['username'],
        $_POST['password'],
        $_POST['email'],
        $_POST['fname'],
        $_POST['lname'],
        $_POST['nickname'],
        $_POST['bio']
        );
 
        // проверка безопасности введенных данных
        global $username, $password, $email, $first_name, $last_name, $nickname, $bio;
        $username   =   sanitize_user( $_POST['username'] );
        $password   =   esc_attr( $_POST['password'] );
        $email      =   sanitize_email( $_POST['email'] );
        $first_name =   sanitize_text_field( $_POST['fname'] );
        $last_name  =   sanitize_text_field( $_POST['lname'] );
        $nickname   =   sanitize_text_field( $_POST['nickname'] );
        $bio        =   esc_textarea( $_POST['bio'] );
 
        // вызов @function complete_registration, чтобы создать пользователя
        // только если не обнаружено WP_error
        complete_registration(
        $username,
        $password,
        $email,
        $first_name,
        $last_name,
        $nickname,
        $bio
        );
    }
 
    registration_form(
        $username,
        $password,
        $email,
        $first_name,
        $last_name,
        $nickname,
        $bio
        );
}