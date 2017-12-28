<?php

namespace Alstef\IhmBundle\Services;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Filesystem\Filesystem;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

class Logger extends \Monolog\Logger
{
  private $maxFiles;
  private $maxFileSize;
  private $logFileName;
  private $logDirectory;
  private $user;
  private $stream;

  public function __construct ($channel, $maxFiles, $maxFileSize, $logDirectory) {
    // Initialisation du looger
    parent::__construct ($channel);
    //Construction de l'emplacement du fichier
    $logFileName = sprintf ("%s/web_ihm.log", $logDirectory);
    // Construction du format de sortie
    $dateFormat = "Y-m-d H:i:s.u";
    // $output = "[%datetime%] [%channel%] [%level_name%] %message% %context% %extra%\n";
    $output = "[%datetime%] [%level_name%] %message%\n";
    $formatter = new LineFormatter($output, $dateFormat);
    // Définition de l'emplacement du fichier
    $stream = new StreamHandler($logFileName, Logger::DEBUG);
    $stream->setFormatter($formatter);
    $this->pushHandler($stream);

    $this->stream = $stream;
    $this->maxFiles = $maxFiles;
    $this->maxFileSize = $maxFileSize;
    $this->logFileName = $logFileName;
  }

  // Rotation des fichiers
  private function rotationFichiers () {
    $fs = new FileSystem();
    $this->stream->close();
    try {
      $fs->rename($this->logFileName, $this->logFileName . ".tmp");
    } catch (Exception $e) {
      $this->error('Impossible de renommer ' . $this->logFileName . " : " . $e->getMessage());
      return;
    }
    $fName = $this->logFileName . sprintf (".%03d", $this->maxFiles);
    if ($fs->exists($fName)) {
      $fs->remove($fName);
    }
    for ($i = $this->maxFiles; $i > 0; $i--) {
      $fNameNew = $this->logFileName . sprintf (".%03d", $i);
      $fNameOld = $this->logFileName . ($i > 1 ? sprintf (".%03d", $i - 1) : ".tmp");
      if ($fs->exists($fNameOld)) {
        $fs->rename($fNameOld, $fNameNew);
      }
    }
  }

  public function addRecord ($level, $message, array $context = array()) {
    // Gestion de la rotation de fichiers
    if (file_exists($this->logFileName) && filesize ($this->logFileName) > $this->maxFileSize) {
      $this->rotationFichiers();
    }
    // Logging du message
    return parent::addRecord ($level, $this->build_msg ($message, $context), $context);
  }

  public function debug ($message, array $context = array()) {
    return $this->addRecord(Logger::DEBUG, $message, $context);
  }

  public function info ($message, array $context = array()) {
    return $this->addRecord(Logger::INFO, $message, $context);
  }

  public function notice ($message, array $context = array()) {
    return $this->addRecord(Logger::NOTICE, $message, $context);
  }

  public function warning ($message, array $context = array()) {
    return $this->addRecord(Logger::WARNING, $message, $context);
  }

  public function error ($message, array $context = array()) {
    return $this->addRecord(Logger::ERROR, $message, $context);
  }

  public function critical ($message, array $context = array()) {
    return $this->addRecord(Logger::CRITICAL, $message, $context);
  }

  public function alert ($message, array $context = array()) {
    return $this->addRecord(Logger::ALERT, $message, $context);
  }

  public function emergency ($message, array $context = array()) {
    return $this->addRecord(Logger::EMERGENCY, $message, $context);
  }

  private function build_msg ($message, array $context = array()) {
    $stack = debug_backtrace ();
    for ($thisfile = $stack[0]['file'], $i = 1;
         !isset($stack[$i]['file']) || $stack[$i]['file'] === $thisfile;
         $i++);
    $file = basename($stack[$i]['file']);
    $function = $stack[$i+1]['function'];
    $line = $stack[$i]['line'];
    return "$file ($function) - $line : $message";
  }
}
