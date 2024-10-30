<?php
session_start();

// end  of captcha

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
 
/**********************
 * Add  Widget
 *********************/
add_action( 'wp_enqueue_scripts', 'em_cost_calc_enqueue_public_scripts' );
	function em_cost_calc_enqueue_public_scripts(){
		wp_enqueue_style('em_custom_css', plugin_dir_url(__FILE__) . 'css/public/em-cost-calculator.css');
		wp_enqueue_script('em_custom_scripts', plugin_dir_url(__FILE__) . 'js/public/em-cost-calculator.js', array('jquery'));

}
add_action( 'admin_enqueue_scripts', 'em_cost_calc_enqueue_private_scripts' );
	function em_cost_calc_enqueue_private_scripts(){
		wp_enqueue_style('em_admin_css', plugin_dir_url(__FILE__) . 'css/private/em-cost-calculator.css');
}

/**********************
 * Add  Sidebar
 *********************/
function em_cost_calculator_sidebar() {
    register_sidebar(
        array (
            'name' => __( 'EM Cost Calulator', 'your-theme-domain' ),
            'id' => 'em-cost-calculator-sidebar'
        )
    );
}
add_action( 'widgets_init', 'em_cost_calculator_sidebar' );

/**********************
 * Add  Template
 *********************/
add_filter( 'template_include', 'emcc_page_template', 99 );

function emcc_page_template( $template ) {
    $file_name = 'emcc-page.php';

    if ( is_page( 'contact' ) ) {
        if ( locate_template( $file_name ) ) {
            $template = locate_template( $file_name );
        } else {
            // Template not found in theme's folder, use plugin's template as a fallback
            $template = plugin_dir_path(__FILE__) . $file_name;
        }
    }

    return $template;
}




class em_cost_calculator extends WP_Widget {

	/**
	 * Constructor
	 * 
	 * Registers the widget with WordPress. Sets up the widget
	 * ID, name, and description.
	 */
	public function __construct() {

		parent::__construct(
			'em_cost_calculator_id', // Base ID
			__( 'EM Cost Calculator', 'em_cost_calculator_widget' ), // Name
			array( 'description' => __( 'Set your price value and currency for each service via this plugin &  visitors will calculate the total cost.', 'em_cost_calculator_widget' ), ) // Args
		);
	
	}

