<?php
function isAdmin(){
    return auth()->user()->hasRole('admin');
};
function authenticateIfNotAdmin($userID, $targetID){
    if (!auth()->user()->hasRole('admin') && ($userID != $targetID)){
        return abort(401, 'You are not authorized to perform this action.');
    }
};

/**
 * Arabic Specific function. Remove it if you don't need it.
 */
function NormalizeArabic($str){
    $str = str_replace("أ", "ا", $str);
    $str = str_replace("آ", "ا", $str);
    $str = str_replace("إ", "ا", $str);
    $str = str_replace("ة", "ه", $str);
    $str = str_replace("ي", "ى", $str);
    return str_replace("ؤ", "و", $str);
}
