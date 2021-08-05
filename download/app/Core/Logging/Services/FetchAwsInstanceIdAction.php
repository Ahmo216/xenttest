<?php

declare(strict_types=1);

namespace App\Core\Logging\Services;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FetchAwsInstanceIdAction
{
    private const AWS_ENDPOINT = 'http://169.254.169.254/latest/meta-data/instance-id';

    private const ON_PREMISE_INSTANCE = 'on_premise';

    /** @var ClientInterface */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function __invoke(): ?string
    {
        try {
            $response = $this->client->request(Request::METHOD_GET, self::AWS_ENDPOINT, [
                RequestOptions::TIMEOUT => 1.0,
            ]);
        } catch (ConnectException $e) {
            return self::ON_PREMISE_INSTANCE;
        } catch (GuzzleException $e) {
            return null;
        }

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return null;
        }

        return $response->getBody()->getContents();
    }
}
