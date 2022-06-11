<?php
require_once 'autoload.php';

use Inc\SessionManager;
//use Controllers\User;

//$user = new \Controllers\User();
if( null === SessionManager::getInstance() ) session_start();
?>
<!DOCTYPE html>
<html lang="FR">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<meta name="description" content="" />
<meta name="author" content="" />
<title>création Blog PHP</title>
<!-- Favicon-->
<link rel="icon" type="image/x-icon" href="public/assets/favicon.ico" />
<!-- Font Awesome icons (free version)-->
<script src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" crossorigin="anonymous"></script>
<link href="public/startbootstrap-sb-admin-2-gh-pages/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

<!-- Bootstrap Icons-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
<!-- Core theme CSS (includes Bootstrap)-->
<link href="public/css/styles.css" rel="stylesheet" />

<style> 
	.date {
    font-size: 11px
}

.bg-gray{
    background-color: #fafafa !important;
}

.comment-text {
    font-size: 12px
}
.fs-12 {
    font-size: 12px
}
	
.shadow-none {
    box-shadow: none
}

.name {
    color: #007bff
}

.cursor:hover {
    color: blue
}

.cursor {
    cursor: pointer
}

.user_snippet_small_profile_image_rounded_geo{
    border-radius:100px;
    width:30px;
    height:30px;
    position:absolute;
    top:-15px;
}

.user_snippet_small_profile_image_rounded_geo:hover{
      box-shadow: 0px 0px 3px 1px #72bf3b;
}
</style>

</head> 