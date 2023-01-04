<?php

class UserSession
{
    public static function authenticate($user, $pass)
    {
        $username = User::login($user, $pass);
        if ($username) {
            $userobj = new User($username);
            $conn = Database::getConnection();
            $ip = $_SERVER['REMOTE_ADDR'];
            $fingerprint = $_POST['fingerprint'];
            $agent = $_SERVER['HTTP_USER_AGENT'];
            $token = md5(rand(0, 9999999).$ip.$agent.time());
            $sql = "INSERT INTO `session` (`uid`, `token`, `login_time`, `ip`, `user_agent`, `active`,`fingerprint`)
            VALUES ('$userobj->id', '$token', now(), '$ip', '$agent', '1', '$fingerprint')";

            if ($conn->query($sql)) {
                Session::set('session_token', $token);
                return $token;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function authorize($token)
    {
        try {
            $session = new UserSession($token);
            if (isset($_SERVER['REMOTE_ADDR']) and isset($_SERVER['HTTP_USER_AGENT'])) {
                if ($session->isActive() and $session->isValid()) {
                    if ($_SERVER['REMOTE_ADDR']==$session->getIp() and $_SERVER['HTTP_USER_AGENT'] == $session->getAgent()) {
                        if ($_POST['fingerprint']== $session->getFingerprint()) {
                            return true;
                        } else {
                            throw new Exception("fingerprint doesnt  match");
                        }
                    } else {
                        throw new Exception("user agent or ip doesnt match");
                    }
                } else {
                    $session->removeSession();
                    throw new Exception("Invalid session");
                }
            } else {
                throw new Exception("Ip / user agent is null");
            }
        } catch(Exception $e) {
            return false;
        }
    }


    public function __construct($token)
    {
        $this->conn = Database::getConnection();
        $this->token = $token;
        $this->data = null;
        $sql = "SELECT * FROM `session` WHERE `token` = '$token' LIMIT 1";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->data = $row;
            $this->uid = $row['uid'];
        } else {
            throw new Exception("Session is invalid.");
        }
    }

    public function getUser()
    {
        return new User($this->uid);
    }

    public function isValid()
    {
        if (isset($this->data['login_time'])) {
            $login_time = DateTime::createFromFormat('Y-m-d H:i:s', $this->data['login_time']);
            if (3600 > time() - $login_time->getTimestamp()) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function getIp()
    {
        return isset($this->data["ip"]) ? $this->data["ip"] : false;
    }

    public function getFingerprint()
    {
        return isset($this->data['fingerprint']) ? $this->data['fingerprint'] : false;
    }

    public function getAgent()
    {
        return isset($this->data["user_agent"]) ? $this->data["user_agent"] : false;
    }

    public function deactivate()
    {
        if (!$this->conn) {
            $this->conn = Database::getConnection();
        }
        $sql = "UPDATE `session` SET `active` = 0 WHERE `uid`=$this->uid";

        return $this->conn->query($sql) ? true : false;
    }

    public function isActive()
    {
        if (isset($this->data['active'])) {
            return $this->data['active'] ? true : false;
        }
    }

    public function removeSession()
    {
        if (isset($this->data['id'])) {
            $id = $this->data['id'];
            if (!$this->conn) {
                $this->conn = Database::getConnection();
            }
            $sql = "DELETE FROM `session` WHERE `id` = $id;";
            if ($this->conn->query($sql)) {
                return true;
            } else {
                return false;
            }
        }
    }
}
