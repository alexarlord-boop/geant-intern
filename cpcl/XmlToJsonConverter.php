<?php



// Example Usage:
$url = "https://attribute-viewer.aai.switch.ch/Shibboleth.sso/Metadata"; // Replace with your XML URL
$xmlParser = xml_parser_create();

// Fetch data from the URL
$xmlData = file_get_contents($url);
echo $xmlData;
$xml = simplexml_load_string($xmlData) or die("Error: Cannot create object");




// Load XML with namespace support
$xml = simplexml_load_string($xmlData, 'SimpleXMLElement', LIBXML_NOERROR | LIBXML_NOWARNING, 'md', true);

if ($xml === false) {
    echo "Failed loading XML\n";
    foreach(libxml_get_errors() as $error) {
        echo $error->message;
    }
} else {
    print_r($xml);
}

//$jsonResult = $xmlParser->parseXmlToJson();
//echo $jsonResult;

