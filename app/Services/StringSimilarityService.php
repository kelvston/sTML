<?php

namespace App\Services;

use Spacy;

class StringSimilarityService
{
    public static function calculate($str1, $str2): float
    {
        // Use SpaCy's language model to compute semantic similarity
        $nlp = Spacy::load('en_core_web_md');

        // Convert both strings into SpaCy Doc objects
        $doc1 = $nlp->makeDoc($str1);
        $doc2 = $nlp->makeDoc($str2);

        // Calculate similarity between the two documents (semantic similarity)
        $similarity = $doc1->similarity($doc2);

        return round($similarity * 100, 1); // Convert to percentage
    }
}
