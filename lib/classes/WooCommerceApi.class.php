<?php

class WooCommerceApi{
		
		function __construct(){

				$this->woocommerce = new \Automattic\WooCommerce\Client(
						'http://gig-games.de/', // Your store URL
						'ck_410bb472d79a017b47c7ff2b70cfee4120904b09', // Your consumer key
						'cs_db96dd892f781080643d93f966246b8a78704a4a', // Your consumer secret
						['version' => 'v3'] // WooCommerce API version
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
						//echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
						//var_dump($e);
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
						//echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
						//var_dump($e);
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