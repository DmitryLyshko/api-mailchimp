<?php

namespace App\Http\Controllers;

use App\Api\ApiMailchimp;
use App\Api\Model\MemberListModel;
use App\Exceptions\JsonException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class ApiMemberController
 * @package App\Http\Controllers
 */
class ApiMemberController
{
    private $api;

    /**
     * ApiMemberController constructor.
     */
    public function __construct()
    {
        $this->api = resolve(ApiMailchimp::class);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws JsonException
     */
    public function getMembers(Request $request)
    {
        $validator = Validator::make(
            [
                'list_id' => $request->get('list_id'),
            ],
            [
                'list_id' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            throw new JsonException('Validation', $validator, 400);
        }

        $result = $this->api->getMembers($request->get('list_id'));
        return response()->json($result);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws JsonException
     */
    public function createMemberList(Request $request)
    {
        $validator = Validator::make(
            [
                'list_id' => $request->get('list_id'),
                'email' => $request->get('email'),
                'status' => $request->get('status'),
            ],
            [
                'list_id' => 'required|string',
                'email' => 'required|string',
                'status' => 'required|in:subscribed,unsubscribed,cleaned,pending',
            ]
        );
        if ($validator->fails()) {
            throw new JsonException('Validation', $validator, 400);
        }

        $result = $this->api->createMember(
            $request->get('list_id'),
            resolve(MemberListModel::class)->toArray()
        );

        $id = '';
        if (isset($result['id'])) {
            $id = $result['id'];
        }

        return response()->json(['id' => $id]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws JsonException
     */
    public function editMemberList(Request $request)
    {
        $validator = Validator::make(
            [
                'list_id' => $request->get('list_id'),
                'email' => $request->get('email'),
                'status' => $request->get('status'),
                'subscriber_hash' => $request->get('subscriber_hash')
            ],
            [
                'list_id' => 'required|string',
                'email' => 'required|string',
                'status' => 'required|in:subscribed,unsubscribed,cleaned,pending',
                'subscriber_hash' => 'required|string'
            ]
        );

        if ($validator->fails()) {
            throw new JsonException('Validation', $validator, 400);
        }

        $result = $this->api->editMemberList(
            $request->get('list_id'),
            $request->get('subscriber_hash'),
            resolve(MemberListModel::class)->toArray()
        );

        return response()->json($result);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws JsonException
     */
    public function deleteMemberList(Request $request)
    {
        $validator = Validator::make(
            [
                'list_id' => $request->get('list_id'),
                'subscriber_hash' => $request->get('subscriber_hash')
            ],
            [
                'list_id' => 'required|string',
                'subscriber_hash' => 'required|string'
            ]
        );

        if ($validator->fails()) {
            throw new JsonException('Validation', $validator, 400);
        }

        $result = $this->api->deleteMemberList(
            $request->get('list_id'),
            $request->get('subscriber_hash')
        );

        return response()->json($result);
    }
}
