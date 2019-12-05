<?php

class WooCommerceApi{

		private $woocommerce;
		
		function __construct(){

				$this->woocommerce = new \Automattic\WooCommerce\Client(
						'https://gig-games.de/', // Your store URL
						WOO_CK, // Your consumer key
						WOO_CS, // Your consumer secret
						[
				        'wp_api' => true,
				        'version' => 'wc/v3',
				        // 'version' => 'v3',
						 // 'verify_ssl'=>false,
						 // 'ssl_enabled'=>false,
						 'query_string_auth' => true,
						 // 'oauth_timestamp' => time() + (60*60)
						 // 'user_agent'=>'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36'
						 ]
				);
		}

//--------------------------------------------------------------------
		/*
		$data = [
		    'name' => 'Premium Quality',
		    'type' => 'simple',
		    'regular_price' => '21.99',
		    'description' => 'Pellentesque habileo.',
		    'short_description' => 'Pellentesque habitt malesuada fames ac turpis egestas.',
		    'categories' => [
		        [
		            'id' => 9
		        ],
		    ],
		    'images' => [
		        [
		            'src' => 'http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_2_front.jpg'
		        ],
		    ]
		];
		*/
		public function addProduct($data){

				return $this->woocommerce->post('products', $data);
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

		public static function updateProductPrice($id, $price){
				

				$data = [
					'regular_price' => $price,
					'stock_status' => 'instock', // instock / outofstock
				];

				$item = post_curl('https://hot-body.net/parser/ajax-controller.php', [
					'function' => 'ajax_hot_do_woocommerce_api_request',
					'method' => 'put',
					'endpoint' => "products/$id",
					'data' => $data,
				]);

				// $data = [
				// 		'product' => [
				// 				'regular_price' => $price,
				// 				'in_stock' => true
				// 		]
				// ];

				// $item = '';
				// try{
				// 		$item = $this->woocommerce->put("products/$id", $data);
				// }catch (Exception $e) {
				// 		echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
				// 		sa($e);
				// }

				return $item['res'];
		}
//--------------------------------------------------------------------

		public static function removeFromSale($id){
				
				// $data = [
				// 		'product' => [
				// 				'in_stock' => false
				// 		]
				// ];

				// $item = '';
				// try{
				// 		$item = $this->woocommerce->put("products/$id", $data);
				// }catch (Exception $e) {
				// 		//echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
				// 		//var_dump($e);
				// }

				$data = [
					'stock_status' => 'outofstock', // instock / outofstock
				];

				$item = post_curl('https://hot-body.net/parser/ajax-controller.php', [
					'function' => 'ajax_hot_do_woocommerce_api_request',
					'method' => 'put',
					'endpoint' => "products/$id",
					'data' => $data,
				]);

				return $item['res'];
		}
//--------------------------------------------------------------------

		public static function MarkAsShipped($id){
				
			// $data = [
			//     'status' => 'completed'
			// ];

			// print_r($woocommerce->put('orders/727', $data));

			$data = [
				'status' => 'completed'
			];

			$item = post_curl('https://hot-body.net/parser/ajax-controller.php', [
				'function' => 'ajax_hot_do_woocommerce_api_request',
				'method' => 'put',
				'endpoint' => "orders/$id",
				'data' => $data,
			]);

			return $item['res'];
		}


//--------------------------------------------------------------------

		public function run()    {


		}
}