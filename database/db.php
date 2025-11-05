<?php

$connection = mysqli_connect("localhost","root","","pharmacy") ;

if (!$connection)
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


