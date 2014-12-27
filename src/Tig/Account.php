<?php

namespace Tig;

use pocketmine\Server;
use pocketmine\utils\Config;
use Tig\MainClass;

class Account{
	private static $api = null;

    public function __construct(){
		self::$instance = $this;
		$dataFolder = Server::getInstance()->getPluginManager()->getPlugin("Tig")->getDataFolder();
		if(!file_exists($dataFolder)) {
			@mkdir($dataFolder, 0744, true);
		}
		$this->account = new Config($dataFolder."account.yml", Config::YAML, array());
	}
	
	public function API(){
		return self::$instance;
	}
	
	//アカウントの作成
	public function craftAccount($user) {
		if(!$this->account->exists($user)){
			$this->account->set($user,array("pt" => 0, "win" => 0, "lose" => 1, "exp" => 0,));
			$this->account->save();
		}
		return true;
    }
	
	//アカウントから情報取得
	public function getAccount($user){
		if($this->account->exists($user)){
			return $this->account->get($user);
		}else{
			return false;
		}
	}
	
	//アカウントからポイントを取得
	public function getPoint($user){
		if($this->account->exists($user)){
			$point = $this->account->get($user)['pt'];
			return $point;
		}else{
			return false;
		}
	}
	
	//ポイント付与
	public function grantPoint($user,$amount){
		$npt = $this->account->get($user)['pt'];
		$npt += $grant;
		$this->account->set($user, array_merge($this->account->get($user), array('pt' => $npt)));
		$this->account->save();
		return true;
	}
	
	//ポイントマイナス
	public function minusPoint($user,$amount){
		$pt = $this->account->get($user)['pt'];
		$pt -= $pt;
		if($npt < 0){
			return false;
		}
		$this->account->set($user, array_merge($this->account->get($user), array('pt' => $pt)));
		$this->account->save();
		return true;
	}

	//勝利
	public function grantWin($user){
		$win = $this->account->get($user)['win'];
		$win ++;
		$this->account->set($user, array_merge($this->account->get($user), array('win' => $win)));
		return true;
	}

	//負ける
	public function grantLose($user){
		$lose = $this->account->get($user)['lose'];
		$lose++;
		$this->account->set($user, array_merge($this->account->get($user), array('lose' => $lose)));
		return true;
	}
	
	//経験値
	public function grantExp($user,$exp){
		$exp = $this->account->get($user)['exp'];
		$exp++;
		$this->account->set($user, array_merge($this->account->get($user), array('exp' => $exp)));
		return true;
	}
}