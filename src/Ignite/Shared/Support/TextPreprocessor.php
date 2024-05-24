<?php

namespace Ignite\Shared\Support;

use Illuminate\Support\Str;

class TextPreprocessor
{
    /**
     * The stop words used by the preprocessor, stored as keys for quick lookup.
     *
     * @var array
     */
    protected array $stopWords = [];

    /**
     * Create a new TextPreprocessor instance and initialize stopwords.
     *
     * @param array $stopWords
     *
     * @return void
     */
    public function __construct(array $stopWords = [])
    {
        $this->setStopWords($stopWords);
    }

    /**
     * Preprocess the given text by normalizing, converting to lowercase,
     * removing punctuation, and removing stopwords.
     *
     * @param string $text
     *
     * @return string
     */
    public function preprocess(string $text): string
    {
        $text = $this->normalize($text);
        $text = $this->toLowercase($text);
        $text = $this->removePunctuation($text);
        $text = $this->removeStopwords($text);
        $text = $this->normalizeWhitespace($text);

        return $text;
    }

    /**
     * Normalize the text by converting it to ASCII.
     *
     * @param string $text
     *
     * @return string
     */
    protected function normalize(string $text): string
    {
        return Str::ascii($text);
    }

    /**
     * Convert the text to lowercase using a robust multibyte function.
     *
     * @param string $text
     *
     * @return string
     */
    protected function toLowercase(string $text): string
    {
        return Str::lower($text);
    }

    /**
     * Remove punctuation from the text using Unicode properties.
     *
     * @param string $text
     *
     * @return string
     */
    protected function removePunctuation(string $text): string
    {
        return preg_replace("/\p{P}+/u", '', $text);
    }

    /**
     * Remove stopwords from the text.
     *
     * @param string $text
     *
     * @return string
     */
    protected function removeStopwords(string $text): string
    {
        $words = explode(' ', $text);

        $filteredWords = array_filter($words, function ($word) {
            return ! isset($this->stopWords[$word]);
        });

        return implode(' ', $filteredWords);
    }

    /**
     * Normalize whitespace by collapsing multiple spaces into a single space.
     *
     * @param string $text
     *
     * @return string
     */
    protected function normalizeWhitespace(string $text): string
    {
        return preg_replace('/\s+/', ' ', $text);
    }

    /**
     * Get the stop words used by the preprocessor.
     *
     * @return array
     */
    public function getStopWords(): array
    {
        return $this->stopWords;
    }

    /**
     * Set create a new TextPreprocessor instance.
     *
     * @param array $stopWords
     *
     * @return self
     */
    public function setStopWords(array $stopWords): self
    {
        $this->stopWords = array_unique(array_flip(
            array_merge($this->stopWords, $stopWords)
        ));

        return $this;
    }
}
