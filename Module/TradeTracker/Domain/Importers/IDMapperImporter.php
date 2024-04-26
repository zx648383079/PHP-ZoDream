<?php
declare(strict_types=1);
namespace Module\TradeTracker\Domain\Importers;

use Zodream\Helpers\Json;

/**
 * 
 * @see https://github.com/EricZhu-42/SteamTradingSite-ID-Mapper
 */
class IDMapperImporter {


    /**
     * @param int $appId 730 cs; 570 dota
     * @return array{en_name: string, cn_name: string, name_id: string}[]
     */
    private function readSteam(string $content, int $appId = 730): array {
        $data = Json::decode($content);
        return array_values($data);
    }

    /**
     * 
     * @return array{en_name: string, name_id: string}[]
     */
    private function readOther(string $content, int $appId = 730): array {
        $data = Json::decode($content);
        $items = [];
        foreach ($data as $en_name => $name_id) {
            $items[] = compact('en_name', 'name_id');
        }
        return $items;
    }
}