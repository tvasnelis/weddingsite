<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width,user-scalable=no">
        <title><?php echo $pageTitle; ?></title>
        <link href='https://fonts.googleapis.com/css?family=Amaranth|Sacramento|Lato:400,700|Kaushan+Script|Montserrat' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="css/normalize.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/gallery_grid.css">
        <link rel="stylesheet" type="text/css" href="css/rsvp.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/form_hide.js"></script>
        <script type="text/javascript" src="js/modernizr.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
    </head>
    <body>
        <header>
            <div class="head_title fancy-font">
                <h1><a href="/">Tim<span class="accent"> & </span>Kimberly</a></h1>
                <h2 class="sub-font">March 25, 2017</h2>
                <h2 class="sub-font">New Orleans, LA</h2>
            </div>
            

            <nav>
                <ul>
                    <li class="sub-font <?php if ($section == "wedding") { echo " on"; } ?>"><a href="wedding">Wedding</a></li>
                    <li class="sub-font <?php if ($section == "travel") { echo " on"; } ?>"><a href="travel">Travel</a></li>
                    <li class="sub-font <?php if ($section == "stay") { echo " on"; } ?>"><a href="stay">Stay</a></li>
                    <li class="sub-font <?php if ($section == "experience") { echo " on"; } ?>"><a href="experience">Experience</a></li>
                    <li class="sub-font <?php if ($section == "rsvp") { echo " on"; } ?>"><a href="rsvp">RSVP</a></li>
                </ul>
            </nav>
        </header>