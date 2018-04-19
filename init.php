<?php

use Catalog\Entity\XmlMapping;

$entityManager = require_once join(DIRECTORY_SEPARATOR, [__DIR__, 'config/bootstrap.php']);

echo "Populating XmlMapping table...".PHP_EOL;

$xmlMappingContent = json_decode(file_get_contents("init/XmlMapping.json"), true);

foreach ($xmlMappingContent as $xmlMappingLine) {

    $xmlMapping = new XmlMapping();
    $xmlMapping->setFileFormat($xmlMappingLine['fileFormat']);
    $xmlMapping->setObjectType($xmlMappingLine['objectType']);
    $xmlMapping->setFieldName($xmlMappingLine['fieldName']);
    $xmlMapping->setXmlFieldName($xmlMappingLine['xmlFieldName']);
    $xmlMapping->setGroupPath($xmlMappingLine['groupPath']);
    $xmlMapping->setSubPath($xmlMappingLine['subPath']);
    $xmlMapping->setLanguage($xmlMappingLine['language']);
    $xmlMapping->setReferenceTag($xmlMappingLine['referenceTag']);

    $entityManager->persist($xmlMapping);
    $entityManager->flush();

    unset($xmlMapping);
}

echo "XmlMapping table is ready!".PHP_EOL;
echo "Project is ready!".PHP_EOL;



