<?php

namespace App\Http\Controllers;

use App\Api\ApiMailchimp;
use App\Api\Model\ListModel;
use App\Exceptions\JsonException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiListController extends BaseController
{
    private $api;

    /**
     * ApiListController constructor.
     */
    public function __construct()
    {
        $this->api = resolve(ApiMailchimp::class);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLists(Request $request)
    {
        return response()->json([$this->api->getList()]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws JsonException
     */
    public function patchList(Request $request)
    {
        $this->validateListId($request);
        $this->validatorList($request);
        $result = $this->api->editList($request->get('list_id'), resolve(ListModel::class)->toArray());
        $status = 'error';
        if (isset($result['id'])) {
            $status = 'Success';
        }

        return response()->json(['Status' => $status]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws JsonException
     */
    public function createList(Request $request)
    {
        $this->validatorList($request);
        $result = $this->api->createList(resolve(ListModel::class)->toArray());
        $status = 'error';
        if (isset($result['id'])) {
            $status = 'Success';
        }

        return response()->json(['Status' => $status]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws JsonException
     */
    public function deleteList(Request $request)
    {
        $this->validateListId($request);
        $result = $this->api->deleteList($request->get('list_id'));
        $status = '';
        if (is_null($result)) {
            $status = 'Success';
        }

        return response()->json(['Status' => $status]);
    }

    /**
     * @param Request $request
     * @throws JsonException
     */
    private function validateListId(Request $request)
    {
        $validator = Validator::make(
            ['list_id' => $request->get('list_id')],
            ['list_id' => 'required:string',]
        );
        if ($validator->fails()) {
            throw new JsonException('Validation', $validator, 400);
        }
    }

    /**
     * @param Request $request
     * @throws JsonException
     */
    private function validatorList(Request $request)
    {
        $validator = Validator::make(
            [
                'name' => $request->get('name'),
                'company' => $request->get('company'),
                'address1' => $request->get('address1'),
                'address2' => $request->get('address2')?:'1',
                'city' => $request->get('city'),
                'state' => $request->get('state'),
                'zip' => $request->get('zip'),
                'country' => $request->get('country'),
                'phone' => $request->get('phone')?:'',
                'permission_reminder' => $request->get('permission_reminder'),
                'from_name' => $request->get('from_name'),
                'from_email' => $request->get('from_email'),
                'subject' => $request->get('subject'),
                'language' => $request->get('language'),
                'email_type_option' => (bool) $request->get('email_type_option'),
            ],
            [
                'name' => 'required|string',
                'company' => 'required|string',
                'address1' => 'required|string',
                'address2' => 'string',
                'city' => 'required|string',
                'state' => 'required|string',
                'zip' => 'required|string',
                'country' => 'required|string',
                'phone' => 'string',
                'permission_reminder' => 'required|string',
                'from_name' => 'required|string',
                'from_email' => 'required|string',
                'subject' => 'required|string',
                'language' => 'required|string',
                'email_type_option' => 'required|bool',
            ]
        );

        if ($validator->fails()) {
            throw new JsonException('Validation', $validator, 400);
        }
    }
}
