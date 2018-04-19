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

use Catalog\Entity\Song;
use Catalog\Helper\XmlParserHelper;

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
    $xmlParserHelper = new XmlParserHelper($xml, $entityManager);

    //Get fields to parse order by group paths
    $groupPaths = $xmlParserHelper->getXmlGroups($fileFormat, $fileLanguage);

    //Create album object - 1 album per file
    $album = $xmlParserHelper->createAlbumObject();

    foreach ($groupPaths as $groupPath) {/*
        print_r($groupPath['groupPath']);
        print_r("--------------\n");*/

        $fieldPaths = $xmlParserHelper->getFieldsToParseByGroup($fileFormat, $fileLanguage, $groupPath);

        $elements = $xml->xpath($groupPath['groupPath']);

        foreach ($elements as $element) {
            //Get Reference

            if ((string)$element->{$groupPath['referenceTag']} === "R0") { //Album Infos

                foreach ($fieldPaths as $path) {
                    if ($path['objectType'] === 'Album') {
                        $xmlParserHelper->setField($album, $path, $element);
                    }
                }

                $entityManager->persist($album);
                $entityManager->flush();
                echo "Album infos inserted" . PHP_EOL;
                print_r($album);
                echo "--------" . PHP_EOL;

            } else { //Song Infos

                $reference = (int)substr($element->{$groupPath['referenceTag']}, 1);

                $song = $xmlParserHelper->getSongObject($reference, $album);

                if (empty($song)) {
                    $song = $xmlParserHelper->createSongObject($reference, $album);
                }

                foreach ($fieldPaths as $path) { //get all data from this group

                    if ($path['fieldName'] === 'duration') {
                        $durationXML = (string)$element->{$path['xmlFieldName']};

                        $duration = XmlParserHelper::validateDuration((string)$element->{$path['xmlFieldName']});

                        $xmlParserHelper->setFieldWithFormattedValue($song, $path, $duration);

                    } else if ($path['subPath'] === NULL) {
                        $xmlParserHelper->setFieldWithoutSubPath($song, $path, $element);

                    } else if (isset($element->xpath($path['subPath'])[0]->{$path['xmlFieldName']}) && $path['objectType'] !== 'Album') {
                        $xmlParserHelper->setField($song, $path, $element);
                    }


                }
                $entityManager->persist($song);
                $entityManager->flush();
                echo "Song inserted" . PHP_EOL;
                print_r($song);
                echo "--------" . PHP_EOL;
                unset($song);
            }
        }
    }

    $entityManager->persist($album);
    $entityManager->flush();


} else {
    exit('Echec lors de l\'ouverture du fichier test.xml.');
}





// ------------------------------------------

echo '>>> END SCRIPT - Time: ', round((microtime(true) - $time), 2), 's <<<', PHP_EOL;


