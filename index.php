<?php
require_once __DIR__ . './vendor/autoload.php';

// API GG Authenic Json Path
putenv('GOOGLE_APPLICATION_CREDENTIALS=./google-auth.json');

use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding;

/** Uncomment and populate these variables in your code */
$audioFile = 'data/audio32KHz.flac';

// change these variables if necessary
$encoding = AudioEncoding::FLAC;
$sampleRateHertz = 32000;
// Your language
$languageCode = 'vi-VN';

// get contents of a file into a string
$content = file_get_contents($audioFile);

// set string as audio content
$audio = (new RecognitionAudio())
    ->setContent($content);

// set config
$config = (new RecognitionConfig())
    ->setEncoding($encoding)
    ->setSampleRateHertz($sampleRateHertz)
    ->setLanguageCode($languageCode);

// create the speech client
$client = new SpeechClient();

try {
    $response = $client->recognize($config, $audio);
    foreach ($response->getResults() as $result) {
        $alternatives = $result->getAlternatives();
        $mostLikely = $alternatives[0];
        $transcript = $mostLikely->getTranscript();
        printf($transcript);
    }
} finally {
    $client->close();
}