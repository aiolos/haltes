<?php

/**
 * Haltes
 *
 * PHP Version 5.5
 *
 * @category  Haltes
 * @package   Application\Form
 * @author    Peter Lammers <peter.lammers@mobiliteitsfabriek.nl>
 * @copyright 2015 Mobiliteitsfabriek
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL
 * @version   SVN: $Id$
 * @link      http://www.mobiliteitsfabriek.nl
 */

namespace Application\Download;

class OVapiDownloader
{

    public function downloadRemoteFile($fileUrl, $saveTo)
    {
        $content = file_get_contents($fileUrl);
        file_put_contents($saveTo, $content);
        return;
    }

    public function extractFile($zip, $name, $extractlocation)
    {
        $res = $zip->open($name);
        $zip->extractTo($extractlocation, "stops.txt");
        $zip->close();
        return;
    }
}
