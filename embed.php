<?php
include 'initial.min.php';
echo '<script src="/library/jquery-1.9.1.min.js" type="text/javascript"></script>';
$content = file_get_contents(conf('dir').$_GET['f']);
$content = preg_replace('/(<a href[^<>]+)>/is', '\\1 onclick="$(\'#backtoblog\', top.document).show();">', $content);

echo $content;
?>