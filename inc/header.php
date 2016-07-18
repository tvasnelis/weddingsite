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
    </head>
    <body>
        <header>
            <div class="head_title fancy-font">
                <h1><a href="index.php">Tim<span class="accent"> & </span>Kimberly</a></h1>
                <h2 class="sub-font">March 25, 2017</h2>
                <h2 class="sub-font">New Orleans, LA</h2>
            </div>
            

            <nav>
                <ul>
                    <li class="sub-font <?php if ($section == "wedding") { echo " on"; } ?>"><a href="wedding.php">Wedding</a></li>
                    <li class="sub-font <?php if ($section == "travel") { echo " on"; } ?>"><a href="travel.php">Travel</a></li>
                    <li class="sub-font <?php if ($section == "stay") { echo " on"; } ?>"><a href="stay.php">Stay</a></li>
                    <li class="sub-font <?php if ($section == "experience") { echo " on"; } ?>"><a href="experience.php">Experience</a></li>
                    <li class="sub-font <?php if ($section == "rsvp") { echo " on"; } ?>"><a href="rsvp_1.php">RSVP</a></li>
                </ul>
            </nav>
        </header>