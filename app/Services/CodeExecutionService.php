<?php

namespace App\Services;

use GuzzleHttp\Client;
use Exception;

class CodeExecutionService
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $driver;

    public function __construct()
    {
        $this->driver = config('services.code_execution.driver', 'piston');
        $this->client = new Client([
            'timeout' => config('services.code_execution.timeout', 15),
            'http_errors' => false,
            'verify' => true,
        ]);
    }

    /**
     * Compile code syntax check / execution.
     *
     * @param string $source
     * @param string $lang
     * @return array
     */
    public function compile($source, $lang = 'c')
    {
        return $this->execute($source, '', $lang);
    }

    /**
     * Run code against given stdin.
     *
     * @param string $source
     * @param string $input
     * @param string $lang
     * @return array
     */
    public function execute($source, $input = '', $lang = 'c')
    {
        $driver = strtolower($this->driver);

        if ($driver === 'judge0') {
            return $this->executeJudge0($source, $input, $lang);
        }

        // Default to Piston
        return $this->executePiston($source, $input, $lang);
    }

    /**
     * Execute via Piston API (Free, HTTPS, public, multi-language).
     *
     * @param string $source
     * @param string $input
     * @param string $lang
     * @return array
     */
    protected function executePiston($source, $input, $lang)
    {
        $endpoint = config('services.code_execution.piston_url', 'https://emkc.org/api/v2/piston/execute');
        $pistonLang = $this->mapLanguageToPiston($lang);
        $fileName = $this->getFileNameForLanguage($lang);

        $payload = [
            'language' => $pistonLang['language'],
            'version' => $pistonLang['version'],
            'files' => [
                [
                    'name' => $fileName,
                    'content' => $source,
                ]
            ],
            'stdin' => (string) $input,
            'run_timeout' => 5000,
        ];

        try {
            $response = $this->client->post($endpoint, [
                'json' => $payload,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]
            ]);

            $statusCode = $response->getStatusCode();
            $body = json_decode((string) $response->getBody(), true);

            if ($statusCode >= 200 && $statusCode < 300 && isset($body['run'])) {
                $run = $body['run'];
                $compile = isset($body['compile']) ? $body['compile'] : null;

                $hasCompileError = ($compile !== null && isset($compile['code']) && $compile['code'] !== 0);
                $hasRunError = (isset($run['code']) && $run['code'] !== 0);

                $stdout = isset($run['stdout']) ? $run['stdout'] : (isset($run['output']) ? $run['output'] : '');
                $stderr = '';
                if ($hasCompileError && isset($compile['stderr'])) {
                    $stderr = $compile['stderr'];
                } elseif (isset($run['stderr'])) {
                    $stderr = $run['stderr'];
                }

                $compileStatus = ($hasCompileError || ($hasRunError && empty($stdout))) ? 'ERROR' : 'OK';

                return [
                    'compile_status' => $compileStatus,
                    'run_status' => [
                        'status' => ($hasCompileError || $hasRunError) ? 'ERROR' : 'OK',
                        'time_used' => '0.05',
                        'memory_used' => '256',
                        'output' => htmlspecialchars($stdout),
                        'output_html' => $stdout,
                        'stderr' => $stderr,
                    ],
                    'input' => $input,
                    'expected_output' => '',
                    'output' => htmlspecialchars($stdout),
                    'output_html' => $stdout,
                    'lang' => strtoupper($lang),
                ];
            }

            $errorMessage = isset($body['message']) ? $body['message'] : 'Execution service responded with status ' . $statusCode;
            return $this->errorResponse($errorMessage, $input, $lang);

        } catch (Exception $e) {
            return $this->errorResponse('Execution service connection error: ' . $e->getMessage(), $input, $lang);
        }
    }

    /**
     * Execute via Judge0 API.
     *
     * @param string $source
     * @param string $input
     * @param string $lang
     * @return array
     */
    protected function executeJudge0($source, $input, $lang)
    {
        $endpoint = rtrim(config('services.code_execution.judge0_url', 'https://ce.judge0.com'), '/') . '/submissions?wait=true';
        $languageId = $this->mapLanguageToJudge0($lang);

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $apiKey = config('services.code_execution.judge0_api_key');
        if (!empty($apiKey)) {
            $headers['X-RapidAPI-Key'] = $apiKey;
            $headers['X-RapidAPI-Host'] = 'judge0-ce.p.rapidapi.com';
        }

        $payload = [
            'source_code' => $source,
            'language_id' => $languageId,
            'stdin' => (string) $input,
        ];

        try {
            $response = $this->client->post($endpoint, [
                'json' => $payload,
                'headers' => $headers,
            ]);

            $body = json_decode((string) $response->getBody(), true);
            $stdout = isset($body['stdout']) ? $body['stdout'] : '';
            $stderr = isset($body['stderr']) ? $body['stderr'] : (isset($body['compile_output']) ? $body['compile_output'] : '');
            $statusId = isset($body['status']['id']) ? (int) $body['status']['id'] : 0;

            // Judge0 status: 3 = Accepted
            $isOk = ($statusId === 3);

            return [
                'compile_status' => ($isOk || !empty($stdout)) ? 'OK' : 'ERROR',
                'run_status' => [
                    'status' => $isOk ? 'OK' : 'ERROR',
                    'time_used' => isset($body['time']) ? $body['time'] : '0.00',
                    'memory_used' => isset($body['memory']) ? $body['memory'] : '0',
                    'output' => htmlspecialchars($stdout),
                    'output_html' => $stdout,
                    'stderr' => $stderr,
                ],
                'input' => $input,
                'expected_output' => '',
                'output' => htmlspecialchars($stdout),
                'output_html' => $stdout,
                'lang' => strtoupper($lang),
            ];

        } catch (Exception $e) {
            return $this->errorResponse('Judge0 error: ' . $e->getMessage(), $input, $lang);
        }
    }

    /**
     * Map language name to Piston identifiers.
     *
     * @param string $lang
     * @return array
     */
    protected function mapLanguageToPiston($lang)
    {
        $normalized = strtolower(trim($lang));
        switch ($normalized) {
            case 'c':
                return ['language' => 'c', 'version' => '*'];
            case 'cpp':
            case 'c++':
                return ['language' => 'c++', 'version' => '*'];
            case 'python':
            case 'py':
            case 'python3':
                return ['language' => 'python3', 'version' => '*'];
            case 'java':
                return ['language' => 'java', 'version' => '*'];
            case 'javascript':
            case 'js':
                return ['language' => 'javascript', 'version' => '*'];
            case 'php':
                return ['language' => 'php', 'version' => '*'];
            default:
                return ['language' => 'c', 'version' => '*'];
        }
    }

    /**
     * Map language name to Judge0 ID.
     *
     * @param string $lang
     * @return int
     */
    protected function mapLanguageToJudge0($lang)
    {
        $normalized = strtolower(trim($lang));
        switch ($normalized) {
            case 'c':
                return 50; // C (GCC 9.2.0)
            case 'cpp':
            case 'c++':
                return 54; // C++ (GCC 9.2.0)
            case 'python':
            case 'py':
            case 'python3':
                return 71; // Python (3.8.1)
            case 'java':
                return 62; // Java (OpenJDK 13.0.1)
            case 'javascript':
            case 'js':
                return 63; // JavaScript (Node.js 12.14.0)
            case 'php':
                return 68; // PHP (7.4.1)
            default:
                return 50;
        }
    }

    /**
     * Get filename appropriate for language.
     *
     * @param string $lang
     * @return string
     */
    protected function getFileNameForLanguage($lang)
    {
        $normalized = strtolower(trim($lang));
        switch ($normalized) {
            case 'c':
                return 'main.c';
            case 'cpp':
            case 'c++':
                return 'main.cpp';
            case 'python':
            case 'py':
            case 'python3':
                return 'main.py';
            case 'java':
                return 'Main.java';
            case 'javascript':
            case 'js':
                return 'main.js';
            case 'php':
                return 'main.php';
            default:
                return 'main.c';
        }
    }

    /**
     * Standard error response format.
     *
     * @param string $message
     * @param string $input
     * @param string $lang
     * @return array
     */
    protected function errorResponse($message, $input = '', $lang = 'C')
    {
        return [
            'compile_status' => 'ERROR',
            'run_status' => [
                'status' => 'ERROR',
                'time_used' => '0.00',
                'memory_used' => '0',
                'output' => $message,
                'output_html' => $message,
                'stderr' => $message,
            ],
            'input' => $input,
            'expected_output' => '',
            'output' => $message,
            'output_html' => $message,
            'lang' => strtoupper($lang),
        ];
    }
}
