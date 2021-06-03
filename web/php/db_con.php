<?php
class Database {
    private $host;
    private $user;
    private $passwd;
    private $schema;
    private $db;
    
    function __construct($host='localhost', $user='root', $passwd='', $schema='informat_website') {
        $this->host=$host;
        $this->user=$user;
        $this->passwd=$passwd;
        $this->schema=$schema;
        $this->db = new mysqli($host, $user, $passwd, $schema);
        if (!is_null($this->db->connect_error)) {
            console_log('Connection failed');
            console_log('Error number: ' . $this->db->connect_errno);
            console_log('Error message: ' . $this->db->connect_error);
        }
        $this->db->set_charset("utf8mb4");
    }

    function query($query, $args=array()) {
        if (empty($args)) {  
            $result = $this->db->query($query);
            if (!$result) {
                console_log('Query error: ' . $this->db->error);
                return null;
            }
        }
        else {
            $prep = $this->db->prepare($query);
            if (!$prep) { console_log("Error in preparation: " . $prep->error); return null; }
            /* key=datatype, value=value for ? */
            foreach($args as $key=>$value) { 
                if (!$prep->bind_param($key, $value)) { console_log("Error in binding: " . $prep->error); return null; }
            }
            if (!$prep->execute()) { console_log("Error in executing: " . $prep->error); return null; }
            $result = $prep->get_result();
        }
        return $result;
    }
}

function console_log($output, $with_script_tags = true) {
//     $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
// ');';
//     if ($with_script_tags) {
//         $js_code = '<script>' . $js_code . '</script>';
//     }
//     echo $js_code;
    error_log($output);
}

function get_db() {
    global $db;
    return $db;
}

$db = new Database(); // set passwords to db here
$website = 'http://' . $_SERVER['SERVER_NAME'] . '/'
?>