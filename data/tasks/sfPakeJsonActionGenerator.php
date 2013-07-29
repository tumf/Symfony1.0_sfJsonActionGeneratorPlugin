<?php

pake_desc('initialize a new json module');
pake_task('json-init-module', 'app_exists');

function run_json_init_module($task, $args)
{
  if (count($args) < 2) {
    throw new Exception('You must provide your module name.');
  }
  
  $app = $args[0];
  $module = $args[1];
  $theme  = isset($args[2]) ? $args[2] : 'default';
  
  try {
    $author_name = $task->get_property('author', 'symfony');
  } catch (pakeException $pe) {
    $author_name = 'Your name here';
  }
  
  $constants = array(
    'PROJECT_NAME' => $task->get_property('name', 'symfony'),
    'APP_NAME'     => $app,
    'MODULE_NAME'  => $module,
    'AUTHOR_NAME'  => $author_name,
    'THEME'        => $theme,
  );
  
  $moduleDir = sfConfig::get('sf_root_dir').DIRECTORY_SEPARATOR.sfConfig::get('sf_apps_dir_name').DIRECTORY_SEPARATOR.$app.DIRECTORY_SEPARATOR.sfConfig::get('sf_app_module_dir_name').DIRECTORY_SEPARATOR.$module;
  
  // create module structure
  $finder = pakeFinder::type('any')->ignore_version_control()->discard('.sf');
  $dirs = sfLoader::getGeneratorSkeletonDirs('sfJsonAction', $theme);
  foreach($dirs as $dir) {
    if (is_dir($dir)) {
      pake_mirror($finder, $dir, $moduleDir);
      break;
    }
  }
  
  // customize php and yml files
  $finder = pakeFinder::type('file')->ignore_version_control()->name('*.php', '*.yml');
  pake_replace_tokens($finder, $moduleDir, '##', '##', $constants);
}