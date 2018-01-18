<?php namespace RancherizeFtp;

use Rancherize\Blueprint\Events\MainServiceBuiltEvent;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use RancherizeFtp\EventHandler\FtpInfrastructureEventHandler;
use RancherizeFtp\Parser\FtpParser;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class FtpProvider
 * @package RancherizeFtp
 */
class FtpProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container[FtpParser::class] = function() {
			return new FtpParser();
		};

		$this->container[FtpInfrastructureEventHandler::class] = function($c) {
			return new FtpInfrastructureEventHandler( $c[FtpParser::class] );
		};

		$this->container['ftp.infrastructure.eventhandler'] = function($c) {
			return $c[FtpInfrastructureEventHandler::class];
		};
	}

	/**
	 */
	public function boot() {
		/**
		 * @var EventDispatcher $event
		 */
		$event = $this->container[EventDispatcher::class];
		$eventHandler = $this->container['ftp.infrastructure.eventhandler'];

		$event->addListener(MainServiceBuiltEvent::class, [$eventHandler, 'mainServiceBuilt']);
	}
}