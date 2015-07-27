<?php

/**
 * Haltes
 *
 * PHP Version 5.5
 *
 * @category  Haltes
 * @package   Application\Form
 * @author    Henri de Jong <henri.dejong@mobiliteitsfabriek.nl>
 * @author    Peter Lammers <peter.lammers@mobiliteitsfabriek.nl>
 * @copyright 2015 Mobiliteitsfabriek
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL
 * @version   SVN: $Id$
 * @link      http://www.mobiliteitsfabriek.nl
 */

class OVapi_downloader
{

    function __contruct()
    {

    }

    function download_remote_file($fileUrl, $saveTo)
    {
        $content = file_get_contents($fileUrl);
        file_put_contents($saveTo, $content);
        return;
    }

    function getfileUrl($operator)
    {
        return "http://gtfs.ovapi.nl/" . $operator . "/gtfs-kv1" . $operator . "-latest.zip";
    }

    function getSaveTo($operator)
    {
        return $operator."/".$operator.".zip";
    }

    function extractFile($name, $operator)
    {
        $zip = new ZipArchive();
        $res = $zip->open($name);
        $zip->extractTo('/home/peter/Documents/Stops/' . $operator . '/', 'stops.txt');
        $zip->close();
        return;
    }

}

$operators = array(
    "arriva",
    "connexxion",
    "ebs",
    "htm",
    "gvb",
    "qbuzz",
    "ret",
    "syntus",
    "veolia"
);

$ovapi = new OVapi_downloader;
foreach ($operators as $operator) {
    $ovapi->download_remote_file($ovapi->getfileUrl($operator), $ovapi->getSaveTo($operator));
    echo "downloaded: " . $operator . " stops.\n";
    echo $ovapi->getSaveTo($operator). "\n";
    $ovapi->extractFile($ovapi->getSaveTo($operator), $operator);
}