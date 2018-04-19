<?php

/*
------------------------------------------
Use:
php console.php [OPTIONS]

OPTIONS:
 -f, --file      Filename (with path) to treat


Example:
 php console.php --file=xml/825646618309.xml
------------------------------------------
*/

use Catalog\Helper\XmlToAlbumHelper;

//~ Report & Display all error
error_reporting(-1);
ini_set('display_errors', true);

//~ Set time limit to 10s
set_time_limit(10);

$entityManager = require_once join(DIRECTORY_SEPARATOR, [__DIR__, 'config/bootstrap.php']);

echo '>>> TEST CATALOG SCRIPT <<<', PHP_EOL;

$time = microtime(true);

//~ Preparse script arguments
require_once 'classes/Argument.php';
require_once 'classes/ArgumentIterator.php';
$arguments = \Deezer\Component\Console\Argument::getInstance();
$arguments->parse($argv);

//~ Example of use for class Argument
//~ Argument::get({SHORT_NAME}, {LONG_NAME}, [{DEFAULT_VALUE}]);
//~ $file = $arguments->get('f', 'file', 'xml/825646618309.xml');


// ------------------------------------------

$file = __DIR__ . '/' . $arguments->get('f', 'file', 'xml/825646618309.xml');
echo 'Process file: ' . $file . PHP_EOL;



// YOUR CODE HERE

//Open existing files

if (file_exists($file)) {
    $xml = simplexml_load_file($file);

    //Get file format
    $xmlAttributes = $xml->attributes();
    $fileFormat = (string) $xmlAttributes->MessageSchemaVersionId;
    $fileLanguage = (string) $xmlAttributes->LanguageAndScriptCode;

    //Call Helper
    $xmlToAlbumHelper = new XmlToAlbumHelper($xml, $entityManager);

    //Get groups of xml elements to parse
    $groupPaths = $xmlToAlbumHelper->getXmlGroups($fileFormat, $fileLanguage);

    //Create album object - 1 album per file
    $album = $xmlToAlbumHelper->createAlbumObject();

    foreach ($groupPaths as $groupPath) {

        //Get field list to parse in this group
        $fieldPaths = $xmlToAlbumHelper->getFieldsToParseByGroup($fileFormat, $fileLanguage, $groupPath);

        $elements = $xml->xpath($groupPath['groupPath']);

        foreach ($elements as $element) { //Go through group elements
            //Get Reference

            if ((string)$element->{$groupPath['referenceTag']} === "R0") { //Album Infos

                foreach ($fieldPaths as $path) { //Look for all fields
                    if ($path['objectType'] === 'Album') {
                        $xmlToAlbumHelper->setField($album, $path, $element);
                    }
                }

                $xmlToAlbumHelper->persistAndFlushAlbum($album);

                XmlToAlbumHelper::displayMessageAndDumpObject("Album infos inserted", $album);

            } else { //Song Infos

                $songNumber = (int)substr($element->{$groupPath['referenceTag']}, 1);

                //Get song object or create if it doesn't exist

                $song = $xmlToAlbumHelper->getSongObject($songNumber, $album);

                if (empty($song)) {
                    $song = $xmlToAlbumHelper->createSongObject($songNumber, $album);
                }

                foreach ($fieldPaths as $path) { //Look for all fields

                    if ($path['fieldName'] === 'duration') { //Duration - we need to parse it

                        $durationXML = (string)$element->{$path['xmlFieldName']};

                        $duration = XmlToAlbumHelper::validateDuration((string)$element->{$path['xmlFieldName']});

                        $xmlToAlbumHelper->setFieldWithFormattedValue($song, $path, $duration);

                    } else if ($path['subPath'] === "") { //No sub path
                        $xmlToAlbumHelper->setFieldWithoutSubPath($song, $path, $element);

                    } else if (isset($element->xpath($path['subPath'])[0]->{$path['xmlFieldName']}) && $path['objectType'] !== 'Album') {
                        $xmlToAlbumHelper->setField($song, $path, $element);
                    }


                }
                
                $xmlToAlbumHelper->persistAndFlushSong($song);

                XmlToAlbumHelper::displayMessageAndDumpObject("Song inserted", $song);
                unset($song);
            }
        }
    }


} else {
    exit('Echec lors de l\'ouverture du fichier test.xml.');
}





// ------------------------------------------

echo '>>> END SCRIPT - Time: ', round((microtime(true) - $time), 2), 's <<<', PHP_EOL;


