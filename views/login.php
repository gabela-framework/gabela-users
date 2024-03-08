<?php
use Gabela\Core\Session;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Login / Register - Gabela Tasks</title>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
                                class="separator"></span><strong>Email:</strong> <a href="#"><?php printValue(Session::getCurrentUserEmail()) ?></a>
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
            <?php getIncluded(NAVBAR_PARTIAL) ?>

            <div id="subheader">
                <div class="container">
                    <div class="row">
                        <div class="span12">
                            <h1>Welcome</h1>
                            <span>Login / Signup to continue</span>
                            <ul class="crumb">
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <li><a href="<?= BASE_URL . 'tasks' ?>">Home</a>
                                    </li>
                                <?php elseif (!isset($_SESSION['user_id'])): ?>
                                    <li><a href="<?= BASE_URL . '' ?>">Home</a>
                                    </li>
                                <?php endif; ?>
                                <li class="sep">/</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- subheader close -->

            <!-- content begin -->
            <div id="content">
                <div class="container">
                    <div class="row">

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

                        <?php if (isset($_SESSION['login_error'])): ?>
                            <div class="alert alert-error">
                                <?php printValue($_SESSION['login_error']); ?>
                            </div>
                            <?php //unset($_SESSION['login_error']); // Clear the message after displaying
                                ?>
                        <?php endif; ?>

                        <?php
                        if (isset($_SESSION['login_error'])) {
                            // Use SweetAlert to display the error message
                            echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "' . $_SESSION['login_error'] . '"
                            });
                        </script>';
                            // Clear the session variable
                            unset($_SESSION['login_error']);
                        }
                        ?>


                        <?php if (isset($_SESSION['registration_success'])): ?>
                            <div class="alert alert-success">
                                <?php printValue($_SESSION['registration_success']); ?>
                            </div>
                            <?php unset($_SESSION['registration_success']); // Clear the message after displaying
                                ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['updated_password'])): ?>
                            <div class="alert alert-success">
                                <?php printValue($_SESSION['updated_password']); ?>
                            </div>
                            <?php unset($_SESSION['updated_password']); // Clear the message after displaying
                                ?>
                        <?php endif; ?>
                        <!-- Inside your HTML body -->
                        <?php if (isset($error)): ?>
                            <div class="error-message">
                                <?php printValue(htmlspecialchars($error, ENT_QUOTES, 'UTF-8')); ?>
                            </div>
                        <?php endif; ?>


                        <div class="span8">
                            <div>

                            <h3 style="padding-bottom: 20px;"> Log In To The Task Management</h3>
                            Feel free to contact us here (111) 333 7777 if you having issue.<br />
                            <br />
                            <div class="contact_form_holder">
                                <form id="contact loginForm" class="row" action="<?= EXTENTION_PATH ?>/login-submit"
                                    method="post">

                                    <div class="span4">
                                        <label>Email <span class="req">*</span></label>
                                        <input type="text" class="full" name="email" id="email" required />
                                        <div id="error_email" class="error">Please check your email</div>
                                    </div>

                                    <div class="span4">
                                        <label>Password</label>
                                        <input type="password" class="form-control" id="loginPassword" name="password"
                                            required>
                                    </div>

                                    <div class="span8">
                                        <div>
                                            <p id="btnsubmit">
                                                <button type="submit" class="btn btn-primary" name="login">Log
                                                    In</button>
                                            </p>
                                            <div class="d-flex align-items-center">
                                                <p class="m-0"> <a href="<?= BASE_URL . 'forgot-password' ?>">Forgot
                                                        Password?</a></p>
                                                <button class="btn btn-success ml-3" data-toggle="modal"
                                                    data-target="#registerModal">Register</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>

                            <?php if (isset($loginError)): ?>
                                <p class="text-danger">
                                    <?php printValue($loginError); ?>
                                </p>
                            <?php endif; ?>
                            <?php if (isset($createUserError)): ?>
                                <p class="text-danger">
                                    <?php printValue($createUserError); ?>
                                </p>
                            <?php endif; ?>

                            </form>
                        </div>
                    </div>

                    <!-- Registration Modal -->
                    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog"
                        aria-labelledby="registerModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="registerModalLabel">Register</h4>
                                </div>
                                <div class="modal-body">
                                    <!-- Registration form -->
                                    <form action="<?= EXTENTION_PATH ?>/register-submit" method="post"
                                        id="createUserForm">
                                        <div class="form-group">
                                            <label for="createName">Name:</label>
                                            <input type="text" class="form-control" id="name createName" name="name"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="createEmail">Your City:</label>
                                            <input type="text" class="form-control" id="city createEmail" name="city"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="createEmail">Email:</label>
                                            <input type="email" class="form-control" id="email createEmail" name="email"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="createPassword">Password:</label>
                                            <input type="password" class="form-control" id="createPassword"
                                                name="password" required>
                                        </div>
                                        <button type="submit" class="btn btn-success" name="create">Create User</button>
                                        <?php if (isset($createUserSuccess)): ?>
                                            <p class="text-success">
                                                <?php printValue($createUserSuccess); ?>
                                            </p>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </div>

    <!-- Latest compiled and minified JavaScript -->
    <script src="assets/includes/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- content close -->

    <?php getIncluded(FOOTER_PARTIAL); ?>