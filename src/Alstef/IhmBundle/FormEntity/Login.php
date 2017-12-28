<?php
// src/Alstef/IhmBundle/FormEntity/Login.php

namespace Alstef\IhmBundle\FormEntity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;

class Login
{
  protected $language;
  protected $name;
  protected $password;

  public function getLanguage() {
    return $this->language;
  }
  public function setLanguage($language) {
    $this->language = $language;
  }
  public function getName() {
    return $this->name;
  }
  public function setName($name) {
    $this->name = $name;
  }
  public function getPassword() {
    return $this->password;
  }
  public function setPassword($password) {
    $this->password = $password;
  }

  public static function loadValidatorMetadata(ClassMetadata $metadata)
  {
    $metadata->addPropertyConstraint('name', new NotBlank());
    $metadata->addPropertyConstraint('password', new NotBlank());
  }
}
