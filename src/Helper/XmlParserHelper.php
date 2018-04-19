<?php

namespace Catalog\Helper;

use Catalog\Entity\XmlMapping;
use Catalog\Entity\Album;
use Catalog\Entity\Song;

/** Functions useful to work on xml
 * @author Lucile Gentner
 */

class XmlParserHelper
{
    private $xml;
    private $entityManager;

    public function __construct($xml, $entityManager)
    {
        $this->xml = $xml;
        $this->entityManager = $entityManager;
    }

    public function getXmlGroups($fileFormat, $fileLanguage)
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('distinct xp.groupPath, xp.referenceTag')
            ->from(XmlMapping::class, 'xp')
            ->where('xp.fileFormat = :fileFormat')
            ->andWhere('xp.language = :language')
            ->orderBy('xp.groupPath, xp.referenceTag')
            ->groupBy('xp.groupPath, xp.referenceTag')
            ->setParameter('fileFormat',$fileFormat)
            ->setParameter('language',$fileLanguage);

        return $qb->getQuery()->getResult();
    }

    public function getFieldsToParseByGroup($fileFormat, $fileLanguage, $groupPath)
    {

        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('xp.subPath, xp.xmlFieldName, xp.fieldName, xp.objectType')
            ->from(XmlMapping::class, 'xp')
            ->where('xp.fileFormat = :fileFormat')
            ->andWhere('xp.language = :language')
            ->andWhere('xp.groupPath = :groupPath')
            ->setParameter('fileFormat',$fileFormat)
            ->setParameter('language',$fileLanguage)
            ->setParameter('groupPath', $groupPath['groupPath']);

        return $qb->getQuery()->getResult();
    }

    public function createAlbumObject()
    {

        $album = new Album();
        $this->entityManager->persist($album);
        return $album;
    }

    public function getSongObject($reference, $album)
    {
        return $this->entityManager
            ->getRepository(Song::class)
            ->findOneBy(array(
                'songNumber' => $reference,
                'album' => $album));
    }

    public function createSongObject($reference, $album)
    {
        $song = new Song();
        $song->setSongNumber($reference);
        $song->setAlbum($album);

        return $song;
    }

    public function setField($object, $path, $element)
    {
        $object->{"set" . ucfirst($path['fieldName'])}((string)$element->xpath($path['subPath'])[0]->{$path['xmlFieldName']});
    }

    public function setFieldWithoutSubPath($object, $path, $element)
    {
        $object->{"set" . ucfirst($path['fieldName'])}((string)$element->{$path['xmlFieldName']});
    }

    public function setFieldWithFormattedValue($object, $path, $value)
    {
        $object->{"set" . ucfirst($path['fieldName'])}($value);
    }

    public function persistAndFlushAlbum($album)
    {
        $this->entityManager->persist($album);
        $this->entityManager->flush();
    }

    public function persistAndFlushSong($song)
    {
        $this->entityManager->persist($song);
        $this->entityManager->flush();
    }


    /** Parses ISO 8601 format
     * Return seconds
     */
    public static function validateDuration($duration)
    {
        if (preg_match('/^PT(\d{1,2})M(\d{1,2}).(\d{1,3})S$/', $duration, $parts) == true) {
            $durationSecond = $parts[1]*60 + $parts[2].'.'.$parts[3];
            return $durationSecond;
        } else {
            return false;
        }
    }

    public static function displayMessageAndDumpObject($message, $object){

        echo $message . PHP_EOL;
        print_r($object);
        echo "--------" . PHP_EOL;

    }
    
}