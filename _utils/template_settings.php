<?php
/**
 * Created by PhpStorm.
 * User: Cristian
 * Date: 5/2/2017
 * Time: 11:36 PM
 */

/**
 * printeaza inceputul de pagina
 * @param $config_vars array
 * @return string
 */
function _print_init($config_vars = array()){
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>'.(strlen(trim($config_vars['title'])) > 0 ? $config_vars['title']:'Admin').'</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="template/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="template/css/AdminLTE.css">';
        if(isset($config_vars['style']) && !empty($config_vars['style'])){
            foreach ($config_vars['style'] as $style){
                $html .= '<link rel="stylesheet" href="'.$style.'">';
            }
        }

    $html .= '<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <noscript>
    <style type="text/css">
        .wrapper {display:none;}
    </style>
    <div class="noscriptmsg">
        Nu ai javascript pornit. Imi pare rau dar nu poti accesa site-ul fara javascript. Daca vrei poti merge <a href="https://www.google.ro">aici</a> pentru a cauta site-uri care accepta javascript oprit.
    </div>
</noscript>
    </head>';
    if(isset($config_vars['return'])){
        if($config_vars['return'] == true)
            return $html;
        else
            echo $html;
    } else {
        echo $html;
    }
}

function _print_footer($config_vars = array()){
    $html = '<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
             <script src="template/js/bootstrap.js"></script>';
    if(isset($config_vars['js']) && !empty($config_vars['js'])) {
        foreach ($config_vars['js'] as $javascript) {
            $html .= '<script src="'.$javascript.'"></script>';

        }
    }
    $html .= '   </body>
                </html>';
    if(isset($config_vars['return'])){
        if($config_vars['return'] == true)
            return $html;
        else
            echo $html;
    } else {
        echo $html;
    }

}