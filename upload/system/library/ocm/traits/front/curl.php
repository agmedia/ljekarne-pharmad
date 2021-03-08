<?php
namespace OCM\Traits\Front;
trait Curl {
    private function sanitizeCurl($curl, $placeholders, $replacers) {
        if (empty($curl['header']) || !is_array($curl['header'])) {
            $curl['header'] = array();
        }
        if (empty($curl['body']) || !is_array($curl['body'])) {
            $curl['body'] = array();
        }
        if (!isset($curl['method'])) {
            $curl['method'] = 'POST';
        }
        foreach ($curl['header'] as &$each) {
            $each['value'] = $this->ocm->html_decode($each['value']);
            $each['name']  = str_replace($placeholders, $replacers, $each['name']);
            $each['value'] = str_replace($placeholders, $replacers, $each['value']);
        }
        foreach ($curl['body'] as &$each) {
            $each['value'] = $this->ocm->html_decode($each['value']);
            $each['name']  = str_replace($placeholders, $replacers, $each['name']);
            $each['value'] = str_replace($placeholders, $replacers, $each['value']);
            // if it is object, lets decode
            $_value = json_decode($each['value'], true);
            if (is_array($_value)) {
                $each['value'] = $_value;
            }
            if ($each['value'] === 'true') {
                $each['value'] = true;
            }
            if ($each['value'] === 'false') {
                $each['value'] = false;
            }
        }
        $header = $this->ocm->common->toCurlHeader($curl['header']);
        $body = $this->ocm->common->toCurlData($curl['body']);
        $url = '';
        if (!empty($curl['url'])) {
            $url = $this->ocm->html_decode($curl['url']);
            $url = str_replace($placeholders, $replacers, $url);
            if (!empty($curl['auth'])) {
                if ($curl['auth']['type'] == 'basic') {
                    if ($curl['auth']['user'] || $curl['auth']['password']) {
                        $auth_key = $curl['auth']['user'] . ':' .$curl['auth']['password'];
                        $header[] = 'Authorization: Basic ' . base64_encode($auth_key);
                    }
                }
                if ($curl['auth']['type'] == 'bearer') {
                    if ($curl['auth']['token']) {
                        $header[] = 'Authorization: Bearer ' . $curl['auth']['token'];
                    }
                }
            }
            if ($curl['method'] == 'JSON') {
                $curl['method'] = 'POST';
                $body = json_encode($body); 
                $header[] = 'Content-Type: application/json';
                $header[] = 'Content-Length: ' . strlen($body);
            }
        }
        return array(
            'url'     => $url,
            'method'  => $curl['method'],
            'body'    => $body,
            'header'  => $header
        );
    }
    private function logCurl($request, $response, $type = '') {
        $log = $type . 'Curl log' . PHP_EOL;
        $log .= 'Request:' . PHP_EOL;
        $log .= 'URL ' . $request['url'] . PHP_EOL;
        $log .= 'Method ' . $request['method'] . PHP_EOL;
        $log .= 'Header ' . json_encode($request['header']) . PHP_EOL;
        $log .= 'Body ' . (is_array($request['body']) ? json_encode($request['body']) : $request['body']) . PHP_EOL;
        $log .= 'Response:' . PHP_EOL;
        $log .= (is_array($response) ? json_encode($response) : $response);
        $this->log->write($log);
    }
}