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

use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class MainClass extends PluginBase implements Listener {

	//サーバー開始時の処理//プラグインが有効になると実行されるメソッド
	public function onEnable() {
		if (!file_exists($this->getDataFolder()))
			@mkdir($this->getDataFolder(), 0755, true);
			
		$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);//イベント登録
		$this->status = true;//デフォルトで有効に
		$this->Account = new Account();//アカウントのオブジェクトを変数へ
    }
	
	//サーバー停止時の処理//プラグインが無効になると実行されるメソッド
	public function onDisable() {}

	//以下イベント処理

	//ログイン
	public function onLogin(PlayerLoginEvent $event){
		if($this->status == true){
			$this->Account->createAccount($event->getPlayer()->getName(),$event->getPlayer()->getAddress());
		}
	}
	//プレーヤー入室
	public function onJoin(PlayerJoinEvent $event){
		if($this->status == true){
			$player = $event->getPlayer();
			$event->setJoinMessage($player->getName()."さんが参加しました!");
		}
	}
	//プレーヤーダメージ
	public function onDamageByEntity(EntityDamageByEntityEvent $event){
		if($event instanceof EntityDamageByEntityEvent){//エラー回避
			$damager = $event->getDamager(); //そのダメージを与えた人
			$s = $event->getEntity();//喰らった人
			if(isset($this->oni[$damager])){
				//ここに処理
			}else{
				$event->setCancelled();
			}
		}
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
						$sender->sendMessage("[鬼ごっこ] 鬼ごっこを開始します");
						$this->status = true;//ステータス　false 普通,　true 鬼ごっこ
						$this->allKick("鬼ごっこ開始の為");
						$this->oni = array();//初期化
						$this->autoOni();
						return true;
					break;
					case "stop":
						$this->status = false;//ステータス　false 普通,　true 鬼ごっこ
						$this->allKick("鬼ごっこ終了する為");
						$this->oni = array();//初期化
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
	
	//指定したプレーヤーを鬼に//$player 鬼にするプレーヤーのオブジェクト
	public function addOni($player) {
		if($player instanceof Player){
			$player = $player->getName();
		}
		$this->oni[$player] = true;
	}
	
	//自動で鬼を決定//$onin 鬼の数.数値
	public function autoOni($onin = 1) {
		$op = $this->getServer->getOnlinePlayers();
		$rkey = array_rand($op,$onin);
		foreach($rkey as $val){
			$this->oni[$val] = true;
		}
	}
	
	//全員Kick//$m キックの理由
	public function allKick($m = "不明") {
		$op = $this->getServer->getOnlinePlayers();
		foreach($op as $player){
			$player->kick($m);
		}
	}
}