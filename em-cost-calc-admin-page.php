<?php
// Admin page
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
function em_cost_calc_admin_menu() {
 global $em_calc_admin_page;
	add_menu_page( 'EM Costing', 'EMCC Customers', 'manage_options', 'em_add_data', 'em_cost_calc_form_page_handler', 'dashicons-admin-multisite', 81 ) ;
	add_submenu_page( 'em_add_data', 'About EMCC', 'About EMCC', 'manage_options', 'em_payment_settings', 'payment_settings_page' ) ;
}
add_action( 'admin_menu', 'em_cost_calc_admin_menu' );

// EM Costing Home Page
function em_cost_calc_form_page_handler(){
		
	global $wpdb;
  
	$emcc_order_details_table_name = $wpdb->prefix . 'emcc_order_details';
  
	  $per_page = 15;
	  $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
	  if ($page > 0) {
		  $offset = $page * $per_page - $per_page;
	  } else {
		  $offset = $page;
	  }
	  $the_post = "select * from $emcc_order_details_table_name order by id desc limit $per_page offset $offset ";
			  
	  $emcc_order_list = $wpdb->get_results($the_post, ARRAY_A);
	  echo '
		  <div class="customer_list_wrapper">
			  <div id="page-wrap">
				  <div class="customer_list_heading">
					  <h1>Your Customers Orders List</h1>
				  </div>
				  
				  
				  <table>
					  <thead>
					  <tr>
						  <th>Order ID</th>
						  <th>Order Date</th>
						  <th>Customer Name</th>
						  <th>Customer Email</th>
						  <th>Customer Address</th>
						  <th>Total Cost</th>
						  <th>Service-1</th>
						  <th>Service-2</th>
						  <th>Service-3</th>
						  <th>Service-4</th>
						  <th>Service-5</th>
					  </tr>
					  </thead>
					  <tbody>
					  '; foreach ( $emcc_order_list as $emcc_order_list ) : echo'
					  <tr>
						  <td>'. $emcc_order_list ['ID'] .'</td>
						  <td>'. $emcc_order_list ['Order_time'] .'</td>
						  <td>'. $emcc_order_list ['customer_name'] .'</td>
						  <td>'. $emcc_order_list ['customer_email'] .'</td>
						  <td>'. $emcc_order_list ['customer_address'] .'</td>
						  <td>'. $emcc_order_list ['total_cost'] .'</td>
						  <td>'. $emcc_order_list ['service_1_costs'] .'</td>
						  <td>'. $emcc_order_list ['service_2_costs'] .'</td>
						  <td>'. $emcc_order_list ['service_3_costs'] .'</td>
						  <td>'. $emcc_order_list ['service_4_costs'] .'</td>
						  <td>'. $emcc_order_list ['service_5_costs'] .'</td>
  
					  </tr>
					  '; endforeach; echo '
					  </tbody>
				  </table>
			  </div>
		  </div>
	  ';
	  
	  // Pagination
	  $total = $wpdb->get_var("SELECT count(ID) from $emcc_order_details_table_name ");
	  echo '<div class="pagination_wrapper">';
		  echo paginate_links(array(
			  'base' => add_query_arg('cpage', '%#%'),
			  'format' => '',
			  'prev_text' => __('&laquo;'),
			  'next_text' => __('&raquo;'),
			  'total' => ceil($total / $per_page),
			  'current' => $page
		  ));
	  echo '</div>';
	
}

	
	


// EM Costing Customers-List Page
function payment_settings_page(){


	echo '
		<div class="upsomming_features">
			<h1>EM Cost Calculator</h1>
			<p>Designed & Developed by:</p>
			<h2>Motahar Hossain</h2>
			<h3>For any query and any support or customization please contact me via email : motahar1201123@gmail.com</h3>
			<h2><a href="https://e-motahar.com/cost-calculator-wordpress-plugin/"> <b class="bold_pro"> Upgrade to Pro Version </b> </a></h2>
		</div>
	';
		
}








