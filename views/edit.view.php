<?php

/**
 * @package   user Management
 * @author    Ntabethemba Ntshoza
 * @date      11-10-2023
 * @copyright Copyright © 2023 VMP By Maneza
 */

// require __DIR__ . '/../../models/User.php';
require __DIR__ . '/../models/User.php';
//require BASE_PATH . '/gabela/helper/weather.php';
getRequired(WEATHER_API);


use Gabela\Users\Model\User;

// require 'models/User.php';

// Check if the user is logged in
$users = new User();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    redirect('/index');
}

$config = getIncluded(WEB_CONFIGS);

if (isset($config['weather']['apikey'])) {
    $apiKey = $config['weather']['apikey'][0];
}

$city = $users->getWeatherCity();

// Fetch the user details for the given ID
if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    // $userId = $_SESSION["user_id"];

    $user = new User();
    // Create an instance of the user class and fetch the user by ID
    $userData = $user->getUserById($userId);

    if (!$user) {
        // Handle the case where the user with the provided ID does not exist
        redirect('/users');
    }
} else {
    // Handle the case where ID is not provided, perhaps show an error message or redirect
    redirect('/users');
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Edit Task - VMP Tasks management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Responsive Minimal Bootstrap Theme">
    <meta name="keywords" content="responsive,minimal,bootstrap,theme">
    <meta name="author" content="">

    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.js"></script>
    <link rel="stylesheet" href="css/ie.css" type="text/css">
    <![endif]-->

    <!-- Include DataTables CSS and JavaScript 
     =================================================-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- CSS Files
    ================================================== -->
    <link rel="stylesheet" href="assets/css/main.css" type="text/css" id="main-css">
    <link rel="stylesheet" href="assets/includes/styles.css" type="text/css">

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        #editUser input {
            width: 50%;
        }
    </style>
</head>

<body>
    <div id="wrapper">

        <!-- header begin -->
        <header>
            <div class="container">
                <div id="logo" style=" width: 250px; height: auto; ">
                    <div class="inner">
                        <a href="<?= BASE_URL . 'tasks' ?>">
                            <img src="assets/images/logo2.png" alt="logo"></a>
                    </div>
                </div>

                <!-- mainmenu begin -->
                <ul id="mainmenu">
                    <li><a href="<?= BASE_URL . 'tasks' ?>">Home</a>
                    </li>
                    <li><a href="<?= BASE_URL . 'users' ?>">Users</a>
                    </li>
                    <li><a onclick="logoutNow()" href="#">Logout</a></li>
                </ul>
                <!-- mainmenu close -->

            </div>
        </header>
        <!-- header close -->

        <!-- subheader begin -->
        <div id="subheader">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <h1>Current Weather</h1>
                        <span>
                            <?php $weatherData = getCurrentWeather($city, $apiKey); ?>

                            <!-- Styling for weather information -->
                            <div>
                                <!-- <h3>Current Weather</h3> -->
                                <ul>
                                    <li><strong>City:</strong>
                                        <?php echo isset($weatherData["name"]) ? $weatherData["name"] : "Sorry!! Your City can't be pulled by OpenWeather"; ?>
                                    </li>
                                    <li> <strong>Current Temp</strong>:
                                        <?php echo isset($weatherData["main"]["temp"]) ? $weatherData["main"]["temp"] . "°C" : ""; ?>
                                    </li>
                                    <li> <strong>Weather:</strong>
                                        <?php echo isset($weatherData["weather"][0]["description"]) ? $weatherData["weather"][0]["description"] : ""; ?>
                                    </li>
                                </ul>
                            </div>
                        </span>
                        <ul class="crumb">
                            <?php if (isset($_SESSION['user_id'])) : ?>
                                <li><a href="<?= BASE_URL . 'tasks' ?>">Home</a>
                                </li>
                            <?php elseif (!isset($_SESSION['user_id'])) : ?>
                                <li><a href="<?= BASE_URL . '' ?>">Home</a>
                                </li>
                            <?php endif; ?>
                            <li class="sep">/</li>
                            <li>Edit Users</li>
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
                    <div class="text-center">
                        <h2>Edit User</h2>
                    </div>
                    <hr class="blank">

                    <!-- user edit form -->
                    <form style="width: 65%;" method="post" action="<?= EXTENTION_PATH ?>/users-edit-submit" class="form-horizontal">
                        <input type="hidden" name="id" value="<?php echo $userData['id']; ?>">

                        <div class="form-group">
                            <label for="title" class="col-sm-2 ">Edit Name:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="title" name="name" value="<?php echo $userData['name']; ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="due_date" class="col-sm-2 ">Edit City:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="due_date" name="city" value="<?php printValue($userData['city']); ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="role" class="col-sm-2">Edit User Role:</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="role" name="role" required>
                                    <option value="user" <?php echo $userData['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                                    <option value="admin" <?php echo $userData['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- JavaScript function to confirm and delete the task -->
        <script>
            function logoutNow() {
                if (confirm("Are you sure you want to logout?")) {
                    // Redirect to logout
                    window.location.href = "<?= EXTENTION_PATH ?>/logout";
                }
            }
        </script>
        <!-- content close -->

        <?php getIncluded(FOOTER_PARTIAL); ?>