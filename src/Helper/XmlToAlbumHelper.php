<?php

namespace Catalog\Helper;

use Catalog\Entity\XmlMapping;
use Catalog\Entity\Album;
use Catalog\Entity\Song;

/**
 * Functions useful to insert xml albums/songs in database
 * @author Lucile Gentner
 */

class XmlToAlbumHelper
{
    private $xml;
    private $entityManager;

    public function __construct($xml, $entityManager)
    {
        $this->xml = $xml;
        $this->entityManager = $entityManager;
    }

    /**
     * Get all Xml groups to parse
     */
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

    /**
     * Get all fields to parse in one specific group
     */
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

    /**
     * Creates an album object
     */
    public function createAlbumObject()
    {

        $album = new Album();
        $this->entityManager->persist($album);
        return $album;
    }

    /**
     * Get a song object from its reference and album
     */
    public function getSongObject($reference, $album)
    {
        return $this->entityManager
            ->getRepository(Song::class)
            ->findOneBy(array(
                'songNumber' => $reference,
                'album' => $album));
    }

    /**
     * Creates a song object
     */
    public function createSongObject($reference, $album)
    {
        $song = new Song();
        $song->setSongNumber($reference);
        $song->setAlbum($album);

        return $song;
    }

    /**
     * Set a field in database with the data parsed in XML
     */
    public function setField($object, $path, $element)
    {
        $object->{"set" . ucfirst($path['fieldName'])}((string)$element->xpath($path['subPath'])[0]->{$path['xmlFieldName']});
    }

    /**
     * Set a field in database with the data parsed in XML - when no subPath
     */
    public function setFieldWithoutSubPath($object, $path, $element)
    {
        $object->{"set" . ucfirst($path['fieldName'])}((string)$element->{$path['xmlFieldName']});
    }

    /**
     * Set a field in database with the data parsed in XML - when the data has been formatted before
     */
    public function setFieldWithFormattedValue($object, $path, $value)
    {
        $object->{"set" . ucfirst($path['fieldName'])}($value);
    }

    /**
     * Persists and flush an album
     */
    public function persistAndFlushAlbum($album)
    {
        $this->entityManager->persist($album);
        $this->entityManager->flush();
    }

    /**
     * Persists and flush a song
     */
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