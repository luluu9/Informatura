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

    function is_assoc(array $arr) {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
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
            if ($this->is_assoc($args)) {
                /* key=datatype, value=value for ? */
                foreach($args as $key=>$value) { 
                    if (!$prep->bind_param($key, $value)) { console_log("Error in binding: " . $prep->error); return null; }
                }
            }
            else {
                # for more than one argument
                $args_types = $args[0];
                $args_values = array_slice($args, 1);
                if (!$prep->bind_param($args_types, ...$args_values)) { console_log("Error in binding: " . $prep->error); return null; }
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

$db = new Database(); // set passwords to db here on deploy
$website = 'http://' . $_SERVER['SERVER_NAME'] . '/'
?>