	/**
	 * Admin Form
	 * 
	 * Displays the form in the admin area. This contains all the
	 * settings for the widget, including widget title and anything
	 * else you may have.
	 */
	public function form( $instance ) {

			$em_title = ! empty( $instance['em_title'] ) ? $instance['em_title'] : '';
			$em_currency = ! empty( $instance['em_currency'] ) ? $instance['em_currency'] : '$';  // currency sign for display on frontend
			$em_service1_name = ! empty( $instance['em_service1_name'] ) ? $instance['em_service1_name'] : '';
				$em_dropdown1 = ! empty( $instance['em_dropdown1'] ) ? $instance['em_dropdown1'] : '';
				$em_dropdown1_price = ! empty( $instance['em_dropdown1_price'] ) ? $instance['em_dropdown1_price'] : '';
				
				$em_dropdown2 = ! empty( $instance['em_dropdown2'] ) ? $instance['em_dropdown2'] : '';
				$em_dropdown2_price = ! empty( $instance['em_dropdown2_price'] ) ? $instance['em_dropdown2_price'] : '';
				
				$em_dropdown3 = ! empty( $instance['em_dropdown3'] ) ? $instance['em_dropdown3'] : '';
				$em_dropdown3_price = ! empty( $instance['em_dropdown3_price'] ) ? $instance['em_dropdown3_price'] : '';
			
			$em_service2_name = ! empty( $instance['em_service2_name'] ) ? $instance['em_service2_name'] : '';
			$em_service2_price = ! empty( $instance['em_service2_price'] ) ? $instance['em_service2_price'] : '';
			
			$em_service3_name = ! empty( $instance['em_service3_name'] ) ? $instance['em_service3_name'] : '';
			$em_service3_price = ! empty( $instance['em_service3_price'] ) ? $instance['em_service3_price'] : '';
			
			$em_service4_name = ! empty( $instance['em_service4_name'] ) ? $instance['em_service4_name'] : '';
			$em_service4_price = ! empty( $instance['em_service4_price'] ) ? $instance['em_service4_price'] : '';
			
			$em_service5_name = ! empty( $instance['em_service5_name'] ) ? $instance['em_service5_name'] : '';
			$em_service5_price = ! empty( $instance['em_service5_price'] ) ? $instance['em_service5_price'] : '';
			
			$emcc_order_checkbox_show = ! empty( $instance['emcc_order_checkbox_show'] ) ? $instance['emcc_order_checkbox_show'] : false;
			
			
			
		?>
		<p class="heading_note">If you don't want to show any section just leave it blank.</p>
		<p>			
			<label for="<?php echo $this->get_field_id( 'em_title' ); ?>"><?php _e( 'Title:', 'em_cost_calculator_widget' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'em_title' ); ?>" name="<?php echo $this->get_field_name( 'em_title' ); ?>" type="text" value="<?php echo esc_attr( $em_title ); ?>">
		</p>
		<p>			
			<label for="<?php echo $this->get_field_id( 'em_currency' ); ?>"><?php _e( 'Currency:', 'em_cost_calculator_widget' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'em_currency' ); ?>" name="<?php echo $this->get_field_name( 'em_currency' ); ?>" type="text" value="<?php echo esc_attr( $em_currency ); ?>">
		</p>
		<p class="service1_dashboard_widget_form_wrapper">			
			<label for="<?php echo $this->get_field_id( 'em_service1_name' ); ?>"><?php _e( 'Service-1 Name(Drop down Options):', 'em_cost_calculator_widget' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'em_service1_name' ); ?>" name="<?php echo $this->get_field_name( 'em_service1_name' ); ?>" type="text" value="<?php echo esc_attr( $em_service1_name ); ?>"> 
			<ul>
				<li class="service1_dashboard_form_option">
					<label class="options_field" for="<?php echo $this->get_field_id( 'em_dropdown1' ); ?>"><?php _e( 'Option-1 Name:', 'em_cost_calculator_widget' ); ?>
						<input class="widefat" id="<?php echo $this->get_field_id( 'em_dropdown1' ); ?>" name="<?php echo $this->get_field_name( 'em_dropdown1' ); ?>" type="text" value="<?php echo esc_attr( $em_dropdown1 ); ?>">
					</label>
					<label class="price_field"  for="<?php echo $this->get_field_id( 'em_dropdown1_price' ); ?>"><?php _e( 'Price :', 'em_cost_calculator_widget' ); ?></label>
					<input class="widefat price_field_input" id="<?php echo $this->get_field_id( 'em_dropdown1_price' ); ?>" name="<?php echo $this->get_field_name( 'em_dropdown1_price' ); ?>" type="number" value="<?php echo esc_attr( $em_dropdown1_price ); ?>"> 
					
				</li>
				<li class="service1_dashboard_form_option">
					<label for="<?php echo $this->get_field_id( 'em_dropdown2' ); ?>"><?php _e( 'Option-2 Name:', 'em_cost_calculator_widget' ); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id( 'em_dropdown2' ); ?>" name="<?php echo $this->get_field_name( 'em_dropdown2' ); ?>" type="text" value="<?php echo esc_attr( $em_dropdown2 ); ?>"> 
					<label class="price_field"  for="<?php echo $this->get_field_id( 'em_dropdown2_price' ); ?>"><?php _e( 'Price :', 'em_cost_calculator_widget' ); ?></label>
					<input class="widefat price_field_input" id="<?php echo $this->get_field_id( 'em_dropdown2_price' ); ?>" name="<?php echo $this->get_field_name( 'em_dropdown2_price' ); ?>" type="number" value="<?php echo esc_attr( $em_dropdown2_price ); ?>"> 
					
				</li>
				<li class="service1_dashboard_form_option">
					<label for="<?php echo $this->get_field_id( 'em_dropdown3' ); ?>"><?php _e( 'Option-1 Name:', 'em_cost_calculator_widget' ); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id( 'em_dropdown3' ); ?>" name="<?php echo $this->get_field_name( 'em_dropdown3' ); ?>" type="text" value="<?php echo esc_attr( $em_dropdown3 ); ?>"> 
					<label class="price_field"  for="<?php echo $this->get_field_id( 'em_dropdown3_price' ); ?>"><?php _e( 'Price :', 'em_cost_calculator_widget' ); ?></label>
					<input class="widefat price_field_input" id="<?php echo $this->get_field_id( 'em_dropdown3_price' ); ?>" name="<?php echo $this->get_field_name( 'em_dropdown3_price' ); ?>" type="number" value="<?php echo esc_attr( $em_dropdown3_price ); ?>"> 
					
				</li>
			</ul>
		</p>
		<p>			
			<label for="<?php echo $this->get_field_id( 'em_service2_name' ); ?>"><?php _e( 'Service-2 Name:', 'em_cost_calculator_widget' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'em_service2_name' ); ?>" name="<?php echo $this->get_field_name( 'em_service2_name' ); ?>" type="text" value="<?php echo esc_attr( $em_service2_name ); ?>"> 
			<label class="price_field"  for="<?php echo $this->get_field_id( 'em_service2_price' ); ?>"><?php _e( 'Price :', 'em_cost_calculator_widget' ); ?></label>
			<input class="widefat price_field_input" id="<?php echo $this->get_field_id( 'em_service2_price' ); ?>" name="<?php echo $this->get_field_name( 'em_service2_price' ); ?>" type="number" value="<?php echo esc_attr( $em_service2_price ); ?>"> 
			
		</p>
		<p>			
			<label for="<?php echo $this->get_field_id( 'em_service3_name' ); ?>"><?php _e( 'Service-3 Name:', 'em_cost_calculator_widget' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'em_service3_name' ); ?>" name="<?php echo $this->get_field_name( 'em_service3_name' ); ?>" type="text" value="<?php echo esc_attr( $em_service3_name ); ?>"> 
			<label class="price_field"  for="<?php echo $this->get_field_id( 'em_service3_price' ); ?>"><?php _e( 'Price :', 'em_cost_calculator_widget' ); ?></label>
			<input class="widefat price_field_input" id="<?php echo $this->get_field_id( 'em_service3_price' ); ?>" name="<?php echo $this->get_field_name( 'em_service3_price' ); ?>" type="number" value="<?php echo esc_attr( $em_service3_price ); ?>"> 
			
		</p>
		<p>			
			<label for="<?php echo $this->get_field_id( 'em_service4_name' ); ?>"><?php _e( 'Service-4 Name:', 'em_cost_calculator_widget' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'em_service4_name' ); ?>" name="<?php echo $this->get_field_name( 'em_service4_name' ); ?>" type="text" value="<?php echo esc_attr( $em_service4_name ); ?>"> 
			<label class="price_field"  for="<?php echo $this->get_field_id( 'em_service4_price' ); ?>"><?php _e( 'Price :', 'em_cost_calculator_widget' ); ?></label>
			<input class="widefat price_field_input" id="<?php echo $this->get_field_id( 'em_service4_price' ); ?>" name="<?php echo $this->get_field_name( 'em_service4_price' ); ?>" type="number" value="<?php echo esc_attr( $em_service4_price ); ?>"> 
		</p>
		<p>			
			<label for="<?php echo $this->get_field_id( 'em_service5_name' ); ?>"><?php _e( 'Service-5 Name:', 'em_cost_calculator_widget' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'em_service5_name' ); ?>" name="<?php echo $this->get_field_name( 'em_service5_name' ); ?>" type="text" value="<?php echo esc_attr( $em_service5_name ); ?>"> 
			<label class="price_field"  for="<?php echo $this->get_field_id( 'em_service5_price' ); ?>"><?php _e( 'Price :', 'em_cost_calculator_widget' ); ?></label>
			<input class="widefat price_field_input" id="<?php echo $this->get_field_id( 'em_service5_price' ); ?>" name="<?php echo $this->get_field_name( 'em_service5_price' ); ?>" type="number" value="<?php echo esc_attr( $em_service5_price ); ?>"> 
		</p>
		
		<p>
			<input disabled="disabled" class="disabled_checkbox" id="" name="" type="checkbox" value="1" />
			<label for=""><?php _e( 'Show "Order Now" when calculate button is clicked. When the Order Now button is clicked the <b class="bold_text">Order Form</b> will be appeared. <a href="https://e-motahar.com/cost-calculator-wordpress-plugin/"> <b class="bold_pro">( Pro  )</b> </a>', 'text_domain' ); ?></label>
		</p>
		<p>
			<input disabled="disabled" class="disabled_checkbox" id="" name="" type="checkbox" value="1" />
			<label for=""><?php _e( 'Send an <b class="bold_text">Auto Email</b> instantly to the customer when an order is placed. The email will contain your "Thank You" message & the order details.<a href="https://e-motahar.com/cost-calculator-wordpress-plugin/"> <b class="bold_pro">( Pro  )</b> </a>', 'text_domain' ); ?></label>
		</p>
		<p>
			<input disabled="disabled" id="" name="" type="checkbox" value="1"  />
			<label for=""><?php _e( 'Get an <b class="bold_text">Auto Email</b> instantly as admin when an order is placed. The email will contain the order details.<a href="https://e-motahar.com/cost-calculator-wordpress-plugin/"> <b class="bold_pro">( Pro  )</b> </a>', 'text_domain' ); ?></label>
		</p>
		
		<p>
			<input disabled="disabled" id="" name="" type="checkbox" value="1"  />
			<label for=""><?php _e( 'Show <b class="bold_text">Pay Now</b> paypal button when an order is placed. Customers will able to pay via their debit/credit card or paypal account. <a href="https://e-motahar.com/cost-calculator-wordpress-plugin/"> <b class="bold_pro">( Pro  )</b> </a>', 'text_domain' ); ?></label>
		</p>
		<p>
			<label for=""><?php _e( 'Set your Paypal email address. <a href="https://e-motahar.com/cost-calculator-wordpress-plugin/"> <b class="bold_pro">( Pro  )</b> </a>', 'text_domain' ); ?></label>
			<input disabled="disabled" id="" name="" type="email" value=""> 
		</p>

		 <p>
		  <label for="<?php echo $this->get_field_id('emcc_paypal_currency'); ?>">Set Paypal Currency:<a href="https://e-motahar.com/cost-calculator-wordpress-plugin/"> <b class="bold_pro">( Pro  )</b> </a>
			<select disabled="disabled" class='widefat' id="" name="" type="text">
			  <option value='USD'>
				USD
			  </option>
			  <option value='AUD'>
				AUD
			  </option> 
			 
			  
			  
			</select>                
		  </label>
		 </p>
			<!-- Widget Title field END -->

		<?php
		
	}

