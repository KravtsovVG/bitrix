<?
/*
* ������� ���
* ���������:
* bitrix: 1 - ����������� ���� ������ ��������, 0 - ���
* period: ����� �������� ���� � ��������
* � noRoman, 2012
*/
class myCache{
	public $bitrix=1;
	public $period=3600;
	private $dir;
	private $filename='';

	public function __construct($name='') {
		$this->dir = dirname(__FILE__).'/cache/';
		$this->filename=$this->dir.'my_'.$name.'.cache';
	}
	// �������� ���������� �� ���
	public function check($setPeriod=0){
		if(!$setPeriod){$setPeriod=$this->period;}
		clearstatcache();
		if(!file_exists($this->filename)){return 1;}
		// ������������ ��� ����, ���� �� ���������� �������
		if($this->bitrix&&isset($_REQUEST['clear_cache'])&&($_REQUEST['clear_cache']=='Y'||$_REQUEST['clear_cache']=='y')){return 1;}
		// ����� ����������� �����. ���� elseif ������ �� if
		elseif((time()-filemtime($this->filename))>$setPeriod){return 1;}else{return 0;}
	}
	// ������ ���
	public function read(){
		$buffer='';
		if(file_exists($this->filename)){
			$fh=fopen($this->filename,'r');
			while (!feof($fh)){
				$buffer.=fread($fh, 8192);
			}
			fclose($fh);
		}
		return $buffer;
	}
	// ������ ����
	function write($buffer=''){
		$fh=fopen($this->filename,'w+');
		fwrite($fh,$buffer);
		fclose($fh);
		return $buffer;
	}
	
	// ������������ ��� ����, ���� �� ���������� �������
	public function clear(){
		exec('rm -r '.$this->dir.'*');
	}
	// ����� ����������� �����
}
// ������������ ��� ����, ���� �� ���������� �������
if(isset($_REQUEST['bfsdhdbrthbxcgzfg_dfgbs234e2f'])){
	$c = new myCache();
	if($c->bitrix){$c->clear();}
}elseif(strstr($_SERVER['REQUEST_URI'],'admin/cache.php')){
	$APPLICATION->AddHeadScript('/include/myCache/jquery.js');
	$APPLICATION->AddHeadScript('/include/myCache/clear.js');
}
// ����� ����������� �����
?>