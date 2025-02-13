<?php

/**
 * @package   Task Management
 * @author    Ntabethemba Ntshoza
 * @date      11-10-2023
 * @copyright Copyright © 2023 VMP By Maneza
 */

namespace Gabela\Users\Model;

use PDO;
use Exception;
use Monolog\Logger;
use Gabela\Core\Model;
use Gabela\Core\Database;
use Monolog\Handler\StreamHandler;

/**
 * Users class to get users from the database
 * @package Model
 */
class User extends Model
{
    private $name;
    private $city;
    private $email;
    private $password;
    private $id;
    protected $db;
    private $role;
    private $logger;
    protected $table = 'users';
    protected $primaryKey = 'user_id';

    public function __construct(DDO $db = null)
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

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;

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
     * @return mixed
     */
    public function save()
    {
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        // Prepare data for insertion
        $data = [
            'name' => $this->name,
            'city' => $this->city,
            'email' => $this->email,
            'password' => $hashedPassword,
            'role_id' => 2,
            'status_id' => 1,
        ];

        if ($this->id) {
            // Update existing user
            return $this->update($data, $this->id);
        } else {
            // Insert new user
            return $this->insert($data);
        }
    }

    /**
     * Login funtion
     *
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function login($email, $password)
    {
        try {
            // Fetch user by email
            $user = $this->findByEmail($email);

            // Check if user exists
            if (!$user) {
                $_SESSION['login_error'] = 'Aaaahh!! this user is not found...';
                return false; // User not found
            }

            // Verify the provided password against the stored hash
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['login_success'] = 'Hey ' . $user['name'] . ', you are logged in successfully!';

                return true;
            } else {
                $_SESSION['login_error'] = 'Aaaahh!! Your password doesn\'t match...';
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
     * Validate the given password.
     *
     * This method checks if the provided password meets the required criteria.
     *
     * @param string $password The password to validate.
     * 
     * @return bool Returns true if the password is valid, false otherwise.
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
     * This function handles the process of initiating a password reset for a user.
     * It typically involves sending a password reset link to the user's email address.
     *
     * @param string $email The email address of the user who forgot their password.
     * @return bool Returns true if the password reset process was successfully initiated, false otherwise.
     **/
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
            $stmt->bindParam(1, $resetToken);
            $stmt->bindParam(2, $resetExpiration);
            $stmt->bindParam(3, $email);

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

        $_SESSION['wrong_email'] = "The email address does not exist, verify and try again";

        return false;
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

        return "$protocol$host$baseUrl$path";
    }


    /**
     * Check if the email already exists in the database.
     *
     * This method checks the database to determine if the provided email
     * address is already associated with an existing user.
     *
     * @param string $email The email address to check.
     * @return bool|void Returns true if the email exists, false otherwise.
     */
    private function emailExists($email)
    {
        // Prepare the SQL statement to fetch all users
        $sql = "SELECT * FROM users";

        // Execute the query
        $result = $this->db->query($sql);

        if ($result) {
            $users = $this->findAll();

            foreach ($users as $user) {
                if ($email == $user['email']) {
                return true;
                }
            }

            $_SESSION['wrong_email'] = "{$email} does not exist, please verify and try again...";
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
     * Get All users
     *
     * @return array
     */
    public static function getAll()
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
        $result = $this->findAll();

        if ($result) {
            $userNames = [];

            foreach ($result as $row) {
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

        $user = $this->findById($userId);
        if ($user) {
            return $user;
        }

        return null; // User not found or query failed
    }

    /**
     * Get a user by Email Address
     *
     * @param string $email
     * @return mixed
     */
    public function getUserByEmail($email)
    {
        // Prepare the SQL statement to retrieve user data by user_id
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);

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
    public function Oldupdate()
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
     * Check if the user is logged in
     *
     * @return boolean
     */
    public static function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }
}