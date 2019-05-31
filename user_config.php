<?php
class User 
{
    private $dbHost     = "localhost";
    private $dbUsername = "root";
    private $dbPassword = "";
    private $dbName     = "userinfo";
    private $userTbl    = 'users';
    private $db;
    function __construct()
    {
        if(!isset($this->db))
        {
            // Connect to the database
            $conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
            if($conn->connect_error){
                die("Failed to connect with MySQL: " . $conn->connect_error);
            }else{
                $this->db = $conn;
            }
        }
    }

    function checkUser($userData = array())
    {
        if(!empty($userData)){
            // Check whether user data already exists in database
            $prevQuery = "SELECT * FROM ".$this->userTbl." 
                          WHERE oauth_provider = '".$userData['oauth_provider']."'
                          AND oauth_uid = '".$userData['oauth_uid']."' "; 
            $prevResult = $this->db->query($prevQuery);

            if($prevResult->num_rows > 0)
            {
                $query = "UPDATE ".$this->userTbl." SET 
                first_name = ".$userData['first_name'].
                ",last_name = ".$userData['last_name'].
                ",email = ".$userData['email'].
                ",picture = ".$userData['picture'].
                ",modified = NOW() 
                WHERE oauth_provider =".$userData['oauth_provider'].
                "AND oauth_uid =".$userData['oauth_uid'];

                $update = $this->db->query($query);
            }
            else
            { 
           $query = "INSERT INTO ".$this->userTbl." (oauth_provider, oauth_uid, first_name, last_name, email, picture, created, modified)
                  "." VALUES('".$userData['oauth_provider']."',
                  '".$userData['oauth_uid']."',
                  '".$userData['first_name']."',
                  '".$userData['last_name']."', 
                  '".$userData['email']."', 
                  '".$userData['picture']."', 
                  NOW(),NOW())";
                  printf($query."<br>");

        if ($this->db->query($query) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $query . "<br>" . $this->db->error;
        }
            }
            // Get user data from the database
            $result = $this->db->query($prevQuery);

            $userData = $result->fetch_assoc();
            // print_r('user:'.$userData.":no data");
        }

        return $userData;
    }

}
?>