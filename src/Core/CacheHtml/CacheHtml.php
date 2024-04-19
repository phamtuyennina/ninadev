<?php
namespace NINA\Core\CacheHtml;
use Illuminate\Support\Str;
use NINA\Core\Singleton;
class CacheHtml
{
    use Singleton;
    private function writeFile($content, $name): void
    {
        $myFile = fopen(cache_path().'/'.$name, "w");
        fwrite($myFile, $content);
        fclose($myFile);
    }
    private function readFile($name): string
    {
        if (file_exists(cache_path().'/'.$name)) {
            echo file_get_contents(cache_path().'/'.$name);
        }
        return '';
    }
    public function set($content, $name): void
    {
        if (!empty($content) && !empty($name)) {
            $this->writeFile($content, $name);
        }
    }
    public function checkUrlCache($path): bool
    {
        if(request()->segment(1)==='admin') return false;
        $contains = true;
        $notCacheArray = array_merge(['/thumb','/admin'],config('app.nocache'));
        foreach ($notCacheArray as $element) {
            if (str_contains($path, $element)) {
                $contains = false;
                break;
            }
        }
        return $contains;
    }
    public function checkFile($name): bool
    {
        if(file_exists(cache_path().'/'.$name)){
            if(config('app.cache_pages_time')>0){
                $fileModifiedTime = filemtime(cache_path().'/'.$name);
                $currentTimestamp = time();
                $timeDifference = $currentTimestamp - $fileModifiedTime;
                $timeDifferenceInMinutes = $timeDifference / 60;
                if ($timeDifferenceInMinutes < config('app.cache_pages_time')) return true;
                return false;
            }
            return true;
        }
        return false;
    }
    public function get($name): void
    {
        $this->readFile($name);
    }
}