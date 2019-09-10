<?php


namespace DynamicScreen\ExtensionKit;


interface RemoteFileContract
{
    public function isReady() : bool;
    public function getUrl() : string;
    public function getMetadata() : array;
    public function metadata($key, $default = null);
}