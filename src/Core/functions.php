<?php

use NINA\Core\Routing\NINARouter;
use Pecee\Http\Url;
use Pecee\Http\Response;
use Pecee\Http\Request as BaseRequest;
function url(?string $name = null, $parameters = null, ?array $getParams = null): Url
{
    if(count(config('app.langs'))>1 && config('app.langconfig')==='link' && request()->segment(1)!=='admin'){
        $parameters['language'] = ((session()->get('locale')?: config('app.lang_default')));
    }

    return NINARouter::router()->getUrl($name, $parameters, $getParams);
}
function response(): Response
{
    return NINARouter::response();
}
function PeceeRequest(): BaseRequest
{
    return NINARouter::request();
}
function input($index = null, $defaultValue = null, ...$methods)
{
    if ($index !== null) {
        return PeceeRequest()->getInputHandler()->value($index, $defaultValue, ...$methods);
    }
    return PeceeRequest()->getInputHandler();
}
if (! function_exists('remember')) {
    function remember(string $cacheKey, int $timeCache, callable $callback,\Psr\Cache\CacheItemPoolInterface $cachePool = null)
    {
        $cachePool = new \Symfony\Component\Cache\Adapter\FilesystemAdapter('', 0, app()->make('path.cache'));
        try {
            $cacheItem = $cachePool->getItem($cacheKey);
            if (!$cacheItem->isHit()) {
                $data = $callback();
                $cacheItem->set($data);
                $cacheItem->expiresAfter($timeCache);
                $cachePool->save($cacheItem);
                return $data;
            } else {
                return $cacheItem->get();
            }
        } catch (\Psr\Cache\InvalidArgumentException $e) {
            throw $e;
        }
    }
}
if (! function_exists('config')) {
    /**
     * Get config setting
     *
     * @param string $key
     * @param string $default
     *
     * @return mixed
     */
    function config($key = null, $default = null)
    {

        if (is_null($key)) {
            return app()->make(__FUNCTION__);
        }
        if (is_array($key)) {
            return app()->make(__FUNCTION__)->set($key);
        }
        return app()->make(__FUNCTION__)->get($key, $default);
    }
}
function redirect(string $url, ?int $code = null): void
{
    if ($code !== null) {
        response()->httpCode($code);
    }
    response()->redirect($url);
}
function csrf_token(): ?string
{
    $baseVerifier = NINARouter::router()->getCsrfVerifier();
    if ($baseVerifier !== null) {
        return $baseVerifier->getTokenProvider()->getToken();
    }
    return null;
}
if (!function_exists('session')) {
    /**
     * Working on session
     *
     * @return \NINA\Core\Session\Session
     */
    function session(): \NINA\Core\Session\Session
    {
        return app()->make(__FUNCTION__);
    }
}
function include_directory($directory, $extension = '.php')
{
    if (is_dir($directory)) {
        $scan = scandir($directory);
        unset($scan[0], $scan[1]);
        foreach ($scan as $file) {
            $current_path = $directory . '/' . $file;
            if (is_dir($current_path)) {
                include_directory($current_path, $extension);
            } else {
                if (strpos($file, $extension) !== false) {
                    include_once($current_path);
                }
            }
        }
    }
}


