<?php

use Agmedia\Models\Product\Product;
use Agmedia\Models\Product\ProductOption;
use Agmedia\Services\ImportXML;

class ControllerExtensionFeedXmlUpdateFeed extends Controller
{

    /**
     *
     */
    public function updateStockAndPrices()
    {
        if ( ! $this->validateKey($this->request->get)) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(collect(['error' => 'Unauthorized!'])->toJson());

            return;
        }

        $xml = new ImportXML(simplexml_load_file('https://api.ljekarne-pharmad.hr/is_izvoz.xml'));

        // PRODUCT WITHOUT OPTIONS
        $exist_codes  = Product::whereIn('manufacturer_id', UPDATE_BRANDS_ID)->pluck('sku')->flatten();
        $loaded       = $xml->collect()->whereIn('Sku', $exist_codes);
        $temp_product = '';

        foreach ($loaded as $item) {
            $temp_product .= '("' . (string) $item['Sku'] . '", ' . (int) $item['Stock'] . ', ' . (float) $item['RegularPrice'] . ', ' . (float) $item['SalePrice'] . '),';
        }

        $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_temp_data");
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_temp_data (sku, stock, price, special) VALUES " . substr($temp_product, 0, -1) . ";");
        $id = $this->db->query("UPDATE " . DB_PREFIX . "product p INNER JOIN " . DB_PREFIX . "product_temp_data pt ON p.sku = pt.sku SET p.quantity = pt.stock, p.price = pt.price;");

        // PRODUCT WITH OPTIONS
        $op          = Product::whereIn('manufacturer_id', UPDATE_BRANDS_ID)->with('options')->get();
        $exist_codes = collect();

        foreach ($op as $item) {
            if ($item->options) {
                foreach ($item->options as $option) {
                    $exist_codes->push($option->sku);
                }
            }
        }

        $loaded       = $xml->collect()->whereIn('Sku', $exist_codes);
        $temp_product = '';

        foreach ($loaded as $item) {
            $temp_product .= '("' . (string) $item['Sku'] . '", ' . (int) $item['Stock'] . ', ' . (float) $item['RegularPrice'] . ', ' . (float) $item['SalePrice'] . '),';
        }

        if ($loaded->count()) {
            $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_temp_data");
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_temp_data (sku, stock, price, special) VALUES " . substr($temp_product, 0, -1) . ";");
            $id = $this->db->query("UPDATE " . DB_PREFIX . "product_option_value p INNER JOIN " . DB_PREFIX . "product_temp_data pt ON p.sku = pt.sku SET p.quantity = pt.stock, p.article_price = pt.price;");
        }

        // SET PRODUCT STOCK AND PRICE BASED ON OPTIONS
        $options  = ProductOption::all();
        $products = [];

        foreach ($options as $option) {
            $products[$option->product_id]['qty']   += $option->quantity;
            $products[$option->product_id]['price'] = $option->article_price;
        }

        foreach ($products as $key => $item) {
            if ($item['price']) {
                $temp_product .= '("' . (string) $key . '", ' . (int) $item['qty'] . ', ' . (float) $item['price'] . ', ' . (float) $item['price'] . '),';
            }
        }

        $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_temp_data");
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_temp_data (sku, stock, price, special) VALUES " . substr($temp_product, 0, -1) . ";");
        $id = $this->db->query("UPDATE " . DB_PREFIX . "product p INNER JOIN " . DB_PREFIX . "product_temp_data pt ON p.product_id = pt.sku SET p.quantity = pt.stock, p.price = pt.price;");

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(collect(['status' => 1])->toJson());
    }


    /**
     *
     */
    public function updateSpecials()
    {
        if ( ! $this->validateKey($this->request->get)) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(collect(['error' => 'Unauthorized!'])->toJson());

            return;
        }

        $xml        = new ImportXML(simplexml_load_file('https://api.ljekarne-pharmad.hr/is_izvoz.xml'));
        $specials   = $xml->setSpecialCodes();

        $ids1 = array_slice($specials, 0, 999);
        $ids2 = array_slice($specials, 999, 1998);

        $products   = Product::whereIn('sku', collect($ids1)->unique('Sku')->pluck('Sku')->take(999))->get();
        $products2  = Product::whereIn('sku', collect($ids2)->unique('Sku')->pluck('Sku')->take(999))->get();
        $temp_query = '';

        foreach ($specials as $item) {
            $product_id = $products->where('sku', $item['Sku'])->pluck('product_id');

            if ( ! $product_id->first()) {
                $product_id = $products2->where('sku', $item['Sku'])->pluck('product_id');
            }

            if ($product_id->first()) {
                $temp_query .= '("' . $product_id->first() . '", 1, 1, ' . (string) $item['SalePrice'] . ', "0000-00-00", "0000-00-00"),';
            }
        }

        if ($temp_query != '') {
            $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_special");
            $id = $this->db->query("INSERT INTO " . DB_PREFIX . "product_special (product_id, customer_group_id, priority, price, date_start, date_end) VALUES " . substr($temp_query, 0, -1) . ";");
        }

        // OPTIONS SPECIALS
        $options       = ProductOption::whereIn('sku', collect($ids1)->pluck('Sku')->take(999))->get();
        $options2      = ProductOption::whereIn('sku', collect($ids1)->pluck('Sku')->take(999))->get();
        $temp_query    = '';
        $temp_products = [];

        foreach ($options as $option) {
            $temp_products[$option->product_id] = $option->sku;
        }
        foreach ($options2 as $option) {
            $temp_products[$option->product_id] = $option->sku;
        }

        $collection = collect($specials);

        $count = 0;

        foreach ($temp_products as $key => $sku) {
            $sale_price = $collection->where('Sku', $sku)->pluck('SalePrice')->first();
            $count++;
            $temp_query .= '("' . $key . '", 1, 1, ' . $sale_price . ', "0000-00-00", "0000-00-00"),';
        }

        if ($temp_query != '') {
            $id = $this->db->query("INSERT INTO " . DB_PREFIX . "product_special (product_id, customer_group_id, priority, price, date_start, date_end) VALUES " . substr($temp_query, 0, -1) . ";");
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(collect(['status' => $id])->toJson());
    }


    /**
     * @param $key
     *
     * @return bool
     */
    private function validateKey($key)
    {
        if (isset($key['key']) && $key['key'] == UPDATE_XML_KEY) {
            return true;
        }

        return false;
    }

}
