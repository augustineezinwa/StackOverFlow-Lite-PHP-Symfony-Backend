<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Psr\Container\ContainerInterface;

class HelperService {

public  function __construct(LoggerInterface $logger, ContainerInterface $container)
{
    $this->logger = $logger;
    $this->container = $container;
}

 public function getLength(array $array) : int {
     $this->logger->info('generating count for an array');
    //  dump($this->container->getParameter('fish')); // this gets parameter in the services yaml file
     return count($array);
 }
  
}