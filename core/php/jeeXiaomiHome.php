<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";

if (!jeedom::apiAccess(init('apikey'), 'xiaomihome')) {
	echo __('Vous n\'etes pas autorisé à effectuer cette action', __FILE__);
	die();
}

if (init('test') != '') {
	echo 'OK';
	die();
}
$result = json_decode(file_get_contents("php://input"), true);
if (!is_array($result)) {
	log::add('xiaomihome', 'debug', 'Format Invalide');
	die();
}

if (isset($result['devices'])) {
	foreach ($result['devices'] as $key => $datas) {
		if ($key == 'aquara'){
			if (!isset($datas['sid'])) {
				continue;
			}
			$logical_id = $datas['sid'];
			if ($datas['model'] == 'gateway') {
				$logical_id = $datas['source'];
			}
			$xiaomihome=xiaomihome::byLogicalId($logical_id, 'xiaomihome');
			if (!is_object($xiaomihome)) {
				$xiaomihome= xiaomihome::createFromDef($datas,$key);
				if (!is_object($xiaomihome)) {
					log::add('xiaomihome', 'debug', __('Aucun équipement trouvé pour : ', __FILE__) . secureXSS($datas['sid']));
					continue;
				}
				sleep(2);
				event::add('jeedom::alert', array(
					'level' => 'warning',
					'page' => 'xiaomihome',
					'message' => '',
				));
				event::add('xiaomihome::includeDevice', $xiaomihome->getId());
			}
			if (!$xiaomihome->getIsEnable()) {
				continue;
			}
			if ($datas['sid'] !== null && $datas['model'] !== null) {
				if ($datas['cmd'] == 'heartbeat' && $datas['model'] == 'gateway') {
					$xiaomihome = xiaomihome::byLogicalId($datas['source'], 'xiaomihome');
					$xiaomihome->setConfiguration('token',$datas['token']);
					$xiaomihome->save();
				}
				if (isset($datas['data'])) {
					$data = $datas['data'];
					foreach ($data as $key => $value) {
						if ($datas['cmd'] == 'heartbeat' && $key == 'status') {
							return;
						}
						if ($datas['model'] == 'gateway'){
							xiaomihome::receiveAquaraData($datas['source'], $datas['model'], $key, $value);
						} else {
							xiaomihome::receiveAquaraData($datas['sid'], $datas['model'], $key, $value);
						}
					}
				}
			}
		}
		elseif ($key == 'yeelight'){
			if (!isset($datas['capabilities']['id'])) {
				continue;
			}
			$logical_id = $datas['capabilities']['id'];
			$xiaomihome=xiaomihome::byLogicalId($logical_id, 'xiaomihome');
			if (!is_object($xiaomihome)) {
				if (!isset($datas['capabilities']['model'])) {
					continue;
				}
				$xiaomihome= xiaomihome::createFromDef($datas,$key);
				if (!is_object($xiaomihome)) {
					log::add('xiaomihome', 'debug', __('Aucun équipement trouvé pour : ', __FILE__) . secureXSS($datas['sid']));
					continue;
				}
				sleep(2);
				event::add('jeedom::alert', array(
					'level' => 'warning',
					'page' => 'xiaomihome',
					'message' => '',
				));
				event::add('xiaomihome::includeDevice', $xiaomihome->getId());
			}
			if (!$xiaomihome->getIsEnable()) {
				continue;
			}
		}
	}
}

?>
