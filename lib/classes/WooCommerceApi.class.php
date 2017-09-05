<?php

class WooCommerceApi{

		private $woocommerce;
		
		function __construct(){

				$this->woocommerce = new \Automattic\WooCommerce\Client(
						'https://gig-games.de/', // Your store URL
						WOO_CK, // Your consumer key
						WOO_CS, // Your consumer secret
						['version' => 'v3',
						 'verify_ssl'=>false,
						 // 'ssl_enabled'=>false,
						 'query_string_auth' => true,
						 // 'oauth_timestamp' => time() + (60*60)
						 // 'user_agent'=>'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36'
						 ]
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