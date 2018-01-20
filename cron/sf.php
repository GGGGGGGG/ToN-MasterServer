<?php

include("../common/lib.php");

calculate_sf();

function calculate_sf()
{
    global $dbcon;
    $players = array();
    $query = "SELECT id FROM users WHERE updated_at >= (CURDATE() - INTERVAL 30 DAY)";
    $result = mysqli_query($dbcon, $query);

    while($row = mysqli_fetch_array($result))
    {
        $players = $row;
    }

    foreach($players as $player)
    {
        $query = "SELECT AVG(sf) as sf_avg from actionplayers where user = {$player}";
        $result = mysqli_query($dbcon, $query);
        $sf = mysqli_fetch_assoc($result);

        $query = "UPDATE playerinfos SET sf='{$sf['sf_avg']}' WHERE id='{$player}'";
        mysqli_query($dbcon, $query);
    }
}