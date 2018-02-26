<?php

/**
 * @copyright 2017 Christoph Wurst <christoph@winzerhof-wurst.at>
 *
 * @author 2017 Christoph Wurst <christoph@winzerhof-wurst.at>
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

namespace OC\Core\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;
use OCP\ISession;

class CSRFTokenController extends Controller {

	/** @var ISession */
	private $session;

	/**
	 * @param string $appName
	 * @param IRequest $request
	 * @param ISession $session
	 */
	public function __construct($appName, IRequest $request, ISession $session) {
		parent::__construct($appName, $request);
		$this->session = $session;
	}

	/**
	 * @NoAdminRequired
	 * @return JSONResponse
	 */
	public function index() {
		$requestToken = $this->session->get('requesttoken');

		if (is_null($requestToken)) {
			return new JSONResponse(null, Http::STATUS_NOT_FOUND);
		}

		return new JSONResponse([
			'token' => $requestToken,
		]);
	}

}
