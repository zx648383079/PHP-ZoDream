<?php
namespace Module\Disk\Domain\App;


class Ipa {
    const TEMPLATE = <<<XML
<?xml version="1.0" encoding="UTF-8"?>  
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">  
<plist version="1.0">  
<dict>
    <key>items</key>
    <array>  
        <dict>  
            <key>assets</key>  
            <array>  
                <dict>  
                    <key>kind</key>  
                    <string>software-package</string>  
                    <key>url</key>  
                    <string>{url}</string>  
                </dict>  
                <dict>  
                    <key>kind</key>  
                    <string>full-size-image</string>  
                    <key>needs-shine</key>  
                    <true/>  
                    <key>url</key>  
                    <string>{icon2x}</string>  
                </dict>  
                <dict>  
                    <key>kind</key>  
                    <string>display-image</string>  
                    <key>needs-shine</key>  
                    <true/>  
                    <key>url</key>  
                    <string>{icon}</string>  
                </dict>  
            </array>  
            <key>metadata</key>  
            <dict>  
                <key>bundle-identifier</key>  
                <string>{bundle_id}</string>  
                <key>bundle-version</key>  
                <string>{version}</string>  
                <key>kind</key>  
                <string>software</string>  
                <key>title</key>  
                <string>{name}</string>  
            </dict>  
        </dict>  
    </array>  
</dict>  
</plist>
XML;



    public static function getPlist($name, $bundle_id, $url, $icon, $version, $icon2x) {
        $data = compact('name', 'bundle_id', 'url', 'icon', 'icon2x', 'version');
        $content = self::TEMPLATE;
        foreach ($data as $key => $item) {
            $content = str_replace(sprintf('{%s}', $key), $item, $content);
        }
        return $content;
    }
}