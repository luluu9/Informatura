<?php 
include_once("php/db_con.php");
error_reporting(-1); // reports all errors
ini_set("display_errors", "1"); // shows all errors
ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");
class Sheets {
    private $sheets_ids = array();
    private $sheets = array();
    function __construct() {
        $sheets_query = "SELECT id FROM arkusze ORDER BY rok DESC";
        $result = get_db()->query($sheets_query);
        if ($result) {
            while ($sheet_id = $result->fetch_assoc()) {
                if ($sheet_id) {
                    $this->sheets_ids[] = $sheet_id['id'];
                }
            }
        }
        else {
            return_404();
            console_log("Sheets ids error!");
        }
    }

    function get_sheets() {
        foreach($this->get_ids() as $sheet_id) {
            $this->sheets[] = new Sheet($sheet_id);
        }
        return $this->sheets;
    }

    function get_ids() {
        return $this->sheets_ids;
    }
}

class Sheet {
    public $sheet_id = 0;
    public $year = 0;
    public $description = "";
    private $problems = array();

    function __construct($sheet_id) {
        $this->sheet_id = $sheet_id;
        $args = ['i'=>$sheet_id];
        $sheets_query = "SELECT rok, opis FROM arkusze WHERE id = ?";
        $result = get_db()->query($sheets_query, $args);
        if ($result) {
            $sheet = $result->fetch_assoc();
            if ($sheet) { 
                $this->year=$sheet['rok'];
                $this->description=$sheet['opis'];
            }
            else {
                return_404();
            }
        }
        else {
            return_404();
        }
    }

    function get_problems() {
        if (!$this->problems) {
            $args=['i'=>$this->sheet_id];
            $problems_query = "SELECT id FROM zadania WHERE id_arkusza=? ORDER BY filename;";
            $result = get_db()->query($problems_query, $args);
            if ($result) {
                while ($problem_id = $result->fetch_assoc()) {
                    if ($problem_id) { 
                        $this->problems[] = new Problem($problem_id['id']);
                        }
                    }
                }
            else {
                console_log($this->sheet_id . " sheet error!");
                return_404();
            }
        }
        else {
            return_404();
        }
        return $this->problems;
    }

    function problem_exist() {
        if ($this->problems) { return True; }
        $args=['i'=>$this->sheet_id];
        $query = "SELECT id FROM zadania WHERE id_arkusza=? LIMIT 1;";
        $result = get_db()->query($query, $args);
        if ($result) {
            $problem = $result->fetch_assoc();
            if ($problem) { 
                return true;
            }
        }
        return false;
    }
}

class Problem extends Sheet {
    public $problem_id = 0;
    public $dirpath = "";
    public $filename = "";
    public $points = 0;
    public $problem_content = "";
    public $solution = "";
    public $modify_date = "";
    public $create_date = "";
    public $author = "";
    private $tags = array();
    private $main_problem = "";

    function __construct($problem_id, $problem_lines=null) {
        $this->problem_id = $problem_id;
        $args=['i'=>$this->problem_id];
        $problem_lines = is_numeric($problem_lines) ? "SUBSTRING_INDEX(tresc,'\n',{$problem_lines})" : "tresc";
        $problem_query = "SELECT arkusze.id, rok, opis, dirpath, filename, autor, punkty, {$problem_lines} as 'tresc', rozwiazanie, data_modyfikacji, data_utworzenia 
                          FROM zadania INNER JOIN arkusze ON zadania.id_arkusza=arkusze.id WHERE zadania.id=?;";
        $result = get_db()->query($problem_query, $args);
        if ($result) {
            $problem = $result->fetch_assoc();
            if ($problem) { 
                $this->dirpath=$problem['dirpath'];
                $this->filename=$problem['filename'];
                $this->points=$problem['punkty'];
                $this->problem_content=$problem['tresc'];
                $this->solution=$this->format($problem['rozwiazanie']);
                $this->modify_date=$problem['data_modyfikacji'];
                $this->create_date=$problem['data_utworzenia'];
                $this->author=$problem['autor'];
                if (!$this->year) { $this->year=$problem['rok']; }
                if (!$this->description) { $this->description=$problem['opis']; }
                if (!$this->sheet_id) { $this->sheet_id=$problem['id']; }
            }
            else {
                return_404();
            }
        }
        else {
            console_log($problem_id . " problem error!");
            return_404();
        }
    }

