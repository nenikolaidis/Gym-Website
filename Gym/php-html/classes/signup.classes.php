<?php
class Signup extends Dbh {

    public function createUser($name, $surname, $country, $state, $city, $address, $email, $username, $password) {
        // Check if the username already exist in pending_users
        if ($this->isUsernameTaken1($username)) {
            header("location: ../main/index.php?error=usernametaken");
            exit();
        }

        // Check if the email already exist in pending_users
        if ($this->isEmailTaken1($email)) {
            header("location: ../main/index.php?error=emailtaken");
            exit();
        }

        // Check if the username already exist in accepted_users
        if ($this->isUsernameTaken2($username)) {
            header("location: ../main/index.php?error=usernametaken");
            exit();
        }

        // Check if the email already exist in accepted_users
        if ($this->isEmailTaken2($email)) {
            header("location: ../main/index.php?error=emailtaken");
            exit();
        }

        $this->insertUser($name, $surname, $country, $state, $city, $address, $email, $username, $password);
    }

    protected function isUsernameTaken1($username) {
        $stmt = $this->connect()->prepare('SELECT username FROM pending_users WHERE username = ?');
        $stmt->execute([$username]);
        return $stmt->fetch() !== false;
    }

    protected function isEmailTaken1($email) {
        $stmt = $this->connect()->prepare('SELECT email FROM pending_users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
    }

    protected function isUsernameTaken2($username) {
        $stmt = $this->connect()->prepare('SELECT username FROM accepted_users WHERE username = ?');
        $stmt->execute([$username]);
        return $stmt->fetch() !== false;
    }

    protected function isEmailTaken2($email) {
        $stmt = $this->connect()->prepare('SELECT email FROM accepted_users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
    }

    
    protected function insertUser($name, $surname, $country, $state, $city, $address, $email, $username, $password) {
        $stmt = $this->connect()->prepare('
            INSERT INTO pending_users (name, surname, country, state, city, address, email, username, pass_word)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');

        if (!$stmt->execute(array($name, $surname, $country, $state, $city, $address, $email, $username, $password))) {
            $stmt = null;
            header("location: ../main/index.php?error=stmtfailed");
            exit();
        }

        $stmt = null;
        header("location: ../user/open.php?error=none");
        exit();
    }
}
