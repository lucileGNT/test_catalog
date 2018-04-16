<?php
namespace Catalog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Song table
 *
 * @ORM\Entity @ORM\Table(name="song")
 **/

class Song
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $songNumber;
    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $title;
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $isrc;
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $mainArtist;
    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $duration;
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $genre;
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $label;
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $distributionRights;
    /**
     * @ORM\ManyToOne(targetEntity=Album::class, inversedBy="songs")
     */
    private $album;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getSongNumber()
    {
        return $this->songNumber;
    }

    /**
     * @param int $songNumber
     */
    public function setSongNumber($songNumber)
    {
        $this->songNumber = $songNumber;
    }

    /**
     * @return int
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param int $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getIsrc()
    {
        return $this->isrc;
    }

    /**
     * @param string $isrc
     */
    public function setIsrc($isrc)
    {
        $this->isrc = $isrc;
    }

    /**
     * @return string
     */
    public function getMainArtist()
    {
        return $this->mainArtist;
    }

    /**
     * @param string $mainArtist
     */
    public function setMainArtist($mainArtist)
    {
        $this->mainArtist = $mainArtist;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param string $genre
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getDistributionRights()
    {
        return $this->distributionRights;
    }

    /**
     * @param string $distributionRights
     */
    public function setDistributionRights($distributionRights)
    {
        $this->distributionRights = $distributionRights;
    }

    /**
     * @return mixed
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @param mixed $album
     */
    public function setAlbum($album)
    {
        $this->album = $album;
    }
}