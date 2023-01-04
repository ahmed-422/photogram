<?php

class User
{
    private $conn;

    public function __call($name, $arguments)
    {
        $property = preg_replace("/[^0-9a-zA-Z]/", "", substr($name, 3));
        $property = strtolower(preg_replace('/\B([A-Z])/', '_$1', $property));
        if (substr($name, 0, 3)== "get") {
            return $this->_get_data($property);
        } elseif (substr($name, 0, 3)== "set") {
            return $this->_set_data($property, $arguments[0]);
        } else {
            throw new Exception("User::__call() -> $name, function unavailable.");
        }
    }

    public static function signup($user, $pass, $email, $phone)
    {
        $option = ['cost' => 9, ];

        $pass = password_hash($pass, PASSWORD_BCRYPT, $option);
        $conn = Database::getConnection();

        $sql = "INSERT INTO `auth` (`username`, `password`, `email`, `phone`, `blocked`, `active`)
        VALUES ('$user', '$pass', '$email', '$phone', '0', '1');";

        $error = false;
        try {
            if ($conn->query($sql) === true) {
                echo "New record created successfully";
                $error = false;
            }
        } catch (Exception $e) {
            // echo "Error: " . $sql . "<br>" . $conn->error;
            $error = $conn->error;
        }
            //$conn->close();
        return $error;
    }

    public static function login($user, $pass)
    {
        $sql = "SELECT * FROM `auth` WHERE `email` = '$user' OR `username` = '$user'";
        $conn = Database::getConnection();

        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row =$result->fetch_assoc();
            // if ($row['password'] == $pass) {
            if (password_verify($pass, $row['password'])) {
                // print("verifiy success");
                return $row['username'];
            } else {
                // print("verify failed");
                return false;
            }
        } else {
            return false;
        }
    }

    public function __construct($username)
    {
        //code to fetch user data from database for the given username or userid
        $this->conn = Database::getConnection();
        $this->username = $username;
        $sql = "SELECT `id` FROM `auth` WHERE `username` = '$username' OR `id` = '$username' LIMIT 1";

        try {
            if ($this->conn->query($sql) == true) {
                $result = $this->conn->query($sql);
            }
        } catch(Exception $e) {
            $error = $this->conn->error;
            print("<br>".$error."<br>");
        }
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->id = $row['id']; //updating this from database
            // print("worked<br>");
            // print($this->id);
        }
    }

    //this function is used to retrieve data from database
    private function _get_data($var)
    {
        if (!$this->conn) {
            $this->conn =Database::getConnection();
        }
        $sql = "SELECT $var FROM users WHERE id = $this->id";


        try {
            if ($this->conn->query($sql) == true) {
                $result = $this->conn->query($sql);
            }
        } catch (Exception $e) {
            $error = $this->conn->error;
        }
        if ($result->num_rows) {
            return $result->fetch_assoc()["$var"];
        } else {
            return null;
        }
    }

    //this fuction is used to set the data in the database
    private function _set_data($var, $data)
    {
        if (!$this->conn) {
            $this->conn =Database::getConnection();
        }
        $sql = "UPDATE users SET $var = $data WHERE id = $this->id";
        if ($this->conn->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function setDob($year, $month, $day)
    {
        if (checkdate($month, $day, $year)) {//checking if date is valid
            return $this->_set_data('dob', "$year.$month.$day");
        } else {
            return false;
        }
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function authenticate()
    {
    }
}
