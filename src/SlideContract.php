<?php


namespace DynamicScreen\ExtensionKit;


interface SlideContract
{
    public function getName();
    public function getOptions();
    public function getDisplay();
    public function getExtensionSettings();
    public function getOption($name, $default = null);
    public function getMedia($media_id);
    public function getMedias(array $media_ids);
    public function getExternalAccount($type);
    public function getRemoteFile($remote_file_id) : RemoteFileContract;
}
