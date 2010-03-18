<?php

    global $transitional_menu;
    global $id;
    
    global $user;
    
    $transitional_menu = array();
    $transitional_menu['Profile'] = build_link('people','profile',$id);
    $transitional_menu['Message'] = build_link('people','message',$id);
        
    if ($user && $user instanceof Artist) {
        $transitional_menu['Albums'] = build_link('music','artist',$id);
    }
    
?>