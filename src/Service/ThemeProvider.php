<?php
namespace Bolt\Demo\Service;

class ThemeProvider
{
    
    public function getThemes()
    {
        $extensions = json_decode(file_get_contents("http://extensions.bolt.cm/list/downloaded.json"), true);
        $themes = [];
        foreach ($extensions['packages'] as &$ext) {
            if ($ext['type'] == 'bolt-theme') {
                $ext['source'] = dirname($ext['source'])."/".basename($ext['source'], '.git');
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
