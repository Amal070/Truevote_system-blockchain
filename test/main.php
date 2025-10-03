<?php
// Run python script
$output = shell_exec("python output.py 2>&1");

// Show the result on browser
echo "<pre>$output</pre>";
?>
