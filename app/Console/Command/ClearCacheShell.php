<?php
App::uses('Shell', 'Console');

class ClearCacheShell extends Shell {
    public function main() {
        $this->clearCache();
    }

    protected function clearCache() {
        Cache::clear();
        $cachePaths = array(
            'models' => CACHE . 'models' . DS,
            'persistent' => CACHE . 'persistent' . DS,
            'views' => CACHE . 'views' . DS
        );

        foreach ($cachePaths as $config => $path) {
            $this->clearCacheFiles($path);
        }

        $this->out('Cache cleared');
    }

    protected function clearCacheFiles($path) {
        if (is_dir($path)) {
            $dir = new Folder($path);
            $files = $dir->read(true, true);

            foreach ($files[1] as $file) {
                $filePath = $path . $file;
                if (is_file($filePath)) {
                    unlink($filePath);
                }
            }
        }
    }
}