if (!function_exists('base_path')) {
    /**
     * Get full path from base
     *
     * @param string $path
     *
     * @return string
     */
    function base_path(string $path = ''): string
    {
        return app()->basePath() . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
if (!function_exists('base_path_src')) {
    /**
     * Get full path from base
     *
     * @param string $path
     *
     * @return string
     */
    function base_path_src(string $path = ''): string
    {
        return app()->basePathSrc() . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
if (!function_exists('objectToArray')) {
    function objectToArray(\ArrayObject $inputs): array
    {
        $array = [];
        foreach ($inputs as $object) {
            $array[] = get_object_vars($object);
        }
        return $array;
    }
}
if (!function_exists('snake_case')) {
    function snake_case(string $string)
    {
        $result = "";
        for ($i = 0; $i < strlen($string); $i++) {
            if (ctype_upper($string[$i])) {
                $result .= $i === 0 ? strtolower($string[$i]) : '_' . strtolower($string[$i]);
            } else {
                $result .= strtolower($string[$i]);
            }
        }
        return $result;
    }
}
if (!function_exists('class_name_only')) {
    /**
     * Get class name only
     *
     * @param string $class
     */
    function class_name_only(string $class): string
    {
        $explode = explode('\\', $class);

        return end(
            $explode
        );
    }
}
function func()
{
    return \NINA\Core\Support\Facades\Func::class;
}
if (!function_exists('request')) {
    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return mixed|\Illuminate\Http\Request|string|array|null
     */
    function request($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('request');
        }
        if (is_array($key)) {
            return app('request')->only($key);
        }
        $value = app('request')->__get($key);
        return is_null($value) ? value($default) : $value;
    }
}
if (!function_exists('agent')) {
    function agent()
    {
        return app('agent');
    }
}
if (!function_exists('view')) {
    function view($view = '',  $data = [])
    {
        return $view ? app()->make('view')->view($view, $data) : app()->make('view');
    }
}
if (!function_exists('app')) {
    function app(string $entity = "")
    {
        if (empty($entity)) {
            return \Illuminate\Container\Container::getInstance();
        }
        return \Illuminate\Container\Container::getInstance()->make($entity);
    }
}
if (!function_exists('getCurrentPath')) {
    function getCurrentPath()
    {
        $scheme = request()->getScheme();
        $host = request()->getHost();
        $url = request()->url();
        return $url;
    }
}
if (!function_exists('trans')) {
    function trans(string $key, array $replace = [], string|null $locale = null, bool $strict = false): string|array|null
    {
        return app()->make('translator')->get($key, $replace, $locale)?: ($strict ? null : $key);
    }
}
if (!function_exists('__')) {
    function __(string $key, array $replace = [], string|null $locale = null, bool $strict = false): string|array|null
    {
        return trans(...func_get_args());
    }
}
if (!function_exists('upload_path')) {
    /**
     * Return upload path
     *
     * @param string $path
     *
     * @return string
     */
    function upload_path($path = ''): string
    {
        return app('path.upload') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('storage_path')) {
    /**
     * Return storage path
     *
     * @param string $path
     *
     * @return string
     */
    function storage_path($path = ''): string
    {
        return app('path.storage') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
if (!function_exists('cache_path')) {
    /**
     * Return storage path
     *
     * @param string $path
     *
     * @return string
     */
    function cache_path($path = ''): string
    {
        return app('path.cache') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (! function_exists('seo')) {
    function seo(){
        return \NINA\Helpers\Seo::getInstance();
    }
}
if (! function_exists('cssminify')) {
    function cssminify(){
        return \NINA\Helpers\CssMinify::getInstance();
    }
}
if (! function_exists('jsminify')) {
    function jsminify(){
        return \NINA\Helpers\JsMinify::getInstance();
    }
}
if (! function_exists('minify_html')) {
    function minify_html($html)
    {
        $search = array(
            '/\>[^\S ]+/s',
            '/[^\S ]+\</s',
            '/(\s)+/s'
        );
        $replace = array(
            '>',
            '<',
            '\\1'
        );
        return preg_replace($search, $replace, $html);
    }
}
if (! function_exists('base_href')) {
    function base_href($path=''){
        return request()->root().'/'.$path;
    }
}
if (! function_exists('assets_photo')) {
    function assets_photo($path='',$thumb=''){
        return request()->root().'/'.(!$thumb?'':('thumb/'.$thumb.'/')).'upload/'.$path.'/';
    }
}
if (! function_exists('assets')) {
    function assets($path=''){
        return request()->root().'/'.$path;
    }
}
if (! function_exists('public_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param  string  $path
     * @return string
     */
    function public_path($path = '')
    {
        return app()->publicPath($path);
    }
}
if (! function_exists('thumb_path')) {
    function thumb_path()
    {
        return app()->getThumbPath();
    }
}
if (!function_exists('transfer')) {
    function transfer($showtext = '', $numb = '', $page_transfer = '')
    {
        return view('component.transfer', ['showtext' => $showtext, 'numb' => $numb, 'page_transfer' => $page_transfer]);
    }
}