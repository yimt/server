<?php
/**
 * @copyright Copyright (c) 2018 Bjoern Schiessle <bjoern@schiessle.org>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */


namespace OC\Federation;

use OCP\Federation\Exceptions\ProviderAlreadyExistsException;
use OCP\Federation\Exceptions\ProviderDoesNotExistsException;
use OCP\Federation\ICloudFederationProvider;
use OCP\Federation\ICloudFederationProviderManager;

/**
 * Class Manager
 *
 * Manage Cloud Federation Providers
 *
 * @package OC\Federation
 */
class CloudFederationProviderManager implements ICloudFederationProviderManager {

	/** @var array list of available cloud federation providers */
	private $cloudFederationProvider;

	public function __construct() {
		$this->cloudFederationProvider= [];
	}


	/**
	 * Registers an callback function which must return an cloud federation provider
	 *
	 * @param string $id
	 * @param string $displayName
	 * @param callable $callback
	 * @throws ProviderAlreadyExistsException
	 */
	public function addCloudFederationProvider($id, $displayName, callable $callback) {

		if (isset($this->cloudFederationProvider[$id])) {
			throw new ProviderAlreadyExistsException($id, $this->cloudFederationProvider[$id]['displayName']);
		}

		$this->cloudFederationProvider[$id] = [
			'id' => $id,
			'displayName' => $displayName,
			'callback' => $callback,
		];

	}

	/**
	 * remove cloud federation provider
	 *
	 * @param string $providerId
	 */
	public function removeCloudFederationProvider($providerId) {
		unset($this->cloudFederationProvider[$providerId]);
	}

	/**
	 * get a list of all cloudFederationProviders
	 *
	 * @return array [id => ['id' => $id, 'displayName' => $displayName, 'callback' => callback]]
	 */
	public function getAllCloudFederationProviders() {
		return $this->cloudFederationProvider;
	}

	/**
	 * get a specific cloud federation provider
	 *
	 * @param string $providerId
	 * @return ICloudFederationProvider
	 * @throws ProviderDoesNotExistsException
	 */
	public function getCloudFederationProvider($providerId) {
		if (isset($this->cloudFederationProvider[$providerId])) {
			return call_user_func($this->cloudFederationProvider[$providerId]['callback']);
		} else {
			throw new ProviderDoesNotExistsException($providerId);
		}
	}

}
