<?php

namespace yevwi;

use pocketmine\console\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\lang\Language;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class JoinCommand  extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->saveResource("config.yml");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onJoin(PlayerJoinEvent $ev): void
    {
        if(($p = $ev->getPlayer())->hasPlayedBefore()) return;
        $nick = "\"{$p->getName()}\"";
        $commands = $this->getConfig()->get("commands");
        foreach ($commands as $command) {
            Server::getInstance()->dispatchCommand(new ConsoleCommandSender($server = Server::getInstance(), $server->getLanguage()), str_replace("{player}",  $nick, $command));
        }
    }

}