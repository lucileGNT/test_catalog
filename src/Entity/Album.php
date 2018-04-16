<?php

namespace Catalog\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Album table
 *
 * @ORM\Entity @ORM\Table(name="album")
 **/

class Album
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $icpn;
    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $grid;
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $mainArtist;
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
     * @ORM\OneToMany(targetEntity=Song::class, cascade={"persist", "remove"}, mappedBy="album")
     */
    protected $songs;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIcpn()
    {
        return $this->icpn;
    }

    /**
     * @param string $icpn
     */
    public function setIcpn($icpn)
    {
        $this->icpn = $icpn;
    }

    /**
     * @return int
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * @param int $grid
     */
    public function setGrid($grid)
    {
        $this->grid = $grid;
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
     * @return Collection Song
     */
    public function getSongs()
    {
        return $this->songs;
    }

    /**
     * @param Song $songs
     */
    public function addSong($song)
    {
        $this->songs->add($song);
        $song->setAlbum($this);
    }
}