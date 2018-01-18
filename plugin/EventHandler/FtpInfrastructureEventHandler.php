<?php namespace RancherizeFtp\EventHandler;
use Rancherize\Blueprint\Events\MainServiceBuiltEvent;
use RancherizeFtp\Parser\FtpParser;

/**
 * Class FtpInfrastructureEventHandler
 * @package RancherizeFtp\EventHandler
 */
class FtpInfrastructureEventHandler {
	/**
	 * @var FtpParser
	 */
	private $ftpParser;

	/**
	 * FtpInfrastructureEventHandler constructor.
	 * @param FtpParser $ftpParser
	 */
	public function __construct( FtpParser $ftpParser) {
		$this->ftpParser = $ftpParser;
	}

	/**
	 * @param MainServiceBuiltEvent $event
	 */
	public function mainServiceBuilt( MainServiceBuiltEvent $event ) {
		$infrastructure = $event->getInfrastructure();
		$config = $event->getEnvironmentConfiguration();
		$mainService = $event->getMainService();

		$ftpService = $this->ftpParser->parse($config);

		$mainService->addLink($ftpService, 'ftp');
		$mainService->setEnvironmentVariable('FTP_USER', $this->ftpParser->getUser($config) );
		$mainService->setEnvironmentVariable('FTP_PASSWORD', $this->ftpParser->getPassword($config) );
		$infrastructure->addService($ftpService);
	}

}