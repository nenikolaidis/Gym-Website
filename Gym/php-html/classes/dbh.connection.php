<?php
    class Dbh{
        protected function connect(){
            //xaamp default credentials
            try{
                $username = "root";
                $password = "";
                $dbh = new PDO("mysql:host=localhost;dbname=project_db", $username, $password);
                return $dbh;
            }
            catch(PDOException $e){
                print "Error!: " .$e->getMessage(). "<br/>";
                die();
            }
        }
    }
?>