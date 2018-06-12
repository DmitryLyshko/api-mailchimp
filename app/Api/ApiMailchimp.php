<?php

namespace App\Api;

use App\Exceptions\JsonException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class ApiMailchimp
 * @package App\Api
 */
class ApiMailchimp
{
    /**
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws JsonException
     */
    public function getList()
    {
        return self::call('GET', '/lists');
    }

    /**
     * @param string $list_id
     * @param array $params_list
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws JsonException
     */
    public function editList(string $list_id, array $params_list)
    {
        return self::call('PATCH', "/lists/{$list_id}", $params_list);
    }

    /**
     * @param array $params_list
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws JsonException
     */
    public function createList(array $params_list)
    {
        return self::call('POST', "/lists/", $params_list);
    }

    /**
     * @param string $list_id
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws JsonException
     */
    public function deleteList(string $list_id)
    {
        return self::call('DELETE', "/lists/{$list_id}");
    }

    /**
     * @param string $list_id
     * @param array $params
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws JsonException
     */
    public function createMember(string $list_id, array $params)
    {
        return self::call('POST', "/lists/{$list_id}/members", $params);
    }

    /**
     * @param string $list_id
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws JsonException
     */
    public function getMembers(string $list_id)
    {
        return self::call('GET', "/lists/{$list_id}/members");
    }

    /**
     * @param string $list_id
     * @param string $subscriber_hash
     * @param array $params
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws JsonException
     */
    public function editMemberList(string $list_id, string $subscriber_hash, array $params)
    {
        return self::call('PATCH', "/lists/{$list_id}/members/{$subscriber_hash}", $params);
    }

    /**
     * @param string $list_id
     * @param string $subscriber_hash
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws JsonException
     */
    public function deleteMemberList(string $list_id, string $subscriber_hash)
    {
        return self::call('DELETE', "/lists/{$list_id}/members/{$subscriber_hash}");
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $params
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws JsonException
     */
    private static function call(string $method, string $uri, array $params = [])
    {
        $client = new Client(['base_uri' => env('BASE_URI')]);
        try {
            $response = $client->request(
                $method,
                env('API_VERSION') . $uri,
                [
                    'auth' => ['user', env('API_KEY')],
                    'json' => $params
                ]
            );
        } catch (GuzzleException $e) {
            throw new JsonException('System error', 'Guzzle exception', 400);
        }

        return json_decode($response->getBody(), true);
    }
}
