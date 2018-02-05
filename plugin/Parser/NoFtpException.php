<?php namespace RancherizeFtp\Parser;

/**
 * Class NoFtpException
 * @package RancherizeFtp\Parser
 *
 * Thrown if the parser is called to create an ftp service but none is wanted by the configuration
 */
class NoFtpException extends \RuntimeException
{

}