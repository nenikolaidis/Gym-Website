<?php
class Login extends Dbh {

    public function loginUser($username, $password) {
        $this->getUser($username, $password);
    }
    
    protected function getUser($username, $password) {
        $stmt = $this->connect()->prepare('
            SELECT
                id, name, surname, username, pass_word, roles
            FROM accepted_users
            WHERE 
            username = ?
        ');

        if (!$stmt->execute(array($username))) {
            $stmt = null;
            header("location: ../main/index.php?error=stmtfailed");
            exit();
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // User not Found
        if (!$user) {
            $stmt = null;
            header("location: ../main/index.php?error=usernotfound");
            exit();
        }

        // Compare passwords
        if ($password !== $user['pass_word']) {
            $stmt = null;
            header("location: ../main/index.php?error=wrongpassword");
            exit();
        }

        // Password is correct; start session and store user data
        session_start();

        $_SESSION["user_id"] = $user['id'];
        $_SESSION["username"] = $user['username'];
        $_SESSION["roles"] = $user['roles'];

        // Redirect based on roles
        if ($user['roles'] == 'Admin') {
            header("location: ../admin/adminhome.php?error=none");
        } else {
            header("location: ../user/open.php?error=none");
        }

        exit();
    }
}
?>
