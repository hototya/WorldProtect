<?php
namespace hototya\world;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;

use pocketmine\utils\Config;

class WorldProtect extends PluginBase implements Listener
{
    private $worlds = [];

    public function onEnable()
    {
        if (!file_exists($this->getDataFolder())) mkdir($this->getDataFolder(), 0744, true);
        $config = new Config($this->getDataFolder() . "worlds.yml", Config::YAML, [
            1 => "world",
            2 => "lobby"
        ]);
        $this->worlds = array_flip($config->getAll());
    }

    public function onBlockBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();//プレイヤーオブジェクトを取得
        if (!$player->isOp()) {//プレイヤーがopでなければ
            $name = $player->getLevel()->getName();//ワールド名を取得
            if (isset($this->worlds[$name])) {
                $event->setCancelled();//イベントをキャンセル
            }
        }
    }

    public function onBlockPlace(BlockPlaceEvent $event)
    {
        $player = $event->getPlayer();
        if (!$player->isOp()) {
            $name = $player->getLevel()->getName();
            if (isset($this->worlds[$name])) {
                $event->setCancelled();
            }
        }
    }
}
