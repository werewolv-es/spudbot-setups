<?php
        function recursiveFindFiles($dir, $ext) {
                if (!file_exists($dir)) { return; }

                $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS));
                foreach($it as $file) {
                        if (pathinfo($file, PATHINFO_EXTENSION) == $ext) {
                                yield $file;
                        }
                }
        }

        function getJSONLastError() {
                switch (json_last_error()) {
                        case JSON_ERROR_NONE:
                                return 'No error has occurred';
                        case JSON_ERROR_DEPTH:
                                return 'The maximum stack depth has been exceeded';
                        case JSON_ERROR_STATE_MISMATCH:
                                return 'Invalid or malformed JSON';
                        case JSON_ERROR_CTRL_CHAR:
                                return 'Control character error, possibly incorrectly encoded';
                        case JSON_ERROR_SYNTAX:
                                return 'Syntax error';
                        case JSON_ERROR_UTF8:
                                return 'Malformed UTF-8 characters, possibly incorrectly encoded';
                        case JSON_ERROR_RECURSION:
                                return 'One or more recursive references in the value to be encoded';
                        case JSON_ERROR_INF_OR_NAN:
                                return 'One or more NAN or INF values in the value to be encoded';
                        case JSON_ERROR_UNSUPPORTED_TYPE:
                                return 'A value of a type that cannot be encoded was given';
                        case JSON_ERROR_INVALID_PROPERTY_NAME:
                                return 'A property name that cannot be encoded was given';
                        case JSON_ERROR_UTF16:
                                return 'Malformed UTF-16 characters, possibly incorrectly encoded';
                        default:
                                return 'Unknown error';
                }
        }

        function loadSetups($fromLocation) {
                echo 'Loading setups...', "\n";

		$hasError = false;
                $newSetups = [];
                $setupFiles = [];

                if (!empty($fromLocation) && file_exists($fromLocation)) {
                        if (is_dir($fromLocation)) {
                                foreach (recursiveFindFiles($fromLocation, 'json') as $file) { $setupFiles[] = (string)$file; }
                        } else {
                                $setupFiles[] = $fromLocation;
                        }
                }

                sort($setupFiles);

                foreach ($setupFiles as $file) {
                        echo "\t", 'Using file: ', $file, "\n";

                        $contents = file_get_contents($file);

                        // Remove comments.
                        $contents = preg_replace("#/\*.*?\*/#s", '', $contents);

                        $data = json_decode($contents, true);
                        if (is_array($data)) {
                                echo "\t\t", 'Found ', count($data), ' setup', (count($data) != 1 ? 's' : ''), '.', "\n";
                                $newSetups = array_merge($newSetups, $data);
                        } else {
                                echo "\t\t", 'Error reading setups - ', getJSONLastError(), "\n";
				$hasError = true;
                        }
                        echo "", "\n";
                }

                echo 'Loaded ', count($newSetups), ' setup', (count($newSetups) != 1 ? 's' : ''), ' from ', $fromLocation, '.', "\n";

		exit($hasError ? 1 : 0);
        }

	loadSetups(__DIR__);
