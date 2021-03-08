<?php
namespace OCM\Traits\Front;
trait Event_hide {
    private function hideMethodsOnActive($quote_data, $hide_list, &$debugging) {
        if($hide_list) {
            $ocm_shipping_hide = $this->ocm->getCache('ocm_shipping_hide');
            if (!$ocm_shipping_hide) $ocm_shipping_hide = array();
            $truncated = array();
            foreach ($quote_data as $key => $value) {
               $tab_id = $value['tab_id'];
               if (isset($hide_list[$tab_id]) && $hide_list[$tab_id]) {
                    $method_hide_list = $hide_list[$tab_id]['hide'];
                    foreach($method_hide_list as $hide_id) {
                        if (isset($quote_data[$this->mname . $hide_id])) {
                            $truncated[] = $hide_id;
                            /* Remove it from hide_list so it can not cancel each other */
                            if (isset($hide_list[$hide_id])) {
                                unset($hide_list[$hide_id]);
                            }
                            $debugging[] = array('name' => $quote_data[$this->mname . $hide_id]['display'],'filter' => array('Hidden by '.$hide_list[$tab_id]['display'].' when it was active'),'index' => $hide_id);
                        } else if (!is_numeric($hide_id)) {
                            $ocm_shipping_hide[] = $hide_id;
                        }
                    }
               }
            }
            /* Finally remove truncated ID */
            foreach ($truncated as $tab_id) {
                unset($quote_data[$this->mname.$tab_id]);
            }
            $this->ocm->setCache('ocm_shipping_hide', $ocm_shipping_hide);
        }
        return $quote_data;
    }
    private function hideMethodsOnInactive($quote_data, $hide_list, &$debugging) {
        if($hide_list) {
            $ocm_shipping_hide = $this->ocm->getCache('ocm_shipping_hide');
            if (!$ocm_shipping_hide) $ocm_shipping_hide = array();
            foreach($hide_list as $hide_by => $hide) {
                foreach($hide['hide'] as $tab_id) {
                    if (isset($quote_data[$this->mname . $tab_id])) {
                        $debugging[]=array('name' => $quote_data[$this->mname . $tab_id]['display'],'filter' => array('Hidden by '.$hide['display'].' when it was inactive'),'index' => $tab_id);
                        unset($quote_data[$this->mname . $tab_id]);
                    } else if (!is_numeric($hide_id)) {
                        $ocm_shipping_hide[] = $hide_id;
                    }
                }
            }
            $this->ocm->setCache('ocm_shipping_hide', $ocm_shipping_hide);
        }
        return $quote_data;
    }
}