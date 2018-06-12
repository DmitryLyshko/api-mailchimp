<?php

namespace App\Api\Model;
use Illuminate\Http\Request;

/**
 * Class MemberListModel
 * @package App\Api\Model
 */
class MemberListModel
{
    private $email;
    private $status;

    /**
     * MemberListModel constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->email = $request->get('email');
        $this->status = $request->get('status');
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'email_address' => $this->email,
            'status' => $this->status
        ];
    }
}
