<?php

/**
 * @package   Task Management
 * @author    Ntabethemba Ntshoza
 * @date      11-10-2023
 * @copyright Copyright © 2023 VMP By Maneza
 */

namespace Gabela\Users\Model;

use mysqli;
use Exception;
use Gabela\Core\Database;
use Monolog\Logger;
use Gabela\Model\UserInterface;
use Monolog\Handler\StreamHandler;

/**
 * Users class to get users from the database
 * @package Model
 */
class User implements UserInterface
{
    private $name;
    private $city;
    private $email;
    private $password;
    private $id;
    private $db;
    private $logger;

    public function __construct(mysqli $db = null)
    {
        $this->db = Database::connect();
        $this->logger = new Logger('users');
        $this->logger->pushHandler(new StreamHandler('var/System.log', Logger::DEBUG));
    }

    /**
     * Get the value of id
     */
    public function getUserId()
    {
        return $this->id;
    }

    // Setter method for name
    public function setUserId($id)
    {
        $this->id = $id;
    }

    // Getter method for name
    public function getName()
    {
        return $this->name;
    }

    // Setter method for name
    public function setName($name)
    {
        $this->name = $name;
    }

    // Getter method for email
    public function getEmail()
    {
        return $this->email;
    }

    // Setter method for email
    public function setEmail($email)
    {
        $this->email = $email;
    }

    // Getter method for password
    public function getPassword()
    {
        return $this->password;
    }

