<?php
namespace Bolt\Demo\Service;

class ThemeProvider
{
    
    public function getThemes()
    {
        $extensions = json_decode(file_get_contents("http://extensions.bolt.cm/list.json"), true);
        $themes = [];
        foreach ($extensions['packages'] as &$ext) {
            if ($ext['type'] == 'bolt-theme') {
                $themes[] = $ext;
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