    function format($str) {
        $str = str_replace("{dirpath}", "/" . $this->dirpath, $str);
        return $str;
    }

    function get_image_path($custom_file="problem_image") {
        if ($custom_file="problem_image") { 
            $mainpath = $this->dirpath . $this->filename;
        }
        else {
            $mainpath = $this->dirpath . $custom_file;
        }
        if (file_exists($mainpath . ".png")) { return "/" . $mainpath . ".png"; }
        elseif (file_exists($mainpath . ".jpg")) { return "/" . $mainpath . ".jpg"; }
        else return "";
    }

    function get_tags() {
        if (!$this->tags) {
            $args=['i'=>$this->problem_id];
            $tags_query = "SELECT tag FROM tagi WHERE id_zadania=?;";
            $result = get_db()->query($tags_query, $args);
            if ($result) {
                while ($tag = $result->fetch_assoc()) {
                    if ($tag) { 
                        $this->tags[] = $tag['tag'];
                        }
                    }
                }
            else {
                console_log($this->problem_id . " tag error!");
            }
        }
        return $this->tags;
    }

    function get_main_problem($only_title=False) {
        if (!$this->main_problem) {
            $path = $this->dirpath . strtok($this->filename, ".") . "_glowne.txt";
            if (file_exists($path)) {
                $this->main_problem = file_get_contents($path);
            }
        }
        if ($only_title) { return strtok($this->main_problem, "\n"); }
        return $this->main_problem;
    }

}

function _GET($param, $type="s") {
    $value = array_key_exists($param, $_GET) ? $_GET[$param] : 0; # już jest urldecoded
    if ($type=="i") {
        $value = (is_numeric($value) ? $value+0 : 0);
    }
    elseif ($type=="s") {
        $value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
    }   
    return $value;
}

function get_tags_formatted($problem) {
    $tags = $problem->get_tags();
    $tags_html = "";
    foreach($tags as $tag) {
        $url_tag = urlencode($tag);
        $tags_html .=  "<object><a href='/rozwiazania?tag={$url_tag}'>" . $tag . "</a></object>"; # object po to, żeby <a><div></div></a> dzialalo dobrze
        if (next($tags)) { $tags_html .= ", "; }
    }
    return $tags_html;
}

function print_problem($problem) {
    $full_problem_content = $problem->get_main_problem() . $problem->problem_content;
    $title = $problem->get_main_problem($only_title=True);
    echo "
    <div class='problem'>
        <div class='problem-header main-width'>
            <div class='problem-title-container'>
                <span class='problem-title'><h3>Zadanie {$problem->filename} (0-{$problem->points})</h3></span>
                <span class='problem-subtitle'>{$title}</span>
            </div>
        <div class='" . (empty($title) ? "small-tags " : "") . "problem-tags'>".get_tags_formatted($problem)."</div>
        </div>
        <img src='{$problem->get_image_path()}' alt='{$full_problem_content}'/>
            
        <div class='problem-content'>
            <pre>{$problem->solution}</pre>
        </div>" . 
        (empty($problem->author) ? "" :         
        "<div class='problem-footer main-width' >
        Rozwiązanie nadesłane przez: {$problem->author}
        </div>") .
    "</div>";
}

function print_problems($result) {
    if ($result) {
        $problems_ids = $result->fetch_all(); 
        foreach ($problems_ids as $problem_id) {
            $problem = new Problem($problem_id[0], $problem_lines=10); /*desc lines nie dziala*/
            $url = "/zadanie/{$problem_id[0]}";
            echo "
            <div class='problem max-width'>
                <a href='{$url}'>
                    <div class='problem-header transition main-width'>
                        <div class='problem-title-container'>
                            <span class='problem-title'><h3>Matura {$problem->year}, {$problem->description}</h3></span>
                            <span class='problem-subtitle'>zadanie {$problem->filename}</span>
                        </div>
                        <div class='problem-tags'>".get_tags_formatted($problem)."</div>
                    </div>
                </a>
                <div class='problem-content'>
                    <pre>{$problem->problem_content}...</pre>
                    <a href='{$url}' class='read'>Czytaj dalej...</a>
                </div>
            </div>";
        }
        return $problems_ids ? true : false;
     }
}

function return_404() {
    http_response_code(404);
    include("404.php");
    die();
}

?>

     