<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://xfinitysoft.com/
 * @since      3.0.0
 *
 * @package    WC_Order_Limit
 * @subpackage WC_Order_Limit/admin
 */

use Automattic\WooCommerce\Admin\BlockTemplates\BlockTemplateInterface;
use Automattic\WooCommerce\Admin\Features\ProductBlockEditor\ProductTemplates\ProductFormTemplateInterface;
use Automattic\WooCommerce\Admin\BlockTemplates\BlockInterface;
use Automattic\WooCommerce\Admin\Features\ProductBlockEditor\BlockRegistry;
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WC_Order_Limit
 * @subpackage WC_Order_Limit/admin
 * @author     xfinitysoft <support@xfinitysoft.com>
 */
class WC_Order_Limit_Block {

	/**
	 * The ID of this plugin.
	 *
	 * @since    3.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    3.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    5.3.6
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		add_action( 'init', array( $this, 'extension_wc_order_limit_block_init' ) );
	}
	/**
	 * Initialize block registry.
	 *
	 * @since    5.3.6
	 */
	public function extension_wc_order_limit_block_init() {
		// phpcs:ignore.
		if ( isset( $_GET['page'] ) && 'wc-admin' === $_GET['page'] ) {
			BlockRegistry::get_instance()->register_block_type_from_metadata( WCOL_MAIN_PLUGIN_DIR . '/build' );
		}
	}
	/**
	 * Register of block in api.
	 *
	 * @since    3.0.0
	 */
	public function wcol_register_block() {
		add_action( 'woocommerce_layout_template_after_instantiation', array( $this, 'wcol_add_block' ), 10, 3 );
		register_rest_field(
			'product',
			'wcol_rules',
			array(
				'get_callback' => array( $this, 'wcol_get_rules' ),
				'schema'       => null,
			)
		);
		register_rest_route(
			'wcol/v1',
			'/roles',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'wcol_get_wp_roles' ),
				'permission_callback' => array( $this, 'wcol_permission_callback' ),
			)
		);
		register_rest_route(
			'wcol/v1',
			'/users',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'wcol_get_wp_users' ),
				'permission_callback' => array( $this, 'wcol_permission_callback' ),
			)
		);
		register_rest_route(
			'wcol/v1',
			'/payment-methods',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'wcol_get_wp_payment_methods' ),
				'permission_callback' => array( $this, 'wcol_permission_callback' ),
			)
		);
	}
	/**
	 * Check the permission of user.
	 *
	 * @since    5.3.6
	 */
	public function wcol_permission_callback() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error( 'rest_forbidden', 'You do not have permissions to access this endpoint', array( 'status' => 403 ) );
		} else {
			return true;
		}
	}
	/**
	 * Add Meta data in api.
	 *
	 * @since    5.3.6
	 * @param    arrray $rule  Array of rule.
	 */
	public function wcol_get_meta( $rule ) {
		$post_id   = $rule['id'];
		$meta_data = array();
		$type      = get_post_meta( $post_id, 'wcol-rule-type', true );
		if ( 'products' === $type ) {
			$meta_data = get_post_meta( $post_id, 'wcol-post-rules', true );
			if ( 'on' === $meta_data['enable-for-users'] && 'specific-roles' === $meta_data['user-type'] ) {
				global $wp_roles;
				$roles = array();
				if ( is_array( $wp_roles->roles ) ) {
					foreach ( $wp_roles->roles as $key => $role ) {
						if ( in_array( $key, $meta_data['rule-roles'], true ) ) {
							$roles[] = array(
								'key'   => $key,
								'label' => $role['name'],
								'value' => array( 'id' => $key ),
							);
						}
					}
				}
				$meta_data['rule-roles'] = $roles;
			}
			if ( 'on' === $meta_data['enable-for-users'] && 'specific-users' === $meta_data['user-type'] ) {
				$users     = get_users( array( 'fields' => array( 'id', 'display_name' ) ) );
				$all_users = array();
				if ( is_array( $users ) && ! empty( $users ) ) {
					foreach ( $users  as $user ) {
						if ( in_array( $user->ID, $meta_data['rule-users'], true ) ) {
							$all_users[] = array(
								'key'   => $user->ID,
								'label' => $user->display_name,
								'value' => array( 'id' => $user->ID ),
							);
						}
					}
				}
				$meta_data['rule-users'] = $all_users;
			}
			if ( 'on' === $meta_data['enable-for-payment'] ) {
				$installed_payment_methods = WC()->payment_gateways()->payment_gateways();
				$methods                   = array();
				if ( is_array( $installed_payment_methods ) && ! empty( $installed_payment_methods ) ) {
					foreach ( $installed_payment_methods  as $key => $method ) {
						if ( in_array( $key, $meta_data['payment-method'], true ) ) {
							$methods[] = array(
								'key'   => $key,
								'label' => $method->title,
								'value' => array( 'id' => $key ),
							);
						}
					}
				}
				$meta_data['payment-method'] = $methods;
			}
		}
		return $meta_data;
	}
	/**
	 * Add Rules data in api.
	 *
	 * @since    5.3.6
	 * @param    arrray $product  Array of product detail.
	 */
	public function wcol_get_rules( $product ) {
		$product_id = $product['id'];
		$plugin     = new WC_Order_Limit();
		$wcol_rule  = new WC_Order_Limit_Rule( $plugin->get_plugin_name(), $plugin->get_version() );
		$wcol_rules = array();
		if ( $product_id ) {
			$wcol_rules = $wcol_rule->get_rules( $product_id, 'products' );
		}

		if ( ! empty( $wcol_rules ) && is_array( $wcol_rules ) ) {
			foreach ( $wcol_rules as $pkey => $rule ) {
				if ( 'on' === $rule['enable-for-users'] && 'specific-roles' === $rule['user-type'] ) {
					global $wp_roles;
					$roles = array();
					if ( is_array( $wp_roles->roles ) ) {
						foreach ( $wp_roles->roles as $key => $role ) {
							if ( in_array( $key, $rule['rule-roles'], true ) ) {
								$roles[] = array(
									'key'   => $key,
									'label' => $role['name'],
									'value' => array( 'id' => $key ),
								);
							}
						}
					}
					$rule['rule-roles'] = $roles;
				}
				if ( 'on' === $rule['enable-for-users'] && 'specific-users' === $rule['user-type'] ) {
					$users     = get_users( array( 'fields' => array( 'id', 'display_name' ) ) );
					$all_users = array();
					if ( is_array( $users ) && ! empty( $users ) ) {
						foreach ( $users  as $user ) {
							if ( in_array( $user->ID, $rule['rule-users'], true ) ) {
								$all_users[] = array(
									'key'   => $user->ID,
									'label' => $user->display_name,
									'value' => array( 'id' => $user->ID ),
								);
							}
						}
					}
					$rule['rule-users'] = $all_users;
				}
				if ( 'on' === $rule['enable-for-payment'] ) {
					$installed_payment_methods = WC()->payment_gateways()->payment_gateways();
					$methods                   = array();
					if ( is_array( $installed_payment_methods ) && ! empty( $installed_payment_methods ) ) {
						foreach ( $installed_payment_methods  as $key => $method ) {
							if ( in_array( $key, $rule['payment-method'], true ) ) {
								$methods[] = array(
									'key'   => $key,
									'label' => $method->title,
									'value' => array( 'id' => $key ),
								);
							}
						}
					}
					$rule['payment-method'] = $methods;
				}
				foreach ( $rule as $key => $value ) {
					$new_key          = str_replace( '-', '_', $key );
					$rule[ $new_key ] = $rule[ $key ];
					if ( $new_key !== $key ) {
						unset( $rule[ $key ] );
					}
				}
				$wcol_rules[ $pkey ] = $rule;
			}
		} else {
			$wcol_rules = array();
		}
		return $wcol_rules;
	}
	/**
	 * Get Roles of WordPress.
	 *
	 * @since    5.3.6
	 */
	public function wcol_get_wp_roles() {
		global $wp_roles;
		$roles = array();
		if ( is_array( $wp_roles->roles ) ) {
			foreach ( $wp_roles->roles as $key => $role ) {
				$roles[] = array(
					'key'   => $key,
					'label' => $role['name'],
					'value' => array( 'id' => $key ),
				);
			}
		}
		return rest_ensure_response( $roles );
	}
	/**
	 * Get Users of WordPress.
	 *
	 * @since    5.3.6
	 */
	public function wcol_get_wp_users() {
		$users     = get_users( array( 'fields' => array( 'id', 'display_name' ) ) );
		$all_users = array();
		if ( is_array( $users ) && ! empty( $users ) ) {
			foreach ( $users  as $user ) {
				$all_users[] = array(
					'key'   => $user->ID,
					'label' => $user->display_name,
					'value' => array( 'id' => $user->ID ),
				);
			}
		}
		return rest_ensure_response( $all_users );
	}
	/**
	 * Get Payment method of WordPress.
	 *
	 * @since    5.3.6
	 */
	public function wcol_get_wp_payment_methods() {
		$installed_payment_methods = WC()->payment_gateways()->payment_gateways();
		$methods                   = array();
		if ( is_array( $installed_payment_methods ) && ! empty( $installed_payment_methods ) ) {
			foreach ( $installed_payment_methods  as $key => $method ) {
				$methods[] = array(
					'key'   => $key,
					'label' => $method->title,
					'value' => array( 'id' => $key ),
				);
			}
		}
		return rest_ensure_response( $methods );
	}
	/**
	 * Add new block.
	 *
	 * @since    5.3.6
	 * @param    integer $layout_template_id    Id of template.
	 * @param    string  $layout_template_area  name of tempplate area.
	 * @param    object  $layout_template       Detail of temaplet layout.
	 */
	public function wcol_add_block( $layout_template_id, $layout_template_area, $layout_template ) {
		// We only want to add the group to templates for the product editor.
		if ( 'simple-product' !== $layout_template_id ) {
			return;
		}

		// Add the group to the template.
		$group = $layout_template->add_group(
			array(
				'id'         => 'wc-order-limit ',
				// We don't specify an order, so the group will be added at the end.
				'attributes' => array(
					'title' => __( 'WC Order Limit', 'order-limit-for-woocommerce' ),
				),
			)
		);

		$section = $group->add_section(
			array(
				'id'         => 'wc-order-limit-rules',
				'attributes' => array(
					'title' => __( 'Rules', 'order-limit-for-woocommerce' ),
				),
			)
		);
		$section->add_block(
			array(
				'id'        => 'wc-order-limit-wc-order-limit',
				'order'     => 40,
				'blockName' => 'wc-order-limit/wc-order-limit',
			)
		);
	}
	/**
	 * Save product rules.
	 *
	 * @since    5.3.6
	 * @param    object $product    Detail of product.
	 * @param    object $request    Request data of api.
	 */
	public function wcol_save_api_rules( $product, $request ) {
		$params = $request->get_params();
		if ( isset( $params['id'] ) ) {
			$product_id = $params['id'];
			if ( isset( $params['wcol_rules'] ) ) {
				$raw_rules = $params['wcol_rules'];
				if ( count( $raw_rules ) > 0 ) {
					foreach ( $raw_rules as $rule ) {
						if ( isset( $rule['rule_roles'] ) && ! empty( $rule['rule_roles'] ) ) {
							$roles = array();
							if ( is_array( $rule['rule_roles'] ) ) {
								foreach ( $rule['rule_roles'] as $role ) {
									$roles[] = $role['key'];
								}
							}
							$rule['rule_roles'] = $roles;
						}
						if ( isset( $rule['rule_users'] ) && ! empty( $rule['rule_users'] ) ) {
							$all_users = array();
							if ( is_array( $rule['rule_users'] ) ) {
								foreach ( $rule['rule_users'] as $user ) {
									$all_users[] = $user['key'];
								}
							}
							$rule['rule_users'] = $all_users;
						}
						if ( isset( $rule['payment_method'] ) && ! empty( $rule['payment_method'] ) ) {
							$methods = array();
							if ( is_array( $rule['payment_method'] ) ) {
								foreach ( $rule['payment_method'] as $method ) {
									$methods[] = $method['key'];
								}
							}
							$rule['payment_method'] = $methods;
						}
						foreach ( $rule as $key => $value ) {
							if ( 'obj_ids' !== $key && 'applied_on' !== $key ) {
								$new_key          = str_replace( '_', '-', $key );
								$rule[ $new_key ] = $rule[ $key ];
								if ( $new_key !== $key ) {
									unset( $rule[ $key ] );
								}
							}
						}
						if ( isset( $rule['id'] ) ) {
							$rule_id = $rule['id'];
							unset( $rule['id'] );
							if ( isset( $rule['delete'] ) && $rule['delete'] ) {
								wp_delete_post( intval( $rule_id ), true );
							} else {
								update_post_meta( $rule_id, 'wcol-post-rules', $rule );
							}
						} else {
							$post_arr = array(
								'post_title'  => get_the_title( $product_id ) . ' rule',
								'post_type'   => 'wcol_rule',
								'post_status' => 'publish',
								'post_author' => get_current_user_id(),
							);
							$rule_id  = wp_insert_post( $post_arr );
							if ( $rule_id ) {
								update_post_meta( $rule_id, 'wcol-post-rules', $rule );
							}
						}
					}
				}
			}
		}
	}
}
