<?php

namespace Agmedia\Luceed;

use Agmedia\Luceed\Connection\LuceedService;

/**
 * Class Luceed
 * @package Agmedia\Luceed
 */
class Luceed
{

    /**
     * @var LuceedService
     */
    private $service;

    /**
     * @var string
     */
    private $env = 'local';

    /**
     * @var array
     */
    private $end_points = [];


    /**
     * Luceed constructor.
     */
    public function __construct()
    {
        $this->service    = new LuceedService();
        $this->env        = agconf('env');
        $this->end_points = LuceedEndPoints::get($this->env);
    }


    /*******************************************************************************
     *                                Copyright : AGmedia                           *
     *                              email: filip@agmedia.hr                         *
     *******************************************************************************/
    // GROUPS / CATEGORIES

    /**
     * @return mixed
     */
    public function getGroupList()
    {
        return $this->service->get($this->end_points['group_list']);
    }


    /*******************************************************************************
     *                                Copyright : AGmedia                           *
     *                              email: filip@agmedia.hr                         *
     *******************************************************************************/
    // MANUFACTURERS

    /**
     * @return mixed
     */
    public function getManufacturer(string $manufacturer_uid)
    {
        return $this->service->get($this->end_points['manufacturer_uid'] . $manufacturer_uid);
    }


    /*******************************************************************************
     *                                Copyright : AGmedia                           *
     *                              email: filip@agmedia.hr                         *
     *******************************************************************************/
    // PRODUCTS

    /**
     * @param array|null $query
     *
     * @return mixed
     */
    public function getProductsList(array $query = null)
    {
        return $this->service->get($this->end_points['product_list'] . ($query ? $query : ''));
    }


    /**
     * @param string $uid
     *
     * @return mixed
     */
    public function getProductImage($uid)
    {
        return $this->service->get($this->end_points['product_image'], $uid);
    }


    /*******************************************************************************
     *                                Copyright : AGmedia                           *
     *                              email: filip@agmedia.hr                         *
     *******************************************************************************/
    // CUSTOMERS

    /**
     * @param array $customer
     *
     * @return mixed
     */
    public function createCustomer(array $customer)
    {
        return $this->service->post($this->end_points['customer_create'], $customer);
    }


    /**
     * @param array $customer
     *
     * @return mixed
     */
    public function getCustomerByEmail(string $email)
    {
        return $this->service->get($this->end_points['customer_email'], $email);
    }


    /*******************************************************************************
     *                                Copyright : AGmedia                           *
     *                              email: filip@agmedia.hr                         *
     *******************************************************************************/
    // ORDERS

    /**
     * @param array $order
     *
     * @return mixed
     */
    public function createOrder(array $order)
    {
        return $this->service->post($this->end_points['order_create'], $order);
    }

}