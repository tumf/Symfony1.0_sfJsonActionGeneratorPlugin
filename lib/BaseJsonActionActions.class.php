<?php

abstract class BaseJsonActionActions extends sfActions
{
  public function postExecute()
  {
    // JSON形式なら$this->jsonの内容を出力
    if ($this->isJsonRequest() && isset($this->json) && $this->json) {
      sfConfig::set("sf_web_debug",0);
      $result = json_encode($this->json);
      $this->getResponse()->setHttpHeader('Content-Type', 'application/json');
      return $this->renderText($result);
    }
  }
  
  public function isJsonRequest()
  {
    return in_array('application/json', $this->getRequest()->getAcceptableContentTypes());
  }
}