<?php

namespace Agmedia\LuceedOpencartWrapper\Models;

use Agmedia\Helpers\Log;
use Agmedia\Luceed\Luceed;
use Agmedia\Models\Customer\Customer;

/**
 * Class LOC_Customer
 * @package Agmedia\LuceedOpencartWrapper\Models
 */
class LOC_Customer
{

    /**
     * @var Luceed
     */
    private $service;

    /**
     * @var array|null
     */
    public $customer;


    /**
     * LOC_Customer constructor.
     *
     * @param array|null $customer
     */
    public function __construct(array $customer = null)
    {
        $this->customer = $this->create($customer);
    }


    /**
     * @return string
     */
    public function getUid(): string
    {
        return $this->customer['uid'] ? $this->customer['uid'] : '';
    }


    /**
     * Check by Email if the customer exist
     * in the Luceed database.
     * Set uid data if exist.
     *
     * @return bool
     */
    public function exist(): bool
    {
        // If uid exist, customer exist.
        // Customer uid is set on class construct
        // if it exists in oc_db.
        if ($this->customer['uid']) {
            return true;
        }

        $this->service = new Luceed();
        // Set the response from Luceed service.
        $exist = $this->setResponseData(
            $this->service->getCustomerByEmail($this->customer['e_mail'])
        );

        Log::warning('Customer exist();');
        Log::warning($exist);

        if ( ! empty($exist)) {
            // If customer exist set uid data.
            $this->customer['uid'] = $exist[0]->partner_uid;

            return true;
        }

        return false;
    }


    /**
     * Store the customer in Luceed database.
     * Set uid data if response ok.
     *
     * @return $this
     */
    public function store()
    {
        $response = json_decode(
            $this->service->createCustomer(['partner' => [$this->customer]])
        );

        Log::warning('Customer store();');
        Log::warning($response);

        if (isset($response->result[0])) {
            $this->customer['uid'] = $response->result[0];
        }

        return $this;
    }


    /**
     * @param array|null $customer
     *
     * @return array
     */
    private function create(array $customer = null): array
    {
        return [
            'uid'            => $this->setUid($customer['customer_id'], true),
            'naziv'          => $customer['fname'] . ' ' . $customer['lname'],
            'ime'            => $customer['fname'],
            'prezime'        => $customer['lname'],
            'enabled'        => 'D',
            'tip_komitenta'  => 'F',
            'adresa'         => $customer['address'],
            'e_mail'         => $customer['email'],
            'postanski_broj' => $customer['zip']
        ];
    }


    /**
     * Set customer uid data.
     * Param can be luceed_uid or oc_customer_id.
     * If it's customer_id 2 param should be true.
     *
     * @param       $uid
     * @param false $from_ocdb
     */
    public function setUid($uid, $from_ocdb = false): void
    {
        if ($uid && $from_ocdb) {
            $customer = Customer::where('customer_id', $uid)->first();

            if ($customer) {
                $this->customer['uid'] = $customer->luceed_uid;
            }
        } else {
            $this->customer['uid'] = $uid ? $uid : null;
        }
    }


    /**
     * Return the corrected response from luceed service.
     * Without unnecessary tags.
     *
     * @param $response
     *
     * @return array
     */
    private function setResponseData($response): array
    {
        Log::warning('Customer setResponseData($response);');
        Log::warning($response);

        $data = json_decode($response);

        if (isset($data->result[0])) {
            return $data->result[0]->partner;
        }

        return false;
    }

}