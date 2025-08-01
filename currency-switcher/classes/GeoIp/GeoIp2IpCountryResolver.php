<?php
namespace Pluginus\CurrencySwitcher\GeoIp;

use GeoIp2\Database\Reader;
use PharData;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Exception;
use WP_Error;

/**
 * GeoIp2IpCountryResolver
 *
 * @author Pavlo
 */
class GeoIp2IpCountryResolver extends IpCountryResolver {
	private $countryDbReader = null;
	private $dbPath = '';
	private $cache = [];
	private $licenseKey = '';
	public function __construct(string $licenseKey = '') {
		
		$this->licenseKey = $licenseKey;
		
		$upload_dir = wp_upload_dir();
		$this->dbPath = $upload_dir['basedir'] . '/currency-switcher/GeoLite2-Country.mmdb';
		
		add_action('wp_ajax_wpcs_download_geoip_db', array($this, 'downloadDb'));
	}
	private function getInstance() {
		if (!$this->countryDbReader) {
			$this->countryDbReader = new Reader($this->dbPath);
		}
		return $this->countryDbReader;
	}
	public function getCountryCode() : string {

		$data = $this->getCountryData();
		
		return $data['code'] ?? '';
	}
	public function getCountryName() : string {

		$data = $this->getCountryData();
		
		return $data['name'] ?? '';
	}
	
	private function getCountryData() : array {
		if (!$this->checkDB()) {
			return ['code'=>'error', 'name' => esc_html__('DB is empty. Please download the database.', 'currency-switcher')];
		}
		$ip = $this->getIpAddress();
		if(isset($this->cache[$ip]) &&  isset($this->cache[$ip]['code'])  &&  isset($this->cache[$ip]['name']) ){
			return $this->cache[$ip];
		}
		$data = [];
		$reader = $this->getInstance();
		$record = $reader->country($this->getIpAddress());
		
		$data['code'] = $record->country->isoCode;
		$data['name'] = $record->country->name;	
				
		$this->cache[$ip] = $data;
		
		return $data;		
	}
	function downloadDb() {
		$response = [];
		$url = "https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-Country&license_key={$this->licenseKey}&suffix=tar.gz";

		$tmpFile = download_url($url);

		if (is_wp_error($tmpFile)) {
			$response['error'] = new WP_Error('download_failed', esc_html__('Error downloading the database.', 'currency-switcher'));
			wp_send_json($response);
			return;
		}

		$tmpBaseDir = sys_get_temp_dir();
		$tmpDir = $tmpBaseDir . '/geoip_' . uniqid();

		if (!mkdir($tmpDir, 0755, true) && !is_dir($tmpDir)) {
			$response['error'] = new WP_Error('mkdir_failed', esc_html__('Failed to create a temporary directory.', 'currency-switcher'));
			wp_send_json($response);
			return;
		}

		try {
			$phar = new PharData($tmpFile);
			
			$phar->decompress(); // .gz → .tar

			$tarFile = str_replace('.gz', '', $tmpFile);
			$pharTar = new PharData($tarFile);
			$pharTar->extractTo($tmpDir, null, true);

			$mmdbFound = false;
			foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($tmpDir)) as $file) {
				if (preg_match('/\.mmdb$/', $file)) {
					wp_mkdir_p(dirname($this->dbPath));
					copy($file, $this->dbPath);
					$mmdbFound = true;
					break;
				}
			}
			if (file_exists($tmpFile)) {
				unlink($tmpFile);
			}
			if (file_exists($tarFile)) {
				unlink($tarFile);
			}
			$this->removeDirectory($tmpDir);

			if ($mmdbFound) {
				$response['success'] = esc_html__('Database downloaded successfully.', 'currency-switcher');
				$response['path'] = $this->dbPath;
			} else {
				$response['error'] = new WP_Error('not_found', esc_html__('Database file not found in the archive.', 'currency-switcher'));
			}

		} catch (Exception $e) {
			$response['error'] = new WP_Error('unpack_error', esc_html__('Error unpacking: ' . $e->getMessage(), 'currency-switcher'));
			
			if (file_exists($tmpFile)) {
				unlink($tmpFile);
			}
			if (isset($tarFile) && file_exists($tarFile)) {
				unlink($tarFile);
			}
			if (isset($tmpDir) && is_dir($tmpDir)) {
				$this->removeDirectory($tmpDir);
			}
		}
		
		wp_send_json($response);
	}
	
	public function checkDB(){
		return file_exists($this->dbPath);
	}
	
	private function removeDirectory($dir) {
		if (!is_dir($dir)) {
			return;
		}
		
		$files = array_diff(scandir($dir), array('.', '..'));
		foreach ($files as $file) {
			$filePath = $dir . '/' . $file;
			if (is_dir($filePath)) {
				$this->removeDirectory($filePath);
			} else {
				unlink($filePath);
			}
		}
		rmdir($dir);
	}
}
