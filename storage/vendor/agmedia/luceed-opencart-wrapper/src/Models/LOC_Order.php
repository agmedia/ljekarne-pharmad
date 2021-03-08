<?php

namespace Agmedia\LuceedOpencartWrapper\Models;

use Agmedia\Helpers\Log;
use Agmedia\Luceed\Luceed;
use Agmedia\Models\Order\Order;
use Agmedia\Models\Order\OrderProduct;
use Illuminate\Support\Carbon;

/**
 * Class LOC_Order
 * @package Agmedia\LuceedOpencartWrapper\Models
 */
class LOC_Order
{

    /**
     * @var Luceed
     */
    private $service;

    /**
     * @var array|null
     */
    public $order;

    /**
     * @var array|null
     */
    private $oc_order;

    /**
     * @var string|null
     */
    public $customer_uid = null;


    /**
     * LOC_Order constructor.
     *
     * @param array|null $order
     */
    public function __construct(array $order = null)
    {
        $this->oc_order = $order;
    }


    /**
     * @param string $customer_uid
     *
     * @return $this
     */
    public function setCustomerUid(string $customer_uid)
    {
        $this->customer_uid = $customer_uid;

        return $this;
    }


    /**
     * @return false
     */
    public function store()
    {
        // Create luceed order data.
        $this->create();

        $this->service = new Luceed();
        // Send order to luceed service.
        $response = json_decode(
            $this->service->createOrder(['nalozi_prodaje' => [$this->order]])
        );

        Log::warning('Order store();');
        Log::warning($response);
        // If response ok.
        // Update order uid.
        if (isset($response->result[0])) {
            return Order::where('order_id', $this->oc_order['order_id'])->update([
                'luceed_uid' => $response->result[0]
            ]);
        }

        return false;
    }


    /**
     * Create luceed order data.
     */
    public function create(): void
    {
        $iznos = number_format($this->oc_order['total'], 2, '.', '');

        $this->order = [
            'nalog_prodaje_b2b' => $this->oc_order['order_id'],
            'datum'             => Carbon::make($this->oc_order['date_added'])->format('d.m.Y'),
            'skladiste'         => 'PJ02',//'Šifra skladišta iz Luceed-a',
            'partner'           => $this->customer_uid,
            'iznos'             => (float)$iznos,
            'placanja'          => [
                'vrsta_placanja_uid' => '1-2987',
                'iznos'              => (float)$iznos,
            ],
            /*'grupe'             => [
                'grupa_b2b' => 'Oznaka grupe u luceedu'
            ],*/
            'stavke'            => $this->getitems(),
        ];
    }


    /**
     * @return array
     */
    private function getitems(): array
    {
        $response       = [];
        $order_products = OrderProduct::where('order_id', $this->oc_order['order_id'])->get();

        if ($order_products->count()) {
            foreach ($order_products as $order_product) {
                $iznos = number_format($order_product->price, 2, '.', '');
                $response[] = [
                    'artikl_uid' => $order_product->model,
                    'kolicina'   => (int)$order_product->quantity,
                    'cijena'     => (float)$iznos,
                    'rabat'      => (int)0,
                ];
            }
        }

        return $response;
    }


    /**
     * @return array
     */
    public function getCustomerData(): array
    {
        return [
            'customer_id' => $this->oc_order['customer_id'],
            'fname'       => $this->oc_order['payment_firstname'],
            'lname'       => $this->oc_order['payment_lastname'],
            'email'       => $this->oc_order['email'],
            'phone'       => $this->oc_order['telephone'],
            'company'     => $this->oc_order['payment_company'],
            'address'     => $this->oc_order['payment_address_1'],
            'zip'         => $this->oc_order['payment_postcode'],
            'city'        => $this->oc_order['payment_city'],
            'country'     => $this->oc_order['payment_country'],
        ];
    }

}