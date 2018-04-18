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

use Catalog\Entity\XmlPath;
use Catalog\Entity\Song;
use Catalog\Entity\Album;

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

if (file_exists('xml/825646618309.xml')) {
    $xml = simplexml_load_file('xml/825646618309.xml');


    //Get fields to parse order by group paths
    $qb = $entityManager->createQueryBuilder();

    $qb->select('distinct xp.groupPath')
        ->from(XmlPath::class, 'xp')
        ->where('xp.fileName = :fileName')
        ->orderBy('xp.groupPath')
        ->groupBy('xp.groupPath')
        ->setParameter('fileName','825646618309.xml');

    $groupPaths = $qb->getQuery()->getScalarResult();

    $album = new Album();
    $entityManager->persist($album);

    foreach ($groupPaths as $groupPath) {/*
        print_r($groupPath['groupPath']);
        print_r("--------------\n");*/


        $qb = $entityManager->createQueryBuilder();

        $qb->select('xp.subPath, xp.xmlFieldName, xp.fieldName, xp.objectType')
            ->from(XmlPath::class, 'xp')
            ->where('xp.fileName = :fileName')
            ->andWhere('xp.groupPath = :groupPath')
            ->setParameter('fileName','825646618309.xml')
            ->setParameter('groupPath', $groupPath['groupPath']);

        $fieldPaths = $qb->getQuery()->getResult();
        $elements = $xml->xpath($groupPath['groupPath']);
        $position = 1;

        $struct = explode('/',$groupPath['groupPath']);
        $referenceTag = str_replace('List','Reference', $struct[0]);
        print_r($referenceTag);

        foreach ($elements as $element) {
            //Get Reference

            $reference = (int)substr($element->{$referenceTag}, 1);

            $song = $entityManager
                ->getRepository(Song::class)
                ->findOneBy(array(
                    'songNumber'=>$reference,
                    'album' => $album));

            if (empty($song)) {
                $song = new Song();
                $song->setSongNumber($reference);
                $song->setAlbum($album);
            }

            foreach($fieldPaths as $path) { //get all data from this group


                if (isset($element->xpath($path['subPath'])[0]->{$path['xmlFieldName']})) {

                    //insert in database
//                    print_r((string)$element->xpath($path['subPath'])[0]->{$path['xmlFieldName']} . "\n");
//                    print_r("....".$reference . "\n");

                    if ($path['objectType'] === 'Song') {
                        $song->{"set" . ucfirst($path['fieldName'])}((string)$element->xpath($path['subPath'])[0]->{$path['xmlFieldName']});
                    } else if ($path['objectType'] === 'Album') {
                        $album->{"set" . ucfirst($path['fieldName'])}((string)$element->xpath($path['subPath'])[0]->{$path['xmlFieldName']});
                    }


                }


            }
            $entityManager->persist($song);
            $entityManager->flush();

            unset($song);
        }
    }
    $entityManager->persist($album);
    $entityManager->flush();
    

} else {
    exit('Echec lors de l\'ouverture du fichier test.xml.');
}





// ------------------------------------------

echo '>>> END SCRIPT - Time: ', round((microtime(true) - $time), 2), 's <<<', PHP_EOL;


