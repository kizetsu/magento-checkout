<?php

class Kizetsu_Checkout_Helper_Gender extends Mage_Core_Helper_Abstract
{

	const GENDER_API_URI = 'https://gender-api.com/get'; // url to gender-api service

	public function getConfig() {
		/* TODO: get config from database */
		return $config;
	}

	/**
	 * get gender by firstname
	 *
	 * @param string firstname
	 * @param array  parameters
	 * 		possible parameters:
	 *			(string) client_ip
	 *			(string) locale
	 *			()
	 * return string (male, female, unknown)
	 */
	public function getGender($firstname, $parameters = null) {
		$config = $this->getConfig();

		$request = "?";
		if($config->private_key) {
			$request .= "?key=".$config->private_key;
		}
		if($config->useip && $parameters['client_ip'] != null) {
			if(count($request) != 0) {
				$request .= "&";
			}
			$request .= "ip=".$parameters['client_ip'];
		}
		if($config->use_locale && $parameters['locale'] != null) {
			if(count($request) != 0) {
				$request .= "&";
			}
			$request .= "language=".$parameters['locale'];
		}

		$data = json_decode(file_get_contents(self::GENDER_API_URI . $request . '&name=' . urlencode($firstname)));
		if($data->errno) {
			Mage::log('GenderApi;errno:'.$data->errno.';errmsg:'.$data->errmsg.';duration:'.$data->duration.';', 4);
		}

		return $data->gender;
	}
}

?>