	/**
	 * Update Values
	 *
	 * Sanitize widget form values as they are saved. Make sure
	 * everything is safe to be added in the database.
	 */
	public function update( $new_instance, $old_instance ) {
		
		$instance          = array();
		$instance['em_title'] = ( ! empty( $new_instance['em_title'] ) ) ? strip_tags( $new_instance['em_title'] ) : '';
		$instance['em_currency'] = ( ! empty( $new_instance['em_currency'] ) ) ? strip_tags( $new_instance['em_currency'] ) : '';
		$instance['description'] = ( ! empty( $new_instance['description'] ) ) ? strip_tags( $new_instance['description'] ) : '';
		$instance['em_service1_name'] = ( ! empty( $new_instance['em_service1_name'] ) ) ? strip_tags( $new_instance['em_service1_name'] ) : '';
			$instance['em_dropdown1'] = ( ! empty( $new_instance['em_dropdown1'] ) ) ? strip_tags( $new_instance['em_dropdown1'] ) : '';
			$instance['em_dropdown1_price'] = ( ! empty( $new_instance['em_dropdown1_price'] ) ) ? strip_tags( $new_instance['em_dropdown1_price'] ) : '';
			
			$instance['em_dropdown2'] = ( ! empty( $new_instance['em_dropdown2'] ) ) ? strip_tags( $new_instance['em_dropdown2'] ) : '';
			$instance['em_dropdown2_price'] = ( ! empty( $new_instance['em_dropdown1_price'] ) ) ? strip_tags( $new_instance['em_dropdown2_price'] ) : '';
			
			$instance['em_dropdown3'] = ( ! empty( $new_instance['em_dropdown3'] ) ) ? strip_tags( $new_instance['em_dropdown3'] ) : '';
			$instance['em_dropdown3_price'] = ( ! empty( $new_instance['em_dropdown1_price'] ) ) ? strip_tags( $new_instance['em_dropdown3_price'] ) : '';
			
		
		$instance['em_service2_name'] = ( ! empty( $new_instance['em_service2_name'] ) ) ? strip_tags( $new_instance['em_service2_name'] ) : '';
			$instance['em_service2_price'] = ( ! empty( $new_instance['em_service2_price'] ) ) ? strip_tags( $new_instance['em_service2_price'] ) : '';
		
		$instance['em_service3_name'] = ( ! empty( $new_instance['em_service3_name'] ) ) ? strip_tags( $new_instance['em_service3_name'] ) : '';
		$instance['em_service3_price'] = ( ! empty( $new_instance['em_service3_price'] ) ) ? strip_tags( $new_instance['em_service3_price'] ) : '';
		
		$instance['em_service4_name'] = ( ! empty( $new_instance['em_service4_name'] ) ) ? strip_tags( $new_instance['em_service4_name'] ) : '';
		$instance['em_service4_price'] = ( ! empty( $new_instance['em_service4_price'] ) ) ? strip_tags( $new_instance['em_service4_price'] ) : '';
		
		$instance['em_service5_name'] = ( ! empty( $new_instance['em_service5_name'] ) ) ? strip_tags( $new_instance['em_service5_name'] ) : '';
		$instance['em_service5_price'] = ( ! empty( $new_instance['em_service5_price'] ) ) ? strip_tags( $new_instance['em_service5_price'] ) : '';
		
		$instance['emcc_order_checkbox_show'] = isset( $new_instance['emcc_order_checkbox_show'] ) ? 1 : false;
		


		return $instance;
		
	}

