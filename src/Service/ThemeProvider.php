<?php
namespace Bolt\Demo\Service;

class ThemeProvider
{
    
    public function getThemes()
    {
        $extensions = json_decode(file_get_contents("https://extensions.bolt.cm/list/downloaded.json?type=bolt-theme"), true);

        $themes = [];
        $defaultKey = false;
        foreach ($extensions['packages'] as $key=> &$ext) {
            if ($ext['type'] == 'bolt-theme' && strpos($ext['requirements']['bolt/bolt'], '^3.0') !== false ) {
                $ext['source'] = dirname($ext['source'])."/".basename($ext['source'], '.git');
                if($ext['name'] == "bolt/theme-2014") {
                    array_unshift($themes, $ext);
                } else {
                    $themes[] = $ext;
                }
            }
        }

        return $themes;
    }
    
    public function getThemeOptions()
    {
        $options = [];
        foreach ($this->getThemes() as $theme ) {
            $options[$theme['name']] =  $theme['name'] ;
        }
        return $options;
    }
}
