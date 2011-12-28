<?php

$command = "git pull origin master";
exec($command, $output, $return);

print_r($output);

/* 

using github service hooks this auto updates the files. Neat huh?

http://net.tutsplus.com/tutorials/other/the-perfect-workflow-with-git-github-and-ssh/

Note: This had to be chmodded 644 on my webhost. Make sure you check that!

*/

?>