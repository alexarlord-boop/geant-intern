<?php

class IOUtils
{
    public function readFile(string $filename): string
    {
        $content = file_get_contents($filename);

        if ($content === false) {
            throw new RuntimeException("Failed to read file: $filename");
        }

        return $content;
    }

    public function writeFile(string $filename, string $content): bool
    {
        $result = file_put_contents($filename, $content);

        if ($result === false || $result === 0) {
            throw new RuntimeException("Failed to write to file: $filename");
        }

        return true;
    }

}