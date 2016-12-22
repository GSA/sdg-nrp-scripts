<?php

$arguments = arguments($argv);

require 'vendor/autoload.php';
use Symfony\Component\Yaml\Yaml;


// Load arguments passed from CLI 

if(empty($arguments['input'])) {
    echo "No input file path specified. Use --input=indicators/" . PHP_EOL . PHP_EOL; 
    exit;
}

if(!empty($arguments['output'])) {
    $output_path = $arguments['output'];
        
    if(!file_exists($output_path)) {
        echo "Creating output directory $output_path" . PHP_EOL . PHP_EOL;
        mkdir($output_path);
    }

} else {
    $output_path = '';
}


// Load list of markdown files
$markdown_files = scandir($arguments['input']);

$count = 0;

foreach ($markdown_files as $key => $filename) {

    if (strlen($filename) < 3 || substr($filename, -3) != '.md') { continue; }

    $file = file_get_contents($arguments['input'] . $filename);

    $parser = new Mni\FrontYAML\Parser();
    $document = $parser->parse($file, false);

    $yaml = $document->getYAML();
    $markdown = $document->getContent();
    
    $yaml_text = "---\n" . Yaml::dump($yaml) . "---\n";
    
    file_put_contents($output_path . '/' . $filename, $yaml_text);
    $count++;
}

if ($count > 0) {
    echo "$count files converted" . PHP_EOL . PHP_EOL;
}

function arguments($argv) {
    $_ARG = array();
    foreach ($argv as $arg) {
      if (preg_match('/--([^=]+)=(.*)/',$arg,$reg)) {
        $_ARG[$reg[1]] = $reg[2];
      } elseif(preg_match('/-([a-zA-Z0-9])/',$arg,$reg)) {
            $_ARG[$reg[1]] = 'true';
        }
  
    }
  return $_ARG;
}


?>