	/**
	 * Front-End Display
	 * 
	 * Display the contents of the widget on the front-end of the
	 * site.
	 */
	public function widget( $args, $instance ) {
		
		extract( $args );
		
		$emcc_order_checkbox_show = ! empty( $instance['emcc_order_checkbox_show'] ) ? $instance['emcc_order_checkbox_show'] : false;
		
	
		echo $args['before_widget'];
		
		global $wpdb;
		
		$em_cost_calc_table_name = $wpdb->prefix . 'emotahar_cost_calc';
		
		$em_cost_calc_current_prices = $wpdb->get_row( "SELECT * FROM $em_cost_calc_table_name ORDER BY id DESC LIMIT 1" );
		?>
<div class="em_widget_wrapper">
	<div class="em_widget_heading">
		<h2><?php 
			// Display the widget em_title.
			if ( ! empty( $instance['em_title'] ) ) {
				echo apply_filters( 'widget_title', $instance['em_title'] ) ;
			} ?>
		</h2>
	</div>
	
	
	<div class="order_now_form_wrapper">
		<form id="form1" name="form1" method="POST" action="" >
			<?php 
				// Display the widget title.
				if ( ! empty( $instance['em_service1_name'] ) ) { ?>
			<div class="em_plugin_select_wrapper">
				<div class="label_wrapper">
					<label class="specfic_service_label">
						<span>
							<?php echo  apply_filters( 'widget_title', $instance['em_service1_name'] ) ; ?>
						 </span>
					</label>
				</div>
				
				<select id="select" name="select" data-placeholder="<?php echo  apply_filters( 'widget_title', $instance['em_service1_name'] ) ; ?>" class="">
					<option value="0" selected class="sum">None</option>
					<!-- Display the Drop down option. -->
					<?php if ( ! empty( $instance['em_dropdown1'] ) ) { ?>
					<option value="<?php echo  apply_filters( 'widget_title', $instance['em_dropdown1_price'] ) ; ?>" class="sums">
						<?php 
							echo  apply_filters( 'widget_title', $instance['em_dropdown1'] ) ; ?>   <?php
							// Display the currency.
							if ( ! empty( $instance['em_currency'] ) ) {
								echo  apply_filters( 'widget_title', $instance['em_currency'] ) ;
							};
						?>
						<?php	
							//echo $em_cost_calc_current_prices->service_1;
							echo  apply_filters( 'widget_title', $instance['em_dropdown1_price'] ) ;
						?>
					</option>
					<?php } ?>
					<!-- Display the Drop down option. -->
					<?php if ( ! empty( $instance['em_dropdown2'] ) ) { ?>
					<option value="<?php echo  apply_filters( 'widget_title', $instance['em_dropdown2_price'] ) ; ?>" class="sums">
						<?php 
							echo  apply_filters( 'widget_title', $instance['em_dropdown2'] ) ; ?>  <?php
							// Display the currency.
							if ( ! empty( $instance['em_currency'] ) ) {
								echo  apply_filters( 'widget_title', $instance['em_currency'] ) ;
							};
						?>
						<?php
							//echo $em_cost_calc_current_prices->service_2;
							echo  apply_filters( 'widget_title', $instance['em_dropdown2_price'] ) ;
						?>
					</option>
					<?php } ?>
					<!-- Display the Drop down option. -->
					<?php if ( ! empty( $instance['em_dropdown3'] ) ) { ?>
					<option value="<?php echo  apply_filters( 'widget_title', $instance['em_dropdown3_price'] ) ; ?>" class="sums">
						<?php 
							echo  apply_filters( 'widget_title', $instance['em_dropdown3'] ) ; ?>  <?php
							// Display the currency.
							if ( ! empty( $instance['em_currency'] ) ) {
								echo  apply_filters( 'widget_title', $instance['em_currency'] ) ;
							};
						?>
						<?php	
							//echo $em_cost_calc_current_prices->service_3;
							echo  apply_filters( 'widget_title', $instance['em_dropdown3_price'] ) ;
						?>
					</option>
					<?php } ?>
				</select>
			</div>
			<?php } ?>
			<?php 
				// Display the widget title.
				if ( ! empty( $instance['em_service2_name'] ) ) { ?>
			<div class="checkbox_wrapper ">
				<div class="">
					<div class='checkbox'>
					  <label class='checkbox__container'>
						<input class='checkbox__toggle sum sum1' type="checkbox" name="service_2" value="<?php echo  apply_filters( 'widget_title', $instance['em_service2_price'] ) ; ?>" id="qr1" />
						<span class='checkbox__checker'></span>
						<span class='checkbox__cross'></span>
						<span class='checkbox__ok'></span>
						<svg class='checkbox__bg' space='preserve' style='enable-background:new 0 0 110 43.76;' version='1.1' viewbox='0 0 110 43.76'>
						  <path class='shape' d='M88.256,43.76c12.188,0,21.88-9.796,21.88-21.88S100.247,0,88.256,0c-15.745,0-20.67,12.281-33.257,12.281,S38.16,0,21.731,0C9.622,0-0.149,9.796-0.149,21.88s9.672,21.88,21.88,21.88c17.519,0,20.67-13.384,33.263-13.384,S72.784,43.76,88.256,43.76z'></path>
						</svg>
					  </label>
					</div>
				</div>
				<div class="label_wrapper">
					<label class="specfic_service_label">
						<span class="style_options">					
							<?php echo  apply_filters( 'widget_title', $instance['em_service2_name'] ) ; ?>
								<?php 
								// Display the currency.
								if ( ! empty( $instance['em_currency'] ) ) {
									echo  apply_filters( 'widget_title', $instance['em_currency'] ) ;
								};
							?>
							<?php echo  apply_filters( 'widget_title', $instance['em_service2_price'] ) ; ?> 
						</span>
					</label>
				</div>
			</div>
			<?php } ?>
			<?php 
				// Display the widget title.
				if ( ! empty( $instance['em_service3_name'] ) ) { ?>
			<div class="checkbox_wrapper ">
				<div class="">
					<div class='checkbox'>
					  <label class='checkbox__container'>
						<input class='checkbox__toggle sum sum2' type="checkbox" name="service_3" value="<?php echo  apply_filters( 'widget_title', $instance['em_service3_price'] ) ;  ?>" id="qr1" />
						<span class='checkbox__checker'></span>
						<span class='checkbox__cross'></span>
						<span class='checkbox__ok'></span>
						<svg class='checkbox__bg' space='preserve' style='enable-background:new 0 0 110 43.76;' version='1.1' viewbox='0 0 110 43.76'>
						  <path class='shape' d='M88.256,43.76c12.188,0,21.88-9.796,21.88-21.88S100.247,0,88.256,0c-15.745,0-20.67,12.281-33.257,12.281,S38.16,0,21.731,0C9.622,0-0.149,9.796-0.149,21.88s9.672,21.88,21.88,21.88c17.519,0,20.67-13.384,33.263-13.384,S72.784,43.76,88.256,43.76z'></path>
						</svg>
					  </label>
					</div>
				</div>
				<div class="label_wrapper">
					<label class="specfic_service_label">
						<span class="style_options">
									<?php echo  apply_filters( 'widget_title', $instance['em_service3_name'] ) ; ?>
							 <?php
							 // Display the currency.
								if ( ! empty( $instance['em_currency'] ) ) {
									echo  apply_filters( 'widget_title', $instance['em_currency'] ) ;
								};
							?>
							<?php	
								echo  apply_filters( 'widget_title', $instance['em_service3_price'] ) ;
							 ?>
						</span>
					</label>
				</div>
			</div>
			<?php } ?>
			<?php 
				// Display the widget title.
				if ( ! empty( $instance['em_service4_name'] ) ) { ?>
				<div class="checkbox_wrapper ">
					<div class="">
						<div class='checkbox'>
						  <label class='checkbox__container'>
							<input class='checkbox__toggle sum sum3' type="checkbox" name="service_4" value="<?php echo  apply_filters( 'widget_title', $instance['em_service4_price'] ) ; ?>" id="qr1" />
							<span class='checkbox__checker'></span>
							<span class='checkbox__cross'></span>
							<span class='checkbox__ok'></span>
							<svg class='checkbox__bg' space='preserve' style='enable-background:new 0 0 110 43.76;' version='1.1' viewbox='0 0 110 43.76'>
							  <path class='shape' d='M88.256,43.76c12.188,0,21.88-9.796,21.88-21.88S100.247,0,88.256,0c-15.745,0-20.67,12.281-33.257,12.281,S38.16,0,21.731,0C9.622,0-0.149,9.796-0.149,21.88s9.672,21.88,21.88,21.88c17.519,0,20.67-13.384,33.263-13.384,S72.784,43.76,88.256,43.76z'></path>
							</svg>
						  </label>
						</div>
					</div>
					<div class="label_wrapper">
						<label class="specfic_service_label">
							<span class="style_options">
								<?php echo  apply_filters( 'widget_title', $instance['em_service4_name'] ) ; ?>    
								 <?php
									 // Display the currency.
									if ( ! empty( $instance['em_currency'] ) ) {
										echo  apply_filters( 'widget_title', $instance['em_currency'] ) ;
									};
								?>
								<?php	
									echo  apply_filters( 'widget_title', $instance['em_service4_price'] ) ;
								 ?>
							</span>
						</label>
					</div>
				</div>
			<?php } ?>
			<?php 
				// Display the widget title.
				if ( ! empty( $instance['em_service5_name'] ) ) { ?>
				<div class="checkbox_wrapper ">
					<div class="">
						<div class='checkbox'>
						  <label class='checkbox__container'>
							<input class='checkbox__toggle sum sum4' type="checkbox" name="service_5" value="<?php echo  apply_filters( 'widget_title', $instance['em_service5_price'] ) ; ?>" id="qr1" />
							<span class='checkbox__checker'></span>
							<span class='checkbox__cross'></span>
							<span class='checkbox__ok'></span>
							<svg class='checkbox__bg' space='preserve' style='enable-background:new 0 0 110 43.76;' version='1.1' viewbox='0 0 110 43.76'>
							  <path class='shape' d='M88.256,43.76c12.188,0,21.88-9.796,21.88-21.88S100.247,0,88.256,0c-15.745,0-20.67,12.281-33.257,12.281,S38.16,0,21.731,0C9.622,0-0.149,9.796-0.149,21.88s9.672,21.88,21.88,21.88c17.519,0,20.67-13.384,33.263-13.384,S72.784,43.76,88.256,43.76z'></path>
							</svg>
						  </label>
						</div>
					</div>
					<div class="label_wrapper">
						<label class="specfic_service_label">
							<span class="style_options">
								<?php echo  apply_filters( 'widget_title', $instance['em_service5_name'] ) ; ?>    
								 <?php
									 // Display the currency.
									if ( ! empty( $instance['em_currency'] ) ) {
										echo  apply_filters( 'widget_title', $instance['em_currency'] ) ;
									};
								?>
								<?php	
									echo  apply_filters( 'widget_title', $instance['em_service5_price'] ) ;
								 ?>
							</span>
						</label>
					</div>
				</div>
			<?php } ?>
			<br/>
			<span id="calc" class="em_submit">Calculate</span>
			<br/>
			<br/>
			<div class="total_wrapper">
				<p>Total : <?php // Display the currency.
								if ( ! empty( $instance['em_currency'] ) ) {
									echo  apply_filters( 'widget_title', $instance['em_currency'] ) ;
									
								}; 
							?>
				<span id="payment-total">0</span></p>
				<input type="hidden"  id="total_cost_for_db" name="total_cost_for_db" value="">
			</div>
			
		</form>

		
	</div>

	
</div>

	
<script>


(function($) {
	//// calculation
	$(document).ready(function(){ 
			 $('.em_plugin_select_wrapper select').awselect() 
		});
	
	var total_price;
	(function() {
		$( ".em_submit" ).on( "click", function() {

		// service1 with select options

		var service1 = $('#select').val();
		if (!service1) {
			service1 = 0;
		}
		var service1 = parseInt(service1, 10);
		
		// service2 with checkbox
		var service2 = 0;
		if ($('.sum1').is(":checked"))
			{
			  // it is checked
			  service2 = $('.sum1').val();
			}
		// convert to integer
		var service2 = parseInt(service2, 10);
		
		// service3 with checkbox
		var service3 = 0;
		if ($('.sum2').is(":checked"))
			{
			  // it is checked
			  service3 = $('.sum2').val();
			}
		// convert to integer
		var service3 = parseInt(service3, 10);
		
		// service4 with checkbox
		var service4 = 0;
		if ($('.sum3').is(":checked"))
			{
			  service4 = $('.sum3').val();
			}
		// convert to integer
		var service4 = parseInt(service4, 10);
		
		// service5 with checkbox
		var service5 = 0;
		if ($('.sum4').is(":checked"))
			{
			  service5 = $('.sum4').val();
			}
		// convert to integer
		var service5 = parseInt(service5, 10);
				  
		 var total_cost = service1 + service2 + service3 + service4 + service5;
				  
			//console.log(total_cost);
			$('#payment-total').text(total_cost);
			$('#total_cost_on_order_div').text(total_cost);
			
			$('#total_cost_for_db').val(total_cost);
			$(".bring_order_form_btn_wrapper").css("display", "block");
		});
	})();
	
	$(".emcc_cross_btn").click(function(){
		$(".order_container").toggleClass("order_div");
		$(".order_container").toggleClass("order_div_hider");
	});
	

		  

			

	})(jQuery);

</script>
		
<?php
	echo $args['after_widget'];
	
	
	$emcc_order_details_from_fronend = array(
		'customer_name' => '',
		'customer_email' => '',
		'customer_address' => '',
		'customer_query' => '',
		'select' => '',
		'service_2' => '',
		'service_3' => '',
		'service_4' => '',
		'service_5' => '',

		'total_cost_for_db' => '',
	);
	
	
	$emcc_order_details_from_item = shortcode_atts( $emcc_order_details_from_fronend, $_REQUEST );
	
	// retrieve default mail table values
	
	global $wpdb;
	
	$emcc_default_mail_user_table_name = $wpdb->prefix . 'emcc_default_mail_user';
	
	$emcc_default_mail_table_values = $wpdb->get_row( "SELECT * FROM $emcc_default_mail_user_table_name ORDER BY id DESC LIMIT 1" );
	
	
	
	
	if (isset($_POST['submit'])) {
		
		if( $_POST["captcha"]&&$_POST["captcha"]!=""&&$_SESSION["code"]==$_POST["captcha"]){
		
		// If captcha okay then go you stuff
		global $wpdb;
		
		global $emcc_order_details_db_version;

		$emcc_order_details_table_name = $wpdb->prefix . 'emcc_order_details';
	 
		 // Safe data insertion with prepare method ( successful )
		$wpdb->query( $wpdb->prepare( 
					"
						INSERT INTO $emcc_order_details_table_name
						( customer_name, customer_email, customer_address, customer_query, service_1_costs, service_2_costs, service_3_costs, service_4_costs, service_5_costs, total_cost )
						VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )
					", 

						$emcc_order_details_from_item
				) );

				
		
		
		
		
		$c_service_1 = $_POST['select'] = isset($_POST['select']) ? $_POST['select'] : '';
		
		$c_service_2 = $_POST['service_2'] = isset($_POST['service_2']) ? $_POST['service_2'] : '';
		
		$c_service_3 = $_POST['service_3'] = isset($_POST['service_3']) ? $_POST['service_3'] : '';
				
		$c_service_4 = $_POST['service_4'] = isset($_POST['service_4']) ? $_POST['service_4'] : '';
		
		$c_service_5 = $_POST['service_5'] = isset($_POST['service_5']) ? $_POST['service_5'] : '';
		
		$c_total_cost = $_POST['servitotal_cost_for_dbe_4'] = isset($_POST['total_cost_for_db']) ? $_POST['total_cost_for_db'] : '';
		
		$c_name = $_POST['customer_name'] = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';
		
		$c_email = $_POST['customer_email'] = isset($_POST['customer_email']) ? $_POST['customer_email'] : '';
		
		$c_address  = $_POST['customer_address'] = isset($_POST['customer_address']) ? $_POST['customer_address'] : '';
		
		$c_query = isset($_POST['customer_query']) ? $_POST['customer_query'] : '';
		
		
		// Customers Email
		
		
		
		// Admin's Email on order placing  of customers
				
	
		echo '
			<div class="emcc_thank_you_message">
				
				<p>Thank You ' . $c_name  . ' </br>
				Your order has been placed</p>
				<div class="order_div_total_cost_wrapper">
					<p id="total_cost_on_order_div">Your Total Cost : '; 
									if ( ! empty( $instance['em_currency'] ) ) {
										echo  apply_filters( 'widget_title', $instance['em_currency'] ) ;
									}; echo '
					'; echo $c_total_cost; echo '</p>
				</div>
				<div class="emcc_pay_cross_btn">
					<a href="javascript: history.go(-1)"><p> OK </p></a>
				</div>
				';
				
				// Display something if emcc_order_checkbox_show is true
				
			echo '
				
				
			</div>
		';
		?>
		
		<script>
			(function($) {
				$('.emcc_pay_cross_btn').on('click', function() { 
					$('.thank_you_message').addClass("thank_you_message_hider");
				});
				

			})(jQuery);
		</script>
					
		
		
		<?php
		
			}
			else //  if something error
				{
				echo '
					<div class="error_message_div">
						<h4>ERROR</h4>
						<p>Please fill the boxes properly.</p>
						<a href="javascript: history.go(-1)">Go Back & Try Again</a>
					</div>
			
				';
				die();
				}

	 
		}
	


	}
	

}
