<?php

namespace Ifui\WebmanModule\Utils;

class MergeVendorPlugin
{
    /**
     * List of composer autoload namespace.
     *
     * @var array
     */
    protected $namespaceList = [];

    /**
     * List of composer autoload pst4.
     *
     * @var array
     */
    protected $psr4List = [];

    /**
     * List of composer autoload classmap.
     *
     * @var array
     */
    protected $classMapList = [];

    /**
     * List of composer autoload files.
     *
     * @var array
     */
    protected $includeFilesList = [];

    /**
     * The primary composer instance.
     *
     * @var \Composer\Autoload\ClassLoader
     */
    protected $loader;

    /**
     * Init method.
     *
     * @return void
     */
    public function init()
    {
        $this->loader = include base_path() . '/vendor/autoload.php';
        $this->preloadLists();
    }

    /**
     * Preload composer list.
     *
     * @return void
     */
    protected function preloadLists()
    {
        $this->classMapList = array_fill_keys(array_keys($this->loader->getClassMap()), true);
        $this->namespaceList = array_fill_keys(array_keys($this->loader->getPrefixes()), true);
        $this->psr4List = array_fill_keys(array_keys($this->loader->getPrefixesPsr4()), true);
        $this->includeFilesList = $this->preloadIncludeFilesList();
    }

    /**
     * Preload autoload files.
     *
     * @return array
     */
    protected function preloadIncludeFilesList()
    {
        $result = [];
        $vendorPath = __DIR__ . '/vendor';
        if (file_exists($file = $vendorPath . '/composer/autoload_files.php')) {
            $includeFiles = require $file;
            foreach ($includeFiles as $includeFile) {
                $relativeFile = $this->stripVendorDir($includeFile, $vendorPath);
                $result[$relativeFile] = true;
            }
        }
        return $result;
    }

    /**
     * Include vendor.
     *
     * @param string $vendorPath absolute path to the vendor directory.
     * @return void
     */
    public function addVendor($vendorPath)
    {
        $dir = $vendorPath . '/composer';
        if (file_exists($file = $dir . '/autoload_namespaces.php')) {
            $map = require $file;
            foreach ($map as $namespace => $path) {
                if (isset($this->namespaceList[$namespace])) continue;
                $this->loader->set($namespace, $path);
                $this->namespaceList[$namespace] = true;
            }
        }
        if (file_exists($file = $dir . '/autoload_psr4.php')) {
            $map = require $file;
            foreach ($map as $namespace => $path) {
                if (isset($this->psr4List[$namespace])) continue;
                $this->loader->setPsr4($namespace, $path);
                $this->psr4List[$namespace] = true;
            }
        }
        if (file_exists($file = $dir . '/autoload_classmap.php')) {
            $classMap = require $file;
            if ($classMap) {
                $classMapDiff = array_diff_key($classMap, $this->classMapList);
                $this->loader->addClassMap($classMapDiff);
                $this->classMapList += array_fill_keys(array_keys($classMapDiff), true);
            }
        }
        if (file_exists($file = $dir . '/autoload_files.php')) {
            $includeFiles = require $file;
            foreach ($includeFiles as $includeFile) {
                $relativeFile = $this->stripVendorDir($includeFile, $vendorPath);
                if (isset($this->includeFilesList[$relativeFile])) continue;
                require $includeFile;
                $this->includeFilesList[$relativeFile] = true;
            }
        }
    }

    /**
     * Removes the vendor directory from a path.
     *
     * @param string $path
     * @return string
     */
    protected function stripVendorDir($path, $vendorDir)
    {
        $path = realpath($path);
        $vendorDir = realpath($vendorDir);
        if (strpos($path, $vendorDir) === 0) {
            $path = substr($path, strlen($vendorDir));
        }
        return $path;
    }
}