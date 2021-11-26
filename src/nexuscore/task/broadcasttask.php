<?php

    namespace nexuscore\task;


    use pocketmine\plugin\Plugin;
    use pocketmine\scheduler\Task;
    use pocketmine\Server;

    class broadcasttask extends Task
    {
        private $plugin;

        public function __construct(Plugin $plugin)
        {
            $this->plugin = $plugin;
        }

        public function onRun():void
        {
             $array = ["バグは必ず報告しましょう！報告が認められると報酬がもらえます！","荒らしを見つけたら証拠を取ってOPに提出してください。","シールド値は死亡時に更新されます。シールド値付きの装備を装備した場合は一度自殺しましょう。"];
             $value = random_int(0, (count($array)-1));
             $players = Server::getInstance()->getOnlinePlayers();
             foreach ($players as $p){
                 $p->sendMessage("§e[お知らせ] ".$array[$value]);
                
             }
        }
    }