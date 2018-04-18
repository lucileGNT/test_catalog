<?php

namespace Catalog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * xml_path table. References all Xml paths depending on file name
 *
 * @ORM\Entity @ORM\Table(name="xml_path")
 **/


class XmlPath
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
    private $fileName;
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $objectType;
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $fieldName;
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $xmlFieldName;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $groupPath;


    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $subPath;

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
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getObjectType()
    {
        return $this->objectType;
    }

    /**
     * @param string $objectType
     */
    public function setObjectType($objectType)
    {
        $this->objectType = $objectType;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @param string $fieldName
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    /**
     * @return string
     */
    public function getGroupPath()
    {
        return $this->groupPath;
    }

    /**
     * @param string $groupPath
     */
    public function setGroupPath($groupPath)
    {
        $this->groupPath = $groupPath;
    }

    /**
     * @return string
     */
    public function getSubPath()
    {
        return $this->subPath;
    }

    /**
     * @param string $subPath
     */
    public function setSubPath($subPath)
    {
        $this->subPath = $subPath;
    }

    /**
     * @return string
     */
    public function getXmlFieldName()
    {
        return $this->xmlFieldName;
    }

    /**
     * @param string $xmlFieldName
     */
    public function setXmlFieldName($xmlFieldName)
    {
        $this->xmlFieldName = $xmlFieldName;
    }

}