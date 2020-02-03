<?php

namespace LaravelUpgrade\Project;

class Info extends Base
{
    private static $versions = ['5.0', '5.1', '5.2', '5.3', '5.4', '5.5', '5.6', '5.7', '5.8', '6.0'];

    public function getComposerFilePath()
    {
        return $this->folder . "/composer.json";
    }

    public function getComposerData()
    {
        return json_decode(file_get_contents($this->getComposerFilePath()));
    }

    public function getLaravelVersion()
    {
        return str_replace("^", "", $this->getComposerData()->require->{"laravel/framework"});
    }

    public function getLaravelNextVersion()
    {
        $currentVersion = implode(".", array_slice(explode(".", $this->getLaravelVersion()), 0, 2));
        $nextVersion = null;
        if (!empty(self::$versions[array_search($currentVersion, self::$versions) + 1])) {
            $nextVersion = self::$versions[array_search($currentVersion, self::$versions) + 1];
        }
        return $nextVersion;
    }
}
