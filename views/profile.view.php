<?php

/**
 * @package   Task Management
 * @author    Ntabethemba Ntshoza
 * @date      11-10-2023
 * @copyright Copyright © 2023 VMP By Maneza
 */

 getRequired(USER_MODEL);
 getRequired(WEATHER_API);

use Gabela\Model\User;

// User class
$users = new User();

getIncluded(WEB_CONFIGS);

if (isset($config['weather']['apikey'])) {
    $apiKey = $config['weather']['apikey'][0];
}

$city = $users->getWeatherCity();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>User details - VMP Tasks management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Responsive Minimal Bootstrap Theme">
    <meta name="keywords" content="responsive,minimal,bootstrap,theme">
    <meta name="author" content="">

    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.js"></script>
    <link rel="stylesheet" href="css/ie.css" type="text/css">
    <![endif]-->

    <!-- CSS Files
    ================================================== -->
    <link rel="stylesheet" href="assets/css/main.css" type="text/css" id="main-css">
    <link rel="stylesheet" href="assets/includes/styles.css" type="text/css">

    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- Include DataTables CSS and JavaScript 
     =================================================-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- Include SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Javascript Files
    ================================================== -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.isotope.min.js"></script>
    <script src="assets/js/jquery.prettyPhoto.js"></script>
    <script src="assets/js/easing.js"></script>
    <script src="assets/js/jquery.ui.totop.js"></script>
    <script src="assets/js/selectnav.js"></script>
    <script src="assets/js/ender.js"></script>
    <script src="assets/js/jquery.lazyload.js"></script>
    <script src="assets/js/jquery.flexslider-min.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/contact.js"></script>
    <!-- Include jQuery -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
</head>

<body>
    <div id="wrapper">

        <!-- header begin -->
        <header>
            <div class="info">
                <div class="container">
                    <div class="row">
                        <div class="span6 info-text">
                            <strong>Phone:</strong> (111) 333 7777 <span
                                class="separator"></span><strong>Email:</strong> <a href="#">contact@example.com</a>
                        </div>
                        <div class="span6 text-right">
                            <div class="social-icons">
                                <a class="social-icon sb-icon-facebook" href="#"></a>
                                <a class="social-icon sb-icon-twitter" href="#"></a>
                                <a class="social-icon sb-icon-rss" href="#"></a>
                                <a class="social-icon sb-icon-dribbble" href="#"></a>
                                <a class="social-icon sb-icon-linkedin" href="#"></a>
                                <a class="social-icon sb-icon-flickr" href="#"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- added a partial and the code can be reusable -->
            <?php getIncluded(NAVBAR_PARTIAL); ?>
            <?php getIncluded(WEATHER_PARTIAL); ?>

            <ul class="crumb">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="<?= BASE_URL . 'tasks' ?>">Home</a>
                    </li>
                <?php elseif (!isset($_SESSION['user_id'])): ?>
                    <li><a href="<?= BASE_URL . '' ?>">Home</a>
                    </li>
                <?php endif; ?>
                <li class="sep">/</li>
                <li>User details</li>
            </ul>
    </div>
    </div>
    </div>
    </div>
    <!-- subheader close -->

    <!-- services section begin -->
    <section id="services" data-speed="10" data-type="background">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <?php

                    // Check if the user_id parameter is set in the URL
                    if (isset($_GET['user_id'])) {
                        $user_id = $_GET['user_id'];

                        // Create an instance of the User class
                        $user = new User();

                        // Use a function to fetch user details by user_id
                        $userData = $user->getUserById($user_id);

                        if ($userData) {
                            // User details found, display them
                            $name = $userData['name'];
                            $email = $userData['email'];
                            $city = $userData['city'];

                            echo "<h1>User Details</h1>";
                            echo "<br>";
                            echo "<div class='panel panel-default'>";
                            echo "<div class='panel-heading'>User Information</div>";
                            echo "<div class='panel-body'>";
                            echo "<br>";
                            echo "<p><strong>Name:</strong> $name</p>";
                            echo "<p><strong>City:</strong> $city</p>";
                            echo "<p><strong>Email:</strong> $email</p>";
                            echo "</div>";
                            echo "</div>";
                            echo "<br>";

                            $base = BASE_URL;
                            // Add a "Return to All Tasks" button
                            echo "<a href='" . BASE_URL . 'tasks' . "' class='btn btn-primary'>Return to All Tasks</a>";
                        } else {
                            // User not found, display an error message
                            echo "<div class='alert alert-danger'>User not found, make sure you provide a correct user id.</div>";
                        }
                    } else {
                        // user_id parameter not set, display an error message
                        echo "<div class='alert alert-danger'>Invalid request. Please provide a user ID.</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
    <!-- content close -->
    <br />

    <?php getIncluded(FOOTER_PARTIAL)?>

</html>