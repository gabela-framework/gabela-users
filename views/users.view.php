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

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    redirect('/');
}

// User class
$usersClass = new User();

getIncluded(WEB_CONFIGS);

if (isset($config['weather']['apikey'])) {
    $apiKey = $config['weather']['apikey'][0];
}

$city = $usersClass->getWeatherCity();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Users - VMP Tasks management</title>
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
                <li>Users</li>
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
                    <h2>Users</h2>
                </div>
                <hr class="blank">

                <?php
                if (isset($_SESSION['registration_error'])) {
                    // Use SweetAlert to display the error message
                    echo '<script>
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "' . $_SESSION['registration_error'] . '"
                            });
                        </script>';
                    // Clear the session variable
                    unset($_SESSION['registration_error']);
                }
                ?>

                <?php if (isset($_SESSION['user_deleted'])): ?>
                    <div class="alert alert-success">
                        <?php printValue($_SESSION['user_deleted']); ?>
                    </div>
                    <?php unset($_SESSION['user_deleted']); // Clear the message after displaying 
                        ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_updated'])): ?>
                    <div class="alert alert-success">
                        <?php printValue($_SESSION['user_updated']); ?>
                    </div>
                    <?php unset($_SESSION['user_updated']); // Clear the message after displaying 
                        ?>
                <?php endif; ?>

                <?php
                $allusers = $usersClass->getallusers();
                // Check if there are no tasks, and display the "Create Task" button if true
                if (empty($allusers)) {
                    echo '<a  href="createTask.php" class="btn btn-primary">Create a Task</a>';
                } else {
                    ?>

                    <table id="taskTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>User Name</th>
                                <th>User City</th>
                                <th>User Email</th>
                                <th data-orderable="false">Edit</th>
                                <th data-orderable="false">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $users = $usersClass->getallusers();
                            ?>
                            <!-- Loop through your tasks and display them as table rows -->
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <?php printValue($user->getUserId()); ?>
                                    </td>
                                    <td>
                                        <?php printValue($user->getName()); ?>
                                    </td>
                                    <td>
                                        <?php printValue($user->getCity()); ?>
                                    </td>
                                    <td>
                                        <?php printValue($user->getEmail()); ?>
                                    </td>
                                    <td>
                                        <!-- Edit button -->
                                        <button onclick="editTask(<?php printValue($user->getUserId()); ?>)"
                                            class="btn btn-primary btn-sm">Edit</button>
                                    </td>
                                    <td>
                                        <!-- Delete button -->
                                        <button onclick="deleteTask(<?php printValue($user->getUserId()); ?>)"
                                            class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                    <!-- JavaScript function to confirm and delete the task -->
                                    <script>
                                        function logoutNow() {
                                            if (confirm("Are you sure you want to logout?")) {
                                                // Redirect to logout.
                                                window.location.href = "<?= EXTENTION_PATH ?>/logout";
                                            }
                                        }

                                        function deleteTask(taskId) {
                                            if (confirm("Are you sure you want to delete this user?")) {
                                                // Redirect to deleteTask.php with the task ID
                                                window.location.href = "<?= EXTENTION_PATH ?>/user-delete?id=" + taskId;
                                            }
                                        }

                                        function editTask(taskId) {
                                            if (confirm("Are you sure you want to edit this user?")) {
                                                // Redirect to deleteTask.php with the task ID
                                                window.location.href = "<?= EXTENTION_PATH ?>/user-edit?id=" + taskId;
                                            }
                                        }
                                    </script>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php
                } // End of else block
                ?>

                <div class="map">
                </div>
            </div>
        </div>
    </section>
    <!-- content close -->
    <!-- added a partial and the code can be reusable -->
    <?php getIncluded(FOOTER_PARTIAL); ?>