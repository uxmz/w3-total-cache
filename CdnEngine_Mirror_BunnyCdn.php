<?php
/**
 * File: CdnEngine_Mirror_BunnyCdn.php
 *
 * @since X.X.X
 *
 * @package W3TC
 */

namespace W3TC;

/**
 * Class: CdnEngine_Mirror_BunnyCDN
 *
 * @since X.X.X
 *
 * @extends CdnEngine_Mirror
 */
class CdnEngine_Mirror_BunnyCDN extends CdnEngine_Mirror {
	/**
	 * Constructor.
	 *
	 * @param array $config Configuration.
	 */
	public function __construct( array $config = array() ) {
		$config = array_merge(
			array(
				'account_api_key'  => '',
				'storage_api_key'  => '',
				'stream_api_key'   => '',
				'site_root_domain' => '',
				'pull_zone_id'     => '',
			),
			$config
		);

		parent::__construct( $config );
	}

	/**
	 * Purge remote files.
	 *
	 * @since X.X.X
	 *
	 * @param  array $files   Local and remote file paths.
	 * @param  array $results Results.
	 * @return bool
	 */
	public function purge( $files, &$results ) {
		if ( empty( $this->_config['account_api_key'] ) ) {
			$results = $this->_get_results( $files, W3TC_CDN_RESULT_HALT, __( 'Empty Authorization Key.', 'w3-total-cache' ) );

			return false;
		}

		$url_prefixes = $this->url_prefixes();
		$api          = new Cdn_BunnyCDN_Api( $this->_config );
		$results      = array();

		try {
			$items = array();

			foreach ( $files as $file ) {
				foreach ( $url_prefixes as $prefix ) {
					$items[] = array(
						'url'       => $prefix . '/' . $file['remote_path'],
						'recursive' => true,
					);
				}
			}

			$api->purge( array( 'items' => $items ) );

			$results[] = $this->_get_result( '', '', W3TC_CDN_RESULT_OK, 'OK' );
		} catch ( \Exception $e ) {
			$results[] = $this->_get_result( '', '', W3TC_CDN_RESULT_HALT, __( 'Failure to pull zone: ', 'w3-total-cache' ) . $e->getMessage() );
		}

		return ! $this->_is_error( $results );
	}

	/**
	 * Purge CDN completely.
	 *
	 * @since X.X.X
	 *
	 * @param  array $results Results.
	 * @return bool
	 */
	public function purge_all( &$results ) {
		if ( empty( $this->_config['account_api_key'] ) ) {
			$results = $this->_get_results( array(), W3TC_CDN_RESULT_HALT, __( 'Missing Account API Key.', 'w3-total-cache' ) );

			return false;
		}

		$url_prefixes = $this->url_prefixes();
		$api          = new Cdn_BunnyCDN_Api( $this->_config );
		$results      = array();

		try {
			$items = array();

			foreach ( $url_prefixes as $prefix ) {
				$items[] = array(
					'url'       => $prefix . '/',
					'recursive' => true,
				);
			}

			$r = $api->purge( array( 'items' => $items ) );
		} catch ( \Exception $e ) {
			$results[] = $this->_get_result( '', '', W3TC_CDN_RESULT_HALT, __( 'Failure to pull zone: ', 'w3-total-cache' ) . $e->getMessage() );
		}

		return ! $this->_is_error( $results );
	}

	/**
	 * Get URL prefixes.
	 *
	 * @since X.X.X
	 *
	 * @return array
	 */
	private function url_prefixes() {
		$url_prefixes = array();

		if ( 'auto' === $this->_config['ssl'] || 'enabled' === $this->_config['ssl'] ) {
			$url_prefixes[] = 'https://' . $this->_config['site_root_domain'];
		}
		if ( 'auto' === $this->_config['ssl'] || 'enabled' !== $this->_config['ssl'] ) {
			$url_prefixes[] = 'http://' . $this->_config['site_root_domain'];
		}

		return $url_prefixes;
	}
}