<?php

namespace App\Api\Model;

use Illuminate\Http\Request;

/**
 * Class ListModel
 * @package App\Api\Model
 */
class ListModel
{
    private $name;
    private $contact;
    private $permission_reminder;
    private $campaign_defaults;
    private $email_type_option;

    /**
     * ListModel constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->name = $request->get('name');

        $contact = new \stdClass();
        $contact->company = $request->get('company');
        $contact->address1 = $request->get('address1');
        $contact->address2 = $request->get('address2')?:'';
        $contact->city = $request->get('city');
        $contact->state = $request->get('state');
        $contact->zip = $request->get('zip');
        $contact->country = $request->get('country');
        $contact->phone = $request->get('phone')?:'';
        $this->contact = $contact;

        $this->permission_reminder = $request->get('permission_reminder');

        $campaign_defaults = new \stdClass();
        $campaign_defaults->from_name = $request->get('from_name');
        $campaign_defaults->from_email = $request->get('from_email');
        $campaign_defaults->subject = $request->get('subject');
        $campaign_defaults->language = $request->get('language');
        $this->campaign_defaults = $campaign_defaults;

        $this->email_type_option = (bool) $request->get('email_type_option');
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'contact' => $this->contact,
            'permission_reminder' => $this->permission_reminder,
            'campaign_defaults' => $this->campaign_defaults,
            'email_type_option' => $this->email_type_option,
        ];
    }
}
