<?php

class WooCommerceApi{

		private $woocommerce;
		
		function __construct(){

				$this->woocommerce = new \Automattic\WooCommerce\Client(
						'http://gig-games.de/', // Your store URL
						'ck_cd110abae068e9cb3d5947123523a2eb84706c50', // Your consumer key
						'cs_9c9a63643a3cbe04cf23b059356e3789dadcb985', // Your consumer secret
						['version' => 'v3',
						 'verify_ssl'=>false,
						 'ssl_enabled'=>true,
						 'user_agent'=>'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36'] // WooCommerce API version
				);
		}

//--------------------------------------------------------------------
		public function addProduct($item){

				$data = [
						'product' => [
								'title' => '',
								'type' => 'simple',
								'regular_price' => '',
								'description' => '',
								'short_description' => '',
								'categories' => [],
								'images' => ['position' => '1', 'src' => 'http://vignette3.wikia.nocookie.net/madannooutovanadis/images/6/60/No_Image_Available.png/revision/latest?cb=20150730162527']
						]
				];

				$data = array_merge($data, $item);

				$this->woocommerce->post('products', $data);
		}

//--------------------------------------------------------------------
		public function checkProductById($item_id = 0){
				
				$item = '';
				try{
						$item = $this->woocommerce->get('products/'.(int)$item_id);
				}catch (Exception $e) {
						echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
						sa($e);
				}

				return $item;
		}
//--------------------------------------------------------------------

		public function updateProductPrice($id, $price){
				
				$data = [
						'product' => [
								'regular_price' => $price,
								'in_stock' => true
						]
				];

				$item = '';
				try{
						$item = $this->woocommerce->put("products/$id", $data);
				}catch (Exception $e) {
						echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
						sa($e);
				}

				return $item;
		}
//--------------------------------------------------------------------

		public function removeFromSale($id){
				
				$data = [
						'product' => [
								'in_stock' => false
						]
				];

				$item = '';
				try{
						$item = $this->woocommerce->put("products/$id", $data);
				}catch (Exception $e) {
						//echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
						//var_dump($e);
				}

				return $item;
		}


//--------------------------------------------------------------------

		public function run()    {


		}
}