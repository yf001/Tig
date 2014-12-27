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
use Tig\Account;

class MainClass extends PluginBase implements Listener {
    
    //サーバー開始時の処理//プラグインが有効になると実行されるメソッド
    public function onEnable() {
		if (!file_exists($this->getDataFolder()))
            @mkdir($this->getDataFolder(), 0755, true);
			
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);//イベント登録
		$this->status = true;//デフォルトで有効に
    }
	
    //サーバー停止時の処理//プラグインが無効になると実行されるメソッド
    public function onDisable() {
    }
	
	//以下イベント処理
	
	//ログイン
	public function onLogin(PlayerLoginEvent $event){
		if($this->status = true){
			Account::API()->createAccount($event->getPlayer()->getName(),$event->getPlayer()->getAddress());
		}
	}
	//プレーヤー入室
	public function onJoin(PlayerJoinEvent $event){
		if($this->status = true){
			$player = $event->getPlayer();
			$event->setJoinMessage($player->getName."さんが参加しました!");
		}
	}
	//プレーヤーダメージ
	public function onDamage(EntityDamageByEntityEvent $event){
		$this->damager = $event->getDamager()->getName(); //そのダメージを与えた人
		$this->s = $event->getEntity()->getName();//喰らった人
		//処理
	}
	//プレーヤー死亡
	//あとで
	
	//コマンド処理
	public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
		switch (strtolower($command->getName())) {
			case "tig":
				if(!isset($args[0])){return false;}//例外回避
				switch ($args[0]) {
					case "start":
						$this->status = true;//ステータス　false 普通,　true 鬼ごっこ
						$this->AllKick();
						$sender->sendMessage("[鬼ごっこ] 鬼ごっこを開始します");
						return true;
					break;
					case "stop":
						$this->status = false;//ステータス　false 普通,　true 鬼ごっこ
						$this->AllKick();
						$sender->sendMessage("[鬼ごっこ] 鬼ごっこを終了しました");
						return true;
					break;
					case "stat":
						//あとで実装
						return true;
					break;
				}
				return true;
			break;
			case "buy":
				//Shop.phpに回す
				return true;
			break;
			case "stat":
				//あとで実装
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