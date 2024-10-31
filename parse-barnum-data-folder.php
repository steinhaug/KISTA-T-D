<?php

require 'vendor/autoload.php';
require 'credentials.php';


function process_directory($dir = './data-barnum/') {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            process_file($dir . $file);
        }
    }
}

function process_file($filepath) {
    $filename = basename($filepath);
    
    // Extract LLM and category from filename
    if (preg_match('/^barnum-([^-]+)-(.+)$/', $filename, $matches)) {
        $llm = $matches[1];
        $category = $matches[2];
    } else {
        echo "Invalid filename format: $filename\n";
        return;
    }
    
    $handle = fopen($filepath, "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            process_line($line, $llm, $category);
        }
        fclose($handle);
    } else {
        echo "Error opening file: $filepath\n";
    }
}

function process_line($line, $llm, $category) {
    $parts = explode('|', $line);
    if (count($parts) === 5) {
        $text = trim($parts[1]);
        $sex = trim($parts[2]);
        $score = trim($parts[3]);
        
        if ($text !== '' && $score !== '') {
            add_to_db($llm, $category, $sex, $score, $text);
        }
    } else {
        echo count($parts);
    }
}

function match_category($cat){
    $cat = strtolower($cat);
    if (str_contains($cat, 'career')) {
        $catid = 1;
    } else if (str_contains($cat, 'travel')) {
        $catid = 2;
    } else if (str_contains($cat, 'creativity')) {
        $catid = 3;
    } else if (str_contains($cat, 'personal')) {
        $catid = 4;
    } else if (str_contains($cat, 'health')) {
        $catid = 5;
    } else if (str_contains($cat, 'love')) {
        $catid = 6;
    } else if (str_contains($cat, 'intimacy')) {
        $catid = 7;
    } else if (str_contains($cat, 'sexuality')) {
        $catid = 8;
    }
    return $catid;
}

function match_llm($llm){
    $llm = strtolower($llm);

    if (str_contains($llm, 'chatgpt')) {
        $llmid = 1;
    } else if (str_contains($llm, 'claude')) {
        $llmid = 2;
    } else if (str_contains($llm, 'gemini')) {
        $llmid = 3;
    } else if (str_contains($llm, 'groq')) {
        $llmid = 4;
    }
    return $llmid;
}

function match_gender($sex){
    $sex = strtolower($sex);

    $gender = 0;
    if (str_contains($sex, 'male')) {
        $gender = 1;
    } else if (str_contains($sex, 'female')) {
        $gender = -1;
    } else if (str_contains($sex, 'man')) {
        $gender = 1;
    } else if (str_contains($sex, 'woman')) {
        $gender = -1;
    }
    return $gender;
}

function add_to_db($llm, $category, $sex, $score, $text) {

    global $mysqli;

    $llmId = match_llm($llm);
    $catId = match_category($category);
    $gender = match_gender($sex);

    // echo "Adding to DB: LLM: $llm, Category: $category, Sex: $sex, Score: $score, Text: $text\n";

    /*
    var_dump($llmId);
    var_dump($catId);
    var_dump($score);
    var_dump($gender);
    var_dump($text);
    //exit;
    */

    $sql = new sqlbuddy;
    $sql->que('llmId', $llmId, 'int');
    $sql->que('catId', $catId, 'int');
    $sql->que('score', $score, 'int');
    $sql->que('gender', $gender, 'int');
    $sql->que('statement', $text, 'string');
    // insert logic. ->build(cmd,table)
    $mysqli->query( $sql->build('insert', 'barnum_statements') );
    $sid = $mysqli->insert_id;
    echo '.';
    // update logic. ->build(cmd,table,identfier logic)
    //$success = $mysqli->query($sql->build('update', 'barnum_statements', 'bsId=' . $file_id));


}

// Start processing
process_directory();

