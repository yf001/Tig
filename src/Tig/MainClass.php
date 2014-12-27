<?php

namespace Tig;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\permission\ServerOperator;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\math\Vector3;

use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;


class MainClass extends PluginBase implements Listener {
    
    //サーバー開始時の処理//プラグインが有効になると実行されるメソッド
    public function onEnable() {
		if (!file_exists($this->getDataFolder()))
            @mkdir($this->getDataFolder(), 0755, true);
			
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);//イベント登録
		$this->status = true;
    }
	
    //サーバー停止時の処理//プラグインが無効になると実行されるメソッド
    public function onDisable() {
    }
	
	//コマンド処理
	public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
		switch (strtolower($command->getName())) {
			case "tig":
				if(!isset($args[0])){return false;}//例外回避
				switch ($args[0]) {
					case "start":
						$this->status = true;//ステータス　false 普通,　true PvP
						$this->AllKick();
						$sender->sendMessage("[鬼ごっこ] 鬼ごっこを開始しました");
						return true;
					break;
					case "stop":
						
						return true;
					break;
					case "stat":
						
						return true;
					break;
				}
				return true;
			break;
			case "buy":
				
				return true;
			break;
			case "stat":
				
				return true;
			break;
		}
		return false;
	}
	
	public function AllKick() {
		$op = $this->getServer->getOnlinePlayers();
		foreach($op as $player){
			$player->kick("鬼ごっこ開始の為");
		}
    }
}