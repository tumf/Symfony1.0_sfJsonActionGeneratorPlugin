<?php

/**
 * sfJsonAction generator.
 * 
 * @package    symfony
 * @subpackage generator
 * @author     Kiryu Tsukimiya <tsukimiya@gmail.com>
 * @version    SVN: $Id:$
 */
class sfJsonActionGenerator extends sfGenerator
{
  protected $params = array();
  
  public function initialize($generatorManager)
  {
    parent::initialize($generatorManager);
    
    $this->setGeneratorClass('sfJsonAction');
  }
  
  public function generate($params = array())
  {
    $logger = sfContext::getInstance()->getLogger();
    $this->params = $params;
    
    $required_parameters = array('moduleName');
    foreach($required_parameters as $entry) {
      if (isset($this->params[$entry]) == false) {
        throw new sfParseException(sprintf('You must specify a "%s".', $entry));
      }
    }
    
    $this->setGeneratedModuleName('auto'.ucfirst($this->params['moduleName']));
    $this->setModuleName($this->params['moduleName']);
    
    $theme = isset($this->params['theme']) ? $this->params['theme'] : 'default';
    $themeDir = sfLoader::getGeneratorTemplate($this->getGeneratorClass(), $theme, '');

    if (is_dir($themeDir) == false) {
      throw new sfConfigurationException(sprintf('The theme "%s" does not exist.', $theme));
    }
    
    $this->setTheme($theme);
    
    $this->generatePhpFiles($this->generatedModuleName);
    
    // require generated action class
    $data = "require_once(sfConfig::get('sf_module_cache_dir').'/".$this->generatedModuleName."/actions/actions.class.php');\n";
    
    return $data;
  }
}