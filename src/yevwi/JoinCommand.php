<?php

namespace yevwi;

use pocketmine\console\ConsoleCommandSender;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\lang\Language;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class JoinCommand  extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->saveResource("config.yml");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }


    public function handleDeath(PlayerDeathEvent $event): void
    {

        $player = $event->getPlayer();

        if (!$player instanceof Player)
            return;
        $last = $player->getLastDamageCause();

        if ($last instanceof EntityDamageByEntityEvent) {
            $damager = $last->getDamager();

            if ($damager instanceof Player) {

                $player = "\"{$player->getName()}\"";
                $damager = "\"{$damager->getName()}\"";
                $commands = $this->getConfig()->get("commands");
                foreach ($commands as $command) {
                    Server::getInstance()->dispatchCommand(new ConsoleCommandSender($server = Server::getInstance(), $server->getLanguage()), str_replace(["{player}", "{damager}"],  [$player, $damager], $command));
                }

            }
        }
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