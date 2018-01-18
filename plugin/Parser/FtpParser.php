<?php namespace RancherizeFtp\Parser;

use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;

/**
 * Class FtpParser
 * @package RancherizeFtp\Parser
 */
class FtpParser {

	public function getUser( Configuration $config ) {
		return $config->get('ftp.user', 'ftp');
	}

	public function getPassword( Configuration $config ) {
		return $config->get('ftp.password', 'ftp');
	}

	/**
	 * @param Configuration $config
	 * @return Service
	 */
	public function parse( Configuration $config ) {
		$ftpService = new Service;

		$ftpService->setName('Ftp');
		$ftpService->setImage('fauria/vsftpd');

		$user = $this->getUser( $config );
		$ftpService->setEnvironmentVariable('FTP_USER', $user );
		$password = $this->getPassword( $config );
		$ftpService->setEnvironmentVariable('FTP_PASS', $password );


		$ftpService->setEnvironmentVariable('LOG_STDOUT', 'true' );
		$ftpService->setEnvironmentVariable('PASV_ADDRESS', '127.0.0.1' );

		$minPort = $config->get( 'ftp.min_port', 200000 );
		$ftpService->setEnvironmentVariable('PASV_MIN_PORT', $minPort );
		$maxPort = $config->get( 'ftp.max_port', $minPort + 5 );
		$ftpService->setEnvironmentVariable('PASV_MAX_PORT', $maxPort );
		if( $config->has('ftp.port') ) {
			$ftpService->expose(21, $config->get('ftp.port'));

			for($currentPort = $minPort ; $currentPort <= $maxPort ; ++$currentPort) {
				$ftpService->expose($currentPort, $currentPort);
			}
		}

		$ftpService->setCommand("bash -c '/bin/mkdir /home/vsftpd/${user} && /bin/chown ${user}.${password} /home/vsftpd/${user}/ && bash /usr/sbin/run-vsftpd.sh'");

		return $ftpService;
	}

}