    // Setter method for password
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get the value of city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of city
     *
     * @return  self
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }


    /**
     * Get the value of city
     */
    public function getWeatherCity()
    {
        // Prepare the SQL statement to fetch all users
        $user_id = $this->getWeatherUserId(); // Get the user's ID for the currently logged-in user;
        $sql = "SELECT city FROM users WHERE id = $user_id";

        // Execute the query
        $result = $this->db->query($sql);

        // Check if the query was successful
        if ($result) {
            $theCity = [];

            // Fetch user data and create User objects
            while ($row = $result->fetch_assoc()) {
                $theCity = isset($row['city']) ? $row['city'] : "Cape town";
            }

            if (!is_null($theCity)) {
                // var_dump($theCity);

                return $theCity;
            } else {

                return false;
            }
        }
    }

    // Getter method for name
    public function getWeatherUserId()
    {
        // Retrieve the user's ID from the session
        if (isset($_SESSION['user_id'])) {
            return $_SESSION['user_id'];
        } else {
            // Handle the case where the user's ID is not found in the session
            return $this->city;
        }
    }

    /**
     * Save New Users
     *
     * @return Bool
     */
    public function save()
    {
        // Hash the password before saving
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        // Prepare the SQL statement
        $sql = "INSERT INTO users (name, city, email, password) VALUES (?, ?, ?, ?)";
        // Bind parameters and execute the query
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssss", $this->name, $this->city, $this->email, $hashedPassword);

        try {
            // Your registration code here
            if ($stmt->execute()) {
                $_SESSION['registration_success'] = 'Heey!!! ' . $this->name . ' you registered successfully. Please Login..';
                $this->logger->critical("{$this->getName()} Registered successfully!!");

                return true; // User saved successfully
            } else {
                $_SESSION['registration_error'] = 'An error occurred while registering. This email address is already in use.';
                $this->logger->critical(var_export($_SESSION['registration_error'], true));

                return false; // User could not be saved
            }
        } catch (mysqli_sql_exception $e) {
            $_SESSION['registration_error'] = 'An error occurred while registering. This email address is already in use.';
            $this->logger->error('An exception occurred', ['exception' => $e]);
        }
    }

    /**
     * Login funtion
     *
     * @param string $email
     * @param string $password
     * @return Bool
     */
    public function login($email, $password)
    {
        
        try {
            // Prepare the SQL statement to retrieve user data based on the provided email
            $sql = "SELECT id, name, email, password FROM users WHERE email = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();

            if ($stmt->error) {
                $errorMessage = "Database query error: " . $stmt->error;
                $this->logger->error($errorMessage);
                die($errorMessage);
            }

            $result = $stmt->get_result();

            // Check if a user with the provided email exists
            if ($result->num_rows === 0) {
                $_SESSION['login_error'] = 'Aaaahh!! Check your email/password...';
                return false; // User not found
            }

            // Fetch the user data
            $user = $result->fetch_assoc();

            // Verify the provided password against the stored hash
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['login_success'] = 'Hey ' . $user['name'] . ' you are logged in successfully!';

                return true;
            } else {
                $_SESSION['login_error'] = 'Aaaahh!! Check your email/password...';

                return false; // Incorrect password
            }
        } catch (Exception $e) {
            $errorMessage = 'An exception occurred: ' . $e->getMessage();
            $this->logger->error($errorMessage, ['exception' => $e]);
            $_SESSION['login_error'] = 'Aaaahh!! Something went wrong...';

            return false; // Handle unexpected exceptions
        }
    }



    /**
     * Validate Password
     * @param string $password
     * 
     * @return Bool
     */
    function validatePassword($password)
    {
        // Check password length
        if (strlen($password) < 8) {
            return false;
        }

        // Check for at least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }

        // Check for at least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }

        // Check for at least one digit
        if (!preg_match('/\d/', $password)) {
            return false;
        }

        // Password passed all checks
        return true;
    }

    /**
     * Forgot password function
     *
     * @param string $email
     * @return Bool
     */
    public function forgotPassword($email)
    {
        // Check if the email exists in the database
        if ($this->emailExists($email)) {
            // Generate a reset token and set it in the database
            $resetToken = bin2hex(random_bytes(16)); // Generate a random token
            $resetExpiration = date('Y-m-d H:i:s', strtotime('+1 hour')); // Set expiration time

            // Update the user's reset_token and reset_expiration in the database
            $sql = "UPDATE users SET reset_token = ?, reset_expiration = ? WHERE email = ?";
            // $stmt = $this->conn->prepare($sql);
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('sss', $resetToken, $resetExpiration, $email);

            if ($stmt->execute()) {
                // Send a reset email to the user with a link to reset their password
                $resetLink = "{$this->getSiteUrl('/reset-password')}?email=" . $email . "&token=" . $resetToken; //edit this to your website url if you not getting it
                $message = "To reset your password, click on the following link:\n" . "<a href='{$resetLink}'>Reset your password now</a>";
                $_SESSION['reset_password'] =  $message;
                $_SESSION['email'] =  $email;
                $_SESSION['token'] =  $resetToken;

                return true;
            }
        }

        printValue("The email address does not exit verify and try again");

        return redirect('/forgot-password');
    }

    /**
     * Get Website URL
     *
     * @param string $path
     * @return string
     */
    function getSiteUrl($path = '/')
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        $scriptName = $_SERVER['SCRIPT_NAME'];

        // Remove the script filename to get the base URL
        $baseUrl = dirname($scriptName);

        return $protocol . $host . $baseUrl . $path;
    }


    /**
     * Check if the email already exist in the database
     *
     * @param string $email
     * @return void
     */
    private function emailExists($email)
    {
        // Prepare the SQL statement to fetch all users
        $sql = "SELECT * FROM users";

        // Execute the query
        $result = $this->db->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                if ($email == $row['email']) {
                    return true;
                }
            }

            $_SESSION['wrong_email'] =  "{$email} does not exist, please verify and try again...";
            // The loop has finished checking all users, and the email doesn't exist
            return false;
        }
    }

    /**
     * Check if password is valid for pass reset
     *
     * @param string $email
     * @param string $token
     * @return boolean
     */
    public function isValidPasswordResetRequest($email, $token)
    {
        // Prepare a SQL statement to check if the email and token match a valid reset request.
        $sql = "SELECT email FROM users WHERE email = ? AND reset_token = ? AND reset_expiration > NOW()";

        // Use prepared statements to prevent SQL injection.
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();
        $stmt->store_result();

        // If a row is found, the request is valid.
        $isValid = ($stmt->num_rows === 1);

        $stmt->close();

        return $isValid;
    }

    /**
     * Update Password function
     *
     * @param string $email
     * @param string $newPassword
     * @return Bool
     */
    public function updatePassword($email, $newPassword)
    {
        // Hash the new password (you should use a secure hashing method).
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Prepare an SQL statement to update the user's password.
        $sql = "UPDATE users SET password = ? WHERE email = ?";

        // Use prepared statements to prevent SQL injection.
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $hashedPassword, $email);
        $result = $stmt->execute();

        if ($result) {
            $_SESSION['updated_password'] = "Password reset was successful! | Please Login!";
            $stmt->close();
        }

        // Return true if the update was successful, false otherwise.
        return $result;
    }

    /**
     * Get All users
     *
     * @return array
     */
    public static function getAllUsers()
    {
        $db = Database::connect();
        // Prepare the SQL statement to fetch all users
        $sql = "SELECT * FROM users";

        // Execute the query
        $result = $db->query($sql);

        // Check if the query was successful
        if ($result) {
            $users = [];
            $databaseInstance = Database::connect();

            // Fetch user data and create User objects
            while ($row = $result->fetch_assoc()) {
                $user = new User($databaseInstance);
                $user->setUserId($row['id']);
                $user->setName($row['name']);
                $user->setCity($row['city']);
                $user->setEmail($row['email']);
                // Add more setters for other user properties as needed

                // Add the User object to the array
                $users[] = $user;
            }

            return $users;
        } else {
            return false; // Query failed
        }
    }

    /**
     * Get usernames from Database 
     *
     * @return 
     */
    public function getUsersFromDatabase()
    {
        // Prepare the SQL statement to fetch all users
        $sql = "SELECT id, name FROM users";

        // Execute the query
        $result = $this->db->query($sql);

        if ($result) {
            $userNames = [];

            while ($row = $result->fetch_assoc()) {
                $userNames[] = $row;
            }

            return $userNames;
        } else {
            return false; // Query failed
        }
    }

    /**
     * Get a user by ID
     *
     * @param string|int $userId
     * @return mixed
     */
    public function getUserById($userId)
    {
        // Prepare the SQL statement to retrieve user data by user_id
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            // Execute the query
            $result = $stmt->get_result();

            // Check if a user with the provided user_id exists
            if ($result->num_rows === 1) {
                // Fetch user data
                $userData = $result->fetch_assoc();
                return $userData;
            }
        }

        return null; // User not found or query failed
    }


    /**
     * Function to update an existing user in the database
     *
     * @return Bool
     */
    public function update()
    {
        // Prepare the SQL statement
        $sql = "UPDATE users 
                SET name = ?, city = ?
                WHERE id = ?";

        // Bind parameters and execute the query
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "ssi",  // "ssi" indicates that you are binding two strings and an integer
            $this->name,
            $this->city,
            $this->id // Add the binding for the 'id' parameter
        );

        if ($stmt->execute()) {
            $_SESSION['user_updated'] = "User: {$this->name} updated successfully";

            return true; // user updated successfully
        } else {
            return false; // user could not be updated
        }
    }


    /**
     * Delete users
     *
     * @return bool
     */
    public function delete($userId)
    {
        // Delete associated tasks first
        $this->deleteUserTasks($userId);

        // Prepare the SQL statement
        $sql = "DELETE FROM users WHERE id = ?";

        // Bind parameters and execute the query
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            $_SESSION['user_deleted'] = "User: {$userId} deleted successfully";
            return true; // user deleted successfully
        } else {
            return false; // user could not be deleted
        }
    }

    /**
     * Delete tasks associated with the user before deleteing the user
     *
     * @param [type] $userId
     * @return void
     */
    private function deleteUserTasks($userId)
    {
        // Prepare the SQL statement
        $sql = "DELETE FROM tasks WHERE user_id = ?";

        // Bind parameters and execute the query
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);

        // Execute the delete query for associated tasks
        $stmt->execute();
    }
}
