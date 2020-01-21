<?php


namespace WEEEOpen\TaralloTest\Database;


use WEEEOpen\Tarallo\Feature;
use WEEEOpen\Tarallo\Item;
use WEEEOpen\Tarallo\ItemCode;
use WEEEOpen\Tarallo\NotFoundException;
use WEEEOpen\Tarallo\Product;

/**
 * @covers \WEEEOpen\Tarallo\Database\ProductDAO
 */
class ProductDAOTest extends DatabaseTest {
	public function testProduct() {
		$db = $this->getDb();

		$product = new Product('Intel', 'K3k', 'dunno');

		$db->productDAO()->addProduct($product);
		$gettedProduct = $db->productDAO()->getProduct($product);
		$this->assertEquals($product, $gettedProduct);
	}

	public function testProductDifferentObject() {
		$db = $this->getDb();

		$product = new Product('Intel', 'K3k', 'dunno');

		$db->productDAO()->addProduct($product);
		$gettedProduct = $db->productDAO()->getProduct(new Product('Intel', 'K3k', 'dunno'));
		$this->assertEquals($product, $gettedProduct);
	}

	public function testProductNotFound() {
		$db = $this->getDb();

		$product = new Product('Intel', 'Invalid', 'DoesNotExist');

		$this->expectException(NotFoundException::class);
		$db->productDAO()->getProduct($product);
	}

	public function testProductNotFoundDifferentVariant() {
		$db = $this->getDb();

		$product = new Product('Intel', 'E6400', 'SLAAA');

		$db->productDAO()->addProduct($product);
		$this->expectException(NotFoundException::class);
		$db->productDAO()->getProduct(new Product('Intel', 'E6400', 'SLBBB'));
	}

	public function testProductNoVariant() {
		$db = $this->getDb();

		$product = new Product('Intel', 'K3k');

		$db->productDAO()->addProduct($product);
		$gettedProduct = $db->productDAO()->getProduct($product);
		$this->assertEquals($product, $gettedProduct);
	}

	public function testDeleteProduct() {
		$db = $this->getDb();

		$product = new Product('Samsong', 'JWOSQPA', 'black');
		$db->productDAO()->addProduct($product);
		$gettedProduct = $db->productDAO()->getProduct($product);
		$this->assertEquals($product, $gettedProduct);

		$db->productDAO()->deleteProduct($product);
		$this->expectException(NotFoundException::class);
		$db->productDAO()->getProduct($product);
	}

	public function testDeleteNotExistingProduct() {
		$db = $this->getDb();

		$product = new Product('Samsong', 'JWOSQPA', 'black');

		$db->productDAO()->deleteProduct($product);
		$this->expectException(NotFoundException::class);
		$db->productDAO()->getProduct($product);
	}

	/**
	 * @covers \WEEEOpen\Tarallo\Database\ProductDAO
	 * @covers \WEEEOpen\Tarallo\Database\FeatureDAO
	 */
	public function testGetAllProducts() {
		$db = $this->getDb();

		$db->productDAO()->addProduct(
			(new Product("eMac", "EZ1600", "boh"))
				->addFeature(new Feature('motherboard-form-factor', 'miniitx'))
				->addFeature(new Feature('color', 'white'))
				->addFeature(new Feature('type', 'case'))
		);
		$db->productDAO()->addProduct(
			(new Product("Intel", "MB346789", "v2.0"))
				->addFeature(new Feature('color', 'green'))
				->addFeature(new Feature('cpu-socket', 'lga771'))
				->addFeature(new Feature('motherboard-form-factor', 'miniitx'))
				->addFeature(new Feature('parallel-ports-n', 1))
				->addFeature(new Feature('serial-ports-n', 1))
				->addFeature(new Feature('ps2-ports-n', 3))
				->addFeature(new Feature('usb-ports-n', 6))
				->addFeature(new Feature('ram-form-factor', 'dimm'))
				->addFeature(new Feature('ram-type', 'ddr2'))
				->addFeature(new Feature('type', 'motherboard'))
		);

		$SCHIFOMACCHINA = (new Item('SCHIFOMACCHINA'))
			->addFeature(new Feature('brand', 'eMac'))
			->addFeature(new Feature('model', 'EZ1600'))
			->addFeature(new Feature('variant', 'boh'))
			->addContent((new Item('B1337'))
					->addFeature(new Feature('brand', 'Intel'))
					->addFeature(new Feature('model', 'MB346789'))
					->addFeature(new Feature('variant', 'v2.0'))
					->addFeature(new Feature('working', 'yes'))
					->addFeature(new Feature('sn', 'TESTTEST'))
					->addContent((new Item('R42'))
							->addFeature(new Feature('brand', 'Samsung'))
							->addFeature(new Feature('model', 'S667ABC512'))
							->addFeature(new Feature('variant', 'v1'))
							->addFeature(new Feature('owner', 'DISAT'))
							->addFeature(new Feature('sn', 'ASD654321'))
							->addFeature(new Feature('working', rand(0, 1) ? 'yes' : 'no')))
					->addContent((new Item('R634'))
							->addFeature(new Feature('brand', 'Samsung'))
							->addFeature(new Feature('model', 'S667ABC512'))
							->addFeature(new Feature('variant', 'v1'))
							->addFeature(new Feature('owner', 'DISAT'))
							->addFeature(new Feature('sn', 'ASD123456'))
							->addFeature(new Feature('working', rand(0, 1) ? 'yes' : 'no'))));

		$db->itemDAO()->addItem($SCHIFOMACCHINA);

		$gotIt = $db->itemDAO()->getItem(new ItemCode($SCHIFOMACCHINA->getCode()));
		$this->assertInstanceOf(Product::class, $gotIt->getProduct());
		$this->assertInstanceOf(Product::class, $gotIt->getContent()[0]->getProduct());
		$this->assertEquals(null, $gotIt->getContent()[0]->getContent()[0]->getProduct());
		$this->assertEquals(null, $gotIt->getContent()[0]->getContent()[1]->getProduct());

		// Motherboard serial number in the right place (item, not product)
		$this->assertEquals('TESTTEST', $gotIt->getContent()[0]->getFeature('sn'));
		$this->assertEquals(null, $gotIt->getContent()[0]->getProduct()->getFeature('sn'));

		// The opposite, but getFeatures also checks Product
		$this->assertEquals('lga771', $gotIt->getContent()[0]->getFeature('cpu-socket'));
		$this->assertEquals('lga771', $gotIt->getContent()[0]->getProduct()->getFeature('cpu-socket'));
	}

	public function testProductFeatureOverride() {
		$db = $this->getDb();

		$db->productDAO()->addProduct(
			(new Product("Intel", "Centryno", "SL666"))
				->addFeature(new Feature('frequency-hertz', 1500000000))
				->addFeature(new Feature('isa', 'x86-64'))
				->addFeature(new Feature('cpu-socket', 'lga771'))
				->addFeature(new Feature('color', 'grey'))
				->addFeature(new Feature('type', 'cpu'))
		);

		$item = (new Item('C123'))
			->addFeature(new Feature('brand', 'Intel'))
			->addFeature(new Feature('model', 'Centryno'))
			->addFeature(new Feature('variant', 'SL666'))
			->addFeature(new Feature('color', 'red'))
			->addFeature(new Feature('sn', 'AAAAAAAA'));
		$db->itemDAO()->addItem($item);

		$gotIt = $db->itemDAO()->getItem(new ItemCode('C123'));
		$this->assertEquals('red', $gotIt->getFeature('color'));
		$this->assertEquals('grey', $gotIt->getProduct()->getFeature('color'));
	}
}
