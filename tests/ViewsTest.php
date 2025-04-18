<?php
namespace vakata\views\test;

class ViewsTest extends \PHPUnit\Framework\TestCase
{
	private static $dir1;
	private static $dir2;
	private static $views;

	public static function setUpBeforeClass(): void {
		self::$dir1 = __DIR__ . DIRECTORY_SEPARATOR . 'views1';
		self::$dir2 = __DIR__ . DIRECTORY_SEPARATOR . 'views2';
		mkdir(self::$dir1);
		mkdir(self::$dir2);
		file_put_contents(
			self::$dir1 . DIRECTORY_SEPARATOR . 'layout.php',
			'd1l <?=$this->section("s1")?><?=$this->section()?> <?=$a?><?php $this->layout("layout"); ?>'
		);
		file_put_contents(
			self::$dir2 . DIRECTORY_SEPARATOR . 'layout.php',
			'd2l <?=$this->section()?> <?=$a?>'
		);
		file_put_contents(
			self::$dir2 . DIRECTORY_SEPARATOR . 'inside.php',
			'inside'
		);
		file_put_contents(
			self::$dir1 . DIRECTORY_SEPARATOR . 'test.php',
			'<?php $this->layout("dir1::layout", ["a"=>2]); ?> d1t <?=$this->e(" <a> ", "strtoupper|trim")?> <?=$this->insert("inside");?><?php $this->sectionStart("s1"); ?>s1<?php $this->sectionStop(); ?><?php $this->sectionStart("s2"); ?>s2<?php $this->sectionStop(); ?>'
		);
		file_put_contents(
			self::$dir1 . DIRECTORY_SEPARATOR . 'ex.php',
			'<?php throw new \Exception(); ?>'
		);
	}
	public static function tearDownAfterClass(): void {
		foreach (scandir(self::$dir1) as $file) {
			if ($file == '.' || $file == '..') {
				continue;
			}
			if (is_file(self::$dir1.DIRECTORY_SEPARATOR.$file)) {
				unlink(self::$dir1.DIRECTORY_SEPARATOR.$file);
			}
		}
		rmdir(self::$dir1);
		foreach (scandir(self::$dir2) as $file) {
			if ($file == '.' || $file == '..') {
				continue;
			}
			if (is_file(self::$dir2.DIRECTORY_SEPARATOR.$file)) {
				unlink(self::$dir2.DIRECTORY_SEPARATOR.$file);
			}
		}
		rmdir(self::$dir2);
	}

	public function testCreate() {
		static::$views = new \vakata\views\Views();
		static::$views->dir(self::$dir1, 'dir1');
		static::$views->dir(self::$dir2);
		static::$views->share('a', '0');
		static::$views->share([ 'a' => '1' ]);
		$v = static::$views->get('dir1::test', ['c' => 3]);
		$this->assertEquals('d2l d1l s1 d1t &lt;A&gt; inside 2 1', $v->render());
		$this->assertEquals('d2l d1l s1 d1t &lt;A&gt; inside 2 1', static::$views->render('dir1::test'));
	}
	public function testInvalidDir() {
		$this->expectException('\\Exception');
		$v = static::$views->get('dir2::test');
	}
	public function testInvalidFile() {
		$this->expectException('\\Exception');
		$v = static::$views->get('dir1::test1');
	}
	public function testBadFile() {
		$this->expectException('\\Exception');
		$v = static::$views->get('dir1::ex');
		$v->render();
	}
}
