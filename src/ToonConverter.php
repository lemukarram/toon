<?php

namespace LeMukarram\Toon; // <-- UPDATED NAMESPACE

class ToonConverter
{
    /**
     * Convert an array or JSON string to TOON format.
     */
    public function jsonToToon(array|string $data): string
    {
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        if (!is_array($data)) {
             return (string) $data;
        }

        return trim($this->encodeData($data, 0));
    }

    /**
     * Decode TOON format back to a PHP array.
     * This is a complex task left as a V2 feature.
     */
    public function toonToJson(string $toon): array
    {
        // For a V1, focusing on the encoder (jsonToToon) is the
        // primary goal for AI prompts. A full decoder is complex.
        $lines = explode("\n", trim($toon));
        return $this->decodeLines($lines);
    }

    /**
     * Estimate tokens (rough approximation for AI usage).
     */
    public function countTokens(string $text): int
    {
        // Rule of thumb: 4 chars ~= 1 token
        return (int) ceil(strlen($text) / 4);
    }

    // --- ENCODING LOGIC ---

    protected function encodeData(array $data, int $level): string
    {
        $indent = str_repeat("  ", $level);
        $output = "";

        // Check if this is a list of compressible objects
        if (array_is_list($data) && !empty($data) && is_array($data[0]) && $this->isCompressibleTable($data)) {
            return $this->encodeTable($data, $level);
        }

        // Standard Key-Value or List Loop
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (empty($value)) {
                     $output .= "{$indent}{$key}: []\n";
                     continue;
                }
                
                $output .= "{$indent}{$key}:\n";
                // Recurse deeper
                $output .= $this->encodeData($value, $level + 1);
            } else {
                // Clean up value strings
                $safeValue = $this->escapeValue((string) $value);
                $output .= "{$indent}{$key}: {$safeValue}\n";
            }
        }

        return $output;
    }

    protected function isCompressibleTable(array $data): bool
    {
        if (empty($data) || !is_array($data[0])) return false;
        
        $firstKeys = array_keys($data[0]);
        // Must be an associative array (object) inside
        if (array_is_list($data[0])) return false;

        foreach ($data as $item) {
            if (!is_array($item) || array_keys($item) !== $firstKeys) {
                return false;
            }
        }
        return true;
    }

    protected function encodeTable(array $data, int $level): string
    {
        $indent = str_repeat("  ", $level);
        $keys = array_keys($data[0]);
        $count = count($data);
        $keyList = implode(",", $keys);

        // Your custom format: @[count](key1,key2)
        $output = "{$indent}@[{$count}]({$keyList}):\n";

        foreach ($data as $row) {
            $values = array_map(function($val) {
                 return $this->escapeValue($val, true); // Escape for CSV
            }, array_values($row));
            
            $output .= "{$indent}  " . implode(",", $values) . "\n";
        }

        return $output;
    }

    protected function escapeValue($value, $isCsv = false): string
    {
        $value = (string) $value;
        // Basic escaping
        $value = str_replace(["\n", ":"], ["\\n", "\:"], $value);
        
        if ($isCsv) {
            // If value contains a comma, quote it
            if (str_contains($value, ',')) {
                 $value = '"' . str_replace('"', '""', $value) . '"';
            }
        }
        return $value;
    }

    // --- DECODING LOGIC (Basic) ---
    // This is a placeholder for a much more complex parser.
    // A robust version is a significant project.
    protected function decodeLines(array &$lines): array
    {
        $result = [];
        $indentSize = 0;

        while (!empty($lines)) {
            $line = ltrim($lines[0]);
            if (empty($line)) {
                array_shift($lines);
                continue;
            }
            
            // This is a basic parser and won't handle all edge cases.
            if (preg_match('/^@(.*?):$/', $line, $matches)) {
                // TODO: Implement table decoding
                array_shift($lines); // Consume table header
                // ... logic to read N lines and parse CSV
            } elseif (preg_match('/^(.*?): (.*)$/', $line, $matches)) {
                // Simple key-value
                $result[$matches[1]] = $matches[2];
                array_shift($lines);
            } elseif (preg_match('/^(.*?):$/', $line, $matches)) {
                // Nested object
                $key = $matches[1];
                array_shift($lines);
                // TODO: Implement recursive decoding
                $result[$key] = ['...nested items...'];
            } else {
                 array_shift($lines); // Unknown line, skip
            }
        }
        
        return $result;
    }
}