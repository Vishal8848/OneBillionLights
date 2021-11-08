<?php

    # MySQLi Reporting
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    class DB {

        private $DBH;   # Database Handle
        
        public function __construct($host = 'localhost', $user = 'root', $passwd = '', $dbname = 'test')    {
            
            # Assign Database Handle
            $this->DBH = new mysqli($host, $user, $passwd, $dbname);

            # Check DB Connection
            if($this->DBH->connect_errno) die("\nConnection Failed : " . $this->DBH->connect_error);
        }

        # Delete User
        private function deleteUser(string $email) : bool {

            $SQL = $this->DBH->prepare("DELETE FROM USERS WHERE EMAIL = ?");
            $SQL->bind_param("s", $email);
            return $SQL->execute();
        }

        # Fetch Password
        private function getPassword(string $email) : string {

            $SQL = $this->DBH->prepare("SELECT PASSWORD FROM USERS WHERE EMAIL = ?");
            $SQL->bind_param('s', $email);
            $SQL->execute();
            return $SQL->get_result()->fetch_assoc()['PASSWORD'];
        }

        # Fetch Device Info
        private function getDevice() : array {

            return array( 
                'parent' => get_browser(null, true)['parent'], 
                'platform' => get_browser(null, true)['platform'], 
                'address' => getenv('REMOTE_ADDR')
            );
        }

        # Check User Record
        public function checkUser(string $email) : int {
            
            $SQL = $this->DBH->prepare("SELECT COUNT(*) AS COUNT FROM USERS WHERE EMAIL = ?");
            $SQL->bind_param("s", $email);
            $SQL->execute();
            return $SQL->get_result()->fetch_assoc()['COUNT'];
        }

        # Add New User
        public function addUser(string $fname, string $lname, string $email, string $passwd) : bool   {

            $SQL = $this->DBH->prepare("INSERT INTO USERS(FNAME, LNAME, USERNAME, EMAIL, PASSWORD) VALUES(?, ?, ?, ?, ?)");
            $SQL->bind_param("sssss", $fname, $lname, explode('@', $email)[0], $email, $passwd);
            return $SQL->execute();
        }

        # Fetch User Credentials
        public function fetchUser(string $email) : array {

            $SQL = $this->DBH->prepare("SELECT ID, PASSWORD, VERIFIED FROM USERS WHERE EMAIL = ?");
            $SQL->bind_param("s", $email);
            $SQL->execute();
            return $SQL->get_result()->fetch_assoc();
        }

        # Check User Verification Status
        public function inspectUser(string $email) : bool {

            $SQL = $this->DBH->prepare("SELECT VERIFIED FROM USERS WHERE EMAIL = ?");
            $SQL->bind_param("s", $email);
            $SQL->execute();
            return $SQL->get_result()->fetch_assoc()['VERIFIED'];
        }

        # Set User Verification TRUE
        public function verifyUser(string $email) : bool {
            
            $SQL = $this->DBH->prepare("UPDATE USERS SET VERIFIED = 1, VERIFIED_AT = now() WHERE EMAIL = ?");
            $SQL->bind_param("s", $email);
            return $SQL->execute();
        }

        # Count Total User Records
        public function countUsers() : int {

            $SQL = $this->DBH->prepare("SELECT COUNT(*) AS COUNT FROM USERS");
            $SQL->execute();
            return $SQL->get_result()->fetch_assoc()['COUNT'];
        }

        # Update User Credentials
        public function updateUser(string $fname, string $lname, string $email, string $passwd) : bool {

            return $this->deleteUser($email) ? $this->addUser($fname, $lname, $email, $passwd) : 0;
        }

        # Change User Password
        public function changePassword(string $email, string $passwd) : bool {

            $device = $this->getDevice();
            $oldpasswd = $this->getPassword($email);
            $SQL = $this->DBH->prepare("UPDATE USERS SET PASSWORD = ? WHERE EMAIL = ?");
            $SQL->bind_param('ss', $passwd, $email);
            if($SQL->execute()) {
                $SQL->close();
                $SQL = $this->DBH->prepare("INSERT INTO PASSWORD_RESETS(USER_ID, OLD_PASSWORD, NEW_PASSWORD, PARENT, PLATFORM, DEVICE) VALUES((SELECT ID FROM USERS WHERE EMAIL = ?), ?, ?, ?, ?, ?)");
                $SQL->bind_param('ssssss', $email, $oldpasswd, $passwd, $device['parent'], $device['platform'], $device['address']);
                return $SQL->execute();
            }   return FALSE;
        }

        # Manage Login Instance
        public function setLogin(string $email) : bool {

            $session = session_id() ?? 'none';
            $device = $this->getDevice();
            $SQL = $this->DBH->prepare("INSERT INTO USER_SESSIONS(USER_ID, CREDENTIAL, SESSION_ID, PARENT, PLATFORM, DEVICE) VALUES((SELECT ID FROM USERS WHERE EMAIL = ?), ?, ?, ?, ?, ?)");
            $SQL->bind_param('ssssss', $email, explode('@', $email)[0], $session, $device['parent'], $device['platform'], $device['address']);
            return $SQL->execute();
        }

        # Record OTP Requests
        public function otpRequest(string $email, string $session = 'none', string $OTP) : bool {
            
            $device = $this->getDevice();
            $SQL = $this->DBH->prepare("INSERT INTO OTP_REQUESTS(CLIENT_EMAIL, SESSION_ID, OTP, PARENT, PLATFORM, DEVICE) VALUES(?, ?, ?, ?, ?, ?)");
            $SQL->bind_param('ssssss', $email, $session, $OTP, $device['parent'], $device['platform'], $device['address']);
            return $SQL->execute();
        }

    }

    $dbconfig = require_once('config.php');

    # Initialize Database Handle
    $OBL = new DB($dbconfig['hostname'], $dbconfig['username'], $dbconfig['password'], $dbconfig['database']);

?>