<?php
namespace Bolt\Demo\Service;

class ThemeProvider
{

    public $blacklist = [
        'mikescops/bootstrapbolttheme',
        'mikecops/cleanblog'
    ];
    
    public function getThemes()
    {
        $extensions = json_decode(file_get_contents("https://extensions.bolt.cm/list/downloaded.json?type=bolt-theme"), true);

        $themes = [];
        $defaultKey = false;
        foreach ($extensions['packages'] as $key=> &$ext) {
            if ($ext['type'] == 'bolt-theme' ) {
                $ext['source'] = dirname($ext['source'])."/".basename($ext['source'], '.git');
                if($ext['name'] == "bolt/theme-2016") {
                    array_unshift($themes, $ext);
                } else {
                    if (!in_array($ext['name'], $this->blacklist)) {
                        $themes[] = $ext;
                    }
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
