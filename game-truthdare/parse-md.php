<?php
function processTruthsAndFantasies($filename) {
    // Check if file exists before attempting to read
    if (!file_exists($filename)) {
        die("File not found.");
    }

    // Read the file line by line
    $file = fopen($filename, "r");
    $processedLines = [];

    while (($line = fgets($file)) !== false) {
        // Use regex to match and remove the leading "X. " where X is an integer
        $processedLine = preg_replace('/^\d+\.\s*/', '', trim($line));
        
        // Add the processed line to the array
        $processedLines[] = $processedLine;
    }

    fclose($file);

    return $processedLines; // Return an array of processed lines
}

// Example usage
$processedLines = processTruthsAndFantasies('truths-lv5-twilight.md');

file_put_contents('truths-lv5-twilight.txt', implode("\n", $processedLines));

print_r($processedLines); // Display the processed lines
