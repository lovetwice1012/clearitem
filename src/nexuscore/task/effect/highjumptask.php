<?php

    namespace nexuscore\task\effect;


    use pocketmine\plugin\Plugin;
    use pocketmine\scheduler\Task;
    use pocketmine\entity\Effect;
    use pocketmine\entity\EffectInstance;

    class highjumptask extends Task
    {
        private $plugin;

        public function __construct(Plugin $plugin)
        {
            $this->plugin = $plugin;
        }

        public function onRun(int $currentTick)
        {
            foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
                $armorinventory = $player->getArmorInventory();
                if($armorinventory===null)       continue;
                if($armorinventory->getLeggings()->getCustomName()==="太古の鎧(脚)HIGHJUMP"){

                    $player->addEffect(new EffectInstance(Effect::getEffect(8), 400, 2, false));
                }
            }
            
        }
    }