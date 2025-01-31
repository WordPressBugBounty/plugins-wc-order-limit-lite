<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://xfinitysoft.com/
 * @since      3.0.0
 *
 * @package    WC_Order_Limit
 * @subpackage WC_Order_Limit/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WC_Order_Limit
 * @subpackage WC_Order_Limit/includes
 * @author     xfinitysoft <support@xfinitysoft.com>
 */
class WC_Order_Limit_Rule {

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
	 * @since    3.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}
	/**
	 * GET All Rules.
	 *
	 * @since    3.0.0
	 * @param    string $object_id    ID of rule.
	 * @param    string $object_type  Type of rule.
	 */
	public function get_rules( $object_id, $object_type ) {

		$object_id   = strval( $object_id );
		$args        = array(
			'fields'      => 'ids',
			'post_type'   => 'wcol_rule',
			'status'      => 'publish',
			'numberposts' => -1,
		);
		$rule_ids    = get_posts( $args );
		$match_rules = array();

		foreach ( $rule_ids as $id ) {
			$rules       = get_post_meta( $id, 'wcol-post-rules', true );
			$rules['id'] = $id;
			if ( isset( $rules['rule-type'] ) && $rules['rule-type'] === $object_type ) {
				if ( 'accomulative' === $object_id ) {
					if ( isset( $rules['accomulative'] ) && 'on' === $rules['accomulative'] ) {
						$match_rules[] = $rules;
					}
				} elseif ( 'categories' === $object_type ) {
					$parentcats = get_ancestors( $object_id, 'product_cat' );
					if ( is_array( $parentcats ) && ! empty( $parentcats ) ) {
						foreach ( $parentcats as $parentcat ) {
							if ( in_array( strval( $parentcat ), $rules['obj_ids'], true ) || in_array( '-1', $rules['obj_ids'], true ) ) {
								$match_rules[] = $rules;
							} elseif ( in_array( $object_id, $rules['obj_ids'], true ) || in_array( '-1', $rules['obj_ids'], true ) ) {
								$match_rules[] = $rules;
							}
						}
					} elseif ( in_array( $object_id, $rules['obj_ids'], true ) || in_array( '-1', $rules['obj_ids'], true ) ) {
							$match_rules[] = $rules;
					}
				} elseif ( in_array( $object_id, $rules['obj_ids'], true ) || in_array( '-1', $rules['obj_ids'], true ) ) {
						$match_rules[] = $rules;
				}
			}
		}

		if ( empty( $match_rules ) ) {
			return false;
		} else {
			return $match_rules;
		}
	}

	/**
	 * GET All Rules.
	 *
	 * @since    3.0.0
	 * @param    string $type    Type of cart item.
	 */
	public function wcol_get_cart_items( $type ) {
		$items = array();
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			if ( 'product' === $type ) {
				$items[] = $cart_item['product_id'];
			} else {
				$terms = wp_get_post_terms( $cart_item['product_id'], 'product_cat' );
				if ( is_array( $terms ) ) {
					foreach ( $terms as $term ) {
						$items[] = $term->term_id;
					}
				}
			}
		}
		return $items;
	}
	/**
	 * Get Cart total product category.
	 *
	 * @since    3.0.0
	 */
	public function cart_totals_by_product_cat() {
		$product_cats = array();
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = $cart_item['product_id'];
			$terms      = wp_get_post_terms( $product_id, 'product_cat' );
			if ( is_array( $terms ) ) {
				foreach ( $terms as $term ) {
					$product_cats[ $term->term_id ][] = $cart_item;
				}
			}
		}
		$product_cats_total = array();
		if ( is_array( $product_cats ) ) {
			foreach ( $product_cats as $term_id => $items ) {
				$amount      = 0;
				$qty         = 0;
				$product_ids = array();
				if ( is_array( $items ) ) {
					foreach ( $items as $item ) {
						$amount       += apply_filters( 'wcol_cart_item_total', $item['line_total'], $item, 'product_cat' );
						$qty          += apply_filters( 'wcol_cart_item_qty', $item['quantity'], $item, 'product_cat' );
						$product_ids[] = $item['product_id'];
					}
				}
				$arg                            = array(
					'amount'      => $amount,
					'quantity'    => $qty,
					'product_ids' => $product_ids,
				);
				$product_cats_total[ $term_id ] = apply_filters( 'xswcol_product_total', $arg, $items );
			}
		}
		return $product_cats_total;
	}
	/**
	 * Check order limit apply.
	 *
	 * @since    3.0.0
	 */
	public function wc_order_limit_check() {
		$wcol_settings = $this->get_wcol_settings();
		wc_clear_notices();
		WC()->session->set( 'is_valid_order', true );
		$product_cat_total = $this->cart_totals_by_product_cat();
		$date_format       = apply_filters( 'xswcol_date_format', 'd M Y h:i:s a' );
		// Check for Cart total Order Limit.
		if ( isset( $wcol_settings['enable_cart_total_limit'] ) && 'on' === $wcol_settings['enable_cart_total_limit'] ) {
			$applied    = false;
			$applied_on = isset( $wcol_settings['cart_total_applied_on'] ) ? $wcol_settings['cart_total_applied_on'] : '';
			switch ( $applied_on ) {
				case 'amount':
						$cart_total_amount = apply_filters( 'wcol_cart_total_amount', WC()->cart->total - WC()->cart->fee_total, $wcol_settings, WC()->cart );
					if ( isset( $wcol_settings['cart_total_enable_maximum_limit'] ) && 'on' === $wcol_settings['cart_total_enable_maximum_limit'] && ( ( $cart_total_amount + $coupon_amounts ) < $wcol_settings['cart_total_minimum_limit'] || $cart_total_amount > $wcol_settings['cart_total_maximum_limit'] ) ) {
						$applied   = true;
						$min_value = wc_price( $wcol_settings['cart_total_minimum_limit'] );
						$max_value = wc_price( $wcol_settings['cart_total_maximum_limit'] );
					} elseif ( ( $cart_total_amount + $coupon_amounts ) < $wcol_settings['cart_total_minimum_limit'] ) {
						$applied   = true;
						$min_value = wc_price( $wcol_settings['cart_total_minimum_limit'] );
						$max_value = wc_price( $wcol_settings['cart_total_maximum_limit'] );
					} else {
						$applied = false;
					}
					break;
				case 'quantity':
						$cart_contents_count = apply_filters( 'wcol_cart_contents_count', WC()->cart->get_cart_contents_count(), $wcol_settings, WC()->cart );
					if ( isset( $wcol_settings['cart_total_enable_maximum_limit'] ) && 'on' === $wcol_settings['cart_total_enable_maximum_limit'] && ( $cart_contents_count < $wcol_settings['cart_total_minimum_limit'] || $cart_contents_count > $wcol_settings['cart_total_maximum_limit'] ) ) {
						$applied   = true;
						$min_value = $wcol_settings['cart_total_minimum_limit'];
						$max_value = ( '' !== $wcol_settings['cart_total_maximum_limit'] ) ? esc_html( $wcol_settings['cart_total_maximum_limit'] ) : '';
					} elseif ( $cart_contents_count < $wcol_settings['cart_total_minimum_limit'] ) {
						$applied   = true;
						$min_value = $wcol_settings['cart_total_minimum_limit'];
						$max_value = $wcol_settings['cart_total_maximum_limit'];
					} else {
						$applied = false;
					}
					break;
			}
			if ( isset( $wcol_settings['cart_total_enable_single_cat_limit'] ) && 'on' === $wcol_settings['cart_total_enable_single_cat_limit'] ) {
				$applied = false;
				$cat_ids = array();
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$cat_ids = array_merge( $cat_ids, $cart_item['data']->get_category_ids() );
				}
				$cat_ids = array_values( array_unique( $cat_ids ) );
				$count   = count( $cat_ids );
				if ( $count > 1 ) {
					$applied = true;
				}
			}
			if ( $applied ) {
				if ( 'on' !== $wcol_settings['cart_total_enable_maximum_limit'] ) {
					$max_value = '<span style="font-size:30px; font-weight:bold;vertical-align: middle;">∞</span>';
				}
				if ( isset( $wcol_settings['cart_total_enable_single_cat_limit'] ) && 'on' === $wcol_settings['cart_total_enable_single_cat_limit'] ) {
					$message = isset( $wcol_settings['cart_single_cat_limit_message'] ) ? $wcol_settings['cart_single_cat_limit_message'] : '';
				} else {
					$message = $wcol_settings['cart_total_limit_message'];
					$message = str_replace(
						array(
							'{min-limit}',
							'{max-limit}',
							'{applied-on}',
						),
						array(
							$min_value,
							$max_value,
							$applied_on,
						),
						$message
					);
				}
				if ( is_cart() && ! WC_Blocks_Utils::has_block_in_page( get_the_ID(), 'woocommerce/cart' ) ) {
					wc_print_notice( apply_filters( 'order_total_order_limit_notice', $message, $wcol_settings ), 'error' );
				} else {
					wc_add_notice( apply_filters( 'order_total_order_limit_notice', $message, $wcol_settings ), 'error' );
				}
				WC()->session->set( 'is_valid_order', false );
			}
		}

		// Check for Product Base Rules Order Limit.
		if ( isset( $wcol_settings['enable_product_limit'] ) && 'on' === $wcol_settings['enable_product_limit'] ) {
			$combine_pvariation = array();
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart ) {
				$_product                 = apply_filters( 'woocommerce_cart_item_product', $cart['data'], $cart, $cart_item_key );
				$product_id               = $_product->get_id();
				$xs_pro_value             = array();
				$xs_pro_value['quantity'] = apply_filters( 'wcol_cart_item_qty', $cart['quantity'], $cart, 'product' );
				$xs_pro_value['amount']   = apply_filters( 'wcol_cart_item_total', $cart['line_total'], $cart, 'product' );
				$cart_vid                 = null;
				if ( isset( $cart['variation_id'] ) && ! empty( $cart['variation_id'] ) ) {
					$cart_vid   = $cart['variation_id'];
					$product_id = wp_get_post_parent_id( $cart_vid );
					$is_checked = get_post_meta( $cart_vid, '_wcol_rule_limit', true );
					if ( 'yes' === $is_checked ) {
						$_product = wc_get_product( $cart_vid );
					}
					if ( isset( $combine_pvariation[ $product_id ] ) ) {
						$combine_pvariation[ $product_id ]['quantity'] = $combine_pvariation[ $product_id ]['quantity'] + $xs_pro_value['quantity'];
						$combine_pvariation[ $product_id ]['amount']   = $combine_pvariation[ $product_id ]['amount'] + $xs_pro_value['amount'];
					} else {
						$combine_pvariation[ $product_id ] = array(
							'quantity'    => $xs_pro_value['quantity'],
							'amount'      => $xs_pro_value['amount'],
							'product_ids' => array( $product_id ),
						);
					}
				}

				$xs_pro_value['product_ids'] = array( $product_id );

				$rules_applied = $this->is_valid_order( $product_id, 'products', $xs_pro_value );
				if ( is_array( $rules_applied ) ) {
					foreach ( $rules_applied as $rule ) {
						if ( 'amount' === $rule['applied_on'] ) {
							$wcol_min_value = wc_price( $rule['min-rule-limit'] );
							$wcol_max_value = ( '' !== $rule['max-rule-limit'] ) ? wc_price( $rule['max-rule-limit'] ) : '';
						} else {
							$wcol_min_value = $rule['min-rule-limit'];
							$wcol_max_value = ( '' !== $rule['max-rule-limit'] ) ? $rule['max-rule-limit'] : '';
						}
						if ( 'on' !== $rule['enable-max-rule-limit'] ) {
							$wcol_max_value = '<span style="font-size:30px; font-weight:bold;vertical-align: middle;">∞</span>';
						}
						$message = $wcol_settings['product_limit_message'];
						$message = str_replace(
							array(
								'{product-name}',
								'{min-limit}',
								'{max-limit}',
								'{applied-on}',
								'{endline}',
							),
							array(
								$_product->get_name(),
								$wcol_min_value,
								$wcol_max_value,
								$rule['applied_on'],
								'<br>',
							),
							$message
						);
						if ( is_cart() && ! WC_Blocks_Utils::has_block_in_page( get_the_ID(), 'woocommerce/cart' ) ) {
							wc_print_notice( apply_filters( 'product_order_limit_notice', $message, $product_id, $rule ), 'error' );
						} else {
							wc_add_notice( apply_filters( 'product_order_limit_notice', $message, $product_id, $rule ), 'error' );
						}
						WC()->session->set( 'is_valid_order', false );
					}
				}
			}
		}

		// Accomulative Checks for Products.
		$accomulative_prule = $this->get_accomulative_rules_on_cart( 'products' );
		if ( isset( $wcol_settings['enable_product_limit'] ) && 'on' === $wcol_settings['enable_product_limit'] && is_array( $accomulative_prule ) && ! empty( $accomulative_prule ) ) {
			foreach ( $accomulative_prule as $rule ) {
				if ( isset( $rule['applied_on'] ) && $rule[ $rule['applied_on'] ] > 0 ) {
					$products = array();
					if ( is_array( $rule['obj_ids'] ) ) {
						foreach ( $rule['obj_ids'] as $object_id ) {
							if ( '-1' !== $object_id ) {
								$products[] = get_the_title( $object_id );
							} else {
								$products[] = 'All Products';
							}
						}
					}
					if ( 'amount' === $rule['applied_on'] ) {
						$wcol_min_value = wc_price( $rule['min-rule-limit'] );
						$wcol_max_value = ( '' !== $rule['max-rule-limit'] ) ? wc_price( $rule['max-rule-limit'] ) : '';
					} else {
						$wcol_min_value = $rule['min-rule-limit'];
						$wcol_max_value = $rule['max-rule-limit'];
					}
					if ( empty( $wcol_max_value ) || 'on' !== $rule['enable-max-rule-limit'] ) {
						$wcol_max_value = '<span style="font-size:30px; font-weight:bold;vertical-align: middle;">∞</span>';
					}

					$message = $wcol_settings['product_limit_message_accomulative'];
					switch ( $rule['rule-time-span'] ) {
						case 'daily':
							$time_span_text = esc_html__( 'Daily', 'order-limit-for-woocommerce' );
							break;
						case 'weekly':
							$time_span_text = esc_html__( 'Weekly', 'order-limit-for-woocommerce' );
							break;
						case 'monthly':
							$time_span_text = esc_html__( 'Monthly', 'order-limit-for-woocommerce' );
							break;
						case 'yearly':
							$time_span_text = esc_html__( 'Yearly', 'order-limit-for-woocommerce' );
							break;
						case 'days':
							$time_span_text = __( 'last ', 'order-limit-for-woocommerce' ) . $rule['days'] . __( ' days', 'order-limit-for-woocommerce' );
							break;
						case 'custom':
							$time_span_text = esc_html__( 'from ', 'order-limit-for-woocommerce' ) . gmdate( 'd M Y h:i a', $rule['from'] ) . esc_html__( ' to ', 'order-limit-for-woocommerce' ) . gmdate( 'd M Y h:i a', $rule['to'] );
							break;
					}
					$reset_time = gmdate( $date_format, $rule['to'] );
					if ( 'days' === $rule['rule-time-span'] ) {
						$reset_time = __( ' required days have passed', 'order-for-woocommerce' );
					}
					$message = str_replace(
						array(
							'{product-names}',
							'{min-limit}',
							'{max-limit}',
							'{applied-on}',
							'{time-span}',
							'{limit-reset-day}',
							'{remaining}',
							'{endline}',
						),
						array(
							implode( ',', $products ),
							$wcol_min_value,
							$wcol_max_value,
							$rule['applied_on'],
							$time_span_text,
							$reset_time,
							$rule['remaining'],
							'<br>',
						),
						$message
					);
					if ( is_cart() && ! WC_Blocks_Utils::has_block_in_page( get_the_ID(), 'woocommerce/cart' ) ) {
						wc_print_notice( apply_filters( 'accomulative_products_limit_notice', $message, $rule ), 'error' );
					} else {
						wc_add_notice( apply_filters( 'accomulative_products_limit_notice', $message, $rule ), 'error' );
					}
					WC()->session->set( 'is_valid_order', false );
				}
			}
		}

		// Check for Category Base Rules Order Limit.

		if ( isset( $wcol_settings['enable_category_limit'] ) && 'on' === $wcol_settings['enable_category_limit'] && is_array( $product_cat_total ) ) {
			foreach ( $product_cat_total as $term_id => $value ) {
				$rules_applied = $this->is_valid_order( $term_id, 'categories', $value );
				if ( is_array( $rules_applied ) ) {
					foreach ( $rules_applied as $rule ) {
						if ( 'amount' === $rule['applied_on'] ) {
							$wcol_min_value = wc_price( $rule['min-rule-limit'] );
							$wcol_max_value = ( '' !== $rule['max-rule-limit'] ) ? wc_price( $rule['max-rule-limit'] ) : '';
						} else {
							$wcol_min_value = $rule['min-rule-limit'];
							$wcol_max_value = ( '' !== $rule['max-rule-limit'] ) ? $rule['max-rule-limit'] : '';
						}
						$term_id = strval( $term_id );
						if ( in_array( $term_id, $rule['obj_ids'], true ) ) {
							$term = get_term( $term_id, 'product_cat' );
						} else {
							$parentcats = get_ancestors( $term_id, 'product_cat' );
							if ( is_array( $parentcats ) && ! empty( $parentcats ) ) {
								foreach ( $parentcats as $parentcat ) {
									if ( in_array( strval( $parentcat ), $rule['obj_ids'], true ) ) {
										$term = get_term( $parentcat, 'product_cat' );
									}
								}
							}
						}

						if ( 'on' !== $rule['enable-max-rule-limit'] ) {
							$wcol_max_value = '<span style="font-size:30px; font-weight:bold;vertical-align: middle;">∞</span>';
						}
						if ( '1' !== $rule['across-all-orders'] && 'minimum_limit' !== $rule['applied_for'] ) {
							if ( '2' === $rule['across-all-orders'] ) {
								$message = $wcol_settings['category_limit_message_across_all_orders'];
							} else {
								$message = $wcol_settings['category_limit_message_across_all_users_orders'];
							}
							switch ( $rule['rule-time-span'] ) {
								case 'daily':
									$time_span_text = esc_html__( 'Daily', 'order-limit-for-woocommerce' );
									break;
								case 'weekly':
									$time_span_text = esc_html__( 'Weekly', 'order-limit-for-woocommerce' );
									break;
								case 'monthly':
									$time_span_text = esc_html__( 'Monthly', 'order-limit-for-woocommerce' );
									break;
								case 'yearly':
									$time_span_text = esc_html__( 'Yearly', 'order-limit-for-woocommerce' );
									break;
								case 'days':
									$time_span_text = __( 'last ', 'order-limit-for-woocommerce' ) . $rule['days'] . __( ' days', 'order-limit-for-woocommerce' );
									break;
								case 'custom':
									$time_span_text = esc_html__( 'from ', 'order-limit-for-woocommerce' ) . gmdate( 'd M Y h:i a', $rule['from'] ) . esc_html__( ' to ', 'order-limit-for-woocommerce' ) . gmdate( 'd M Y h:i a', $rule['to'] );
									break;
							}
						} else {
							switch ( $rule['rule-time-span'] ) {
								case 'daily':
									$time_span_text = esc_html__( 'Daily', 'order-limit-for-woocommerce' );
									break;
								case 'weekly':
									$time_span_text = esc_html__( 'Weekly', 'order-limit-for-woocommerce' );
									break;
								case 'monthly':
									$time_span_text = esc_html__( 'Monthly', 'order-limit-for-woocommerce' );
									break;
								case 'yearly':
									$time_span_text = esc_html__( 'Yearly', 'order-limit-for-woocommerce' );
									break;
								case 'days':
									$time_span_text = __( 'last ', 'order-limit-for-woocommerce' ) . $rule['days'] . __( ' days', 'order-limit-for-woocommerce' );
									break;
								case 'custom':
									$rule_start_time = gmdate( 'd M Y h:i a', strtotime( $rule['rule-start-time'] ) );
									$rule_end_time   = gmdate( 'd M Y h:i a', strtotime( $rule['rule-end-time'] ) );
									$time_span_text  = esc_html__( 'from ', 'order-limit-for-woocommerce' ) . gmdate( 'd M Y h:i a', strtotime( $rule['rule-start-time'] ) ) . esc_html__( ' to ', 'order-limit-for-woocommerce' ) . gmdate( 'd M Y h:i a', strtotime( $rule['rule-end-time'] ) );
									break;
							}
							$message     = $wcol_settings['category_limit_message'];
							$date_format = apply_filters( 'xswcol_date_format', 'd M Y h:i:s a' );
						}
						$names = array();
						if ( isset( $rule['prule'] ) && ! empty( $rule['prule'] ) ) {
							if ( 'categories' === $rule['prule_type'] ) {
								$message = 'You must add this category product {parent-category-names} in cart to proceed';
								$message = isset( $wcol_settings['parent_category_limit_message'] ) ? $wcol_settings['parent_category_limit_message'] : $message;
								foreach ( $rule['prule'] as $id ) {
									$cterm   = get_term( $id, 'product_cat' );
									$names[] = $cterm->name;
								}
							} else {
								$message = 'You must add this product {parent-product-names} in cart to proceed';
								$message = isset( $wcol_settings['parent_product_limit_message'] ) ? $wcol_settings['parent_product_limit_message'] : $message;
								foreach ( $rule['prule'] as $id ) {
									$names[] = get_the_title( $id );
								}
							}
						}
						$reset_time = gmdate( $date_format, $rule['to'] );
						if ( 'days' === $rule['rule-time-span'] ) {
							$reset_time = __( ' required days have passed', 'order-for-woocommerce' );
						}
						$message = str_replace(
							array(
								'{category-name}',
								'{min-limit}',
								'{max-limit}',
								'{applied-on}',
								'{time-span}',
								'{limit-reset-day}',
								'{remaining}',
								'{parent-category-names}',
								'{endline}',
							),
							array(
								$term->name,
								$wcol_min_value,
								$wcol_max_value,
								$rule['applied_on'],
								$time_span_text,
								$reset_time,
								$rule['remaining'],
								implode( ',', $names ),
								'<br>',
							),
							$message
						);
						if ( is_cart() && ! WC_Blocks_Utils::has_block_in_page( get_the_ID(), 'woocommerce/cart' ) ) {
							wc_print_notice( apply_filters( 'product_cat_order_limit_notice', $message, $term_id, $rule ), 'error' );
						} else {
							wc_add_notice( apply_filters( 'product_cat_order_limit_notice', $message, $term_id, $rule ), 'error' );
						}
						WC()->session->set( 'is_valid_order', false );
					}
				}
			}
		}

		// Accomulative Checks for Product Categories.
		$accomulative_crule = $this->get_accomulative_rules_on_cart( 'categories' );
		if ( isset( $wcol_settings['enable_category_limit'] ) && 'on' === $wcol_settings['enable_category_limit'] && is_array( $accomulative_crule ) ) {
			foreach ( $accomulative_crule as $rule ) {
				if ( isset( $rule['applied_on'] ) && $rule[ $rule['applied_on'] ] > 0 ) {
					$cats = array();
					if ( is_array( $rule['obj_ids'] ) ) {
						foreach ( $rule['obj_ids'] as $object_id ) {
							if ( '-1' !== $object_id ) {
								$term   = get_term( $object_id, 'product_cat' );
								$cats[] = $term->name;
							} else {
								$cats[] = 'All Categories';
							}
						}
					}
					if ( 'amount' === $rule['applied_on'] ) {
						$wcol_min_value = wc_price( $rule['min-rule-limit'] );
						$wcol_max_value = ( '' !== $rule['max-rule-limit'] ) ? wc_price( $rule['max-rule-limit'] ) : '';
					} else {
						$wcol_min_value = $rule['min-rule-limit'];
						$wcol_max_value = $rule['max-rule-limit'];
					}
					if ( empty( $wcol_max_value ) || 'on' !== $rule['enable-max-rule-limit'] ) {
						$wcol_max_value = '<span style="font-size:30px; font-weight:bold;vertical-align: middle;">∞</span>';
					}
					$message = $wcol_settings['category_limit_message_accomulative'];
					switch ( $rule['rule-time-span'] ) {
						case 'daily':
							$time_span_text = esc_html__( 'Daily', 'order-limit-for-woocommerce' );
							break;
						case 'weekly':
							$time_span_text = esc_html__( 'Weekly', 'order-limit-for-woocommerce' );
							break;
						case 'monthly':
							$time_span_text = esc_html__( 'Monthly', 'order-limit-for-woocommerce' );
							break;
						case 'yearly':
							$time_span_text = esc_html__( 'Yearly', 'order-limit-for-woocommerce' );
							break;
						case 'days':
							$time_span_text = __( 'last ', 'order-limit-for-woocommerce' ) . $rule['days'] . __( ' days', 'order-limit-for-woocommerce' );
							break;
						case 'custom':
							$time_span_text = esc_html__( 'from ', 'order-limit-for-woocommerce' ) . gmdate( 'd M Y h:i a', $rule['from'] ) . esc_html__( ' to ', 'order-limit-for-woocommerce' ) . gmdate( 'd M Y h:i a', $rule['to'] );
							break;
					}
					$reset_time = gmdate( $date_format, $rule['to'] );
					if ( 'days' === $rule['rule-time-span'] ) {
						$reset_time = __( ' required days have passed', 'order-for-woocommerce' );
					}
					$message = str_replace(
						array(
							'{category-names}',
							'{min-limit}',
							'{max-limit}',
							'{applied-on}',
							'{time-span}',
							'{limit-reset-day}',
							'{remaining}',
							'{endline}',
						),
						array(
							implode( ', ', $cats ),
							$wcol_min_value,
							$wcol_max_value,
							$rule['applied_on'],
							$time_span_text,
							$reset_time,
							$rule['remaining'],
							'<br>',
						),
						$message
					);
					if ( is_cart() && ! WC_Blocks_Utils::has_block_in_page( get_the_ID(), 'woocommerce/cart' ) ) {
						wc_print_notice( apply_filters( 'accomulative_product_cats_limit_notice', $message, $rule ), 'error' );
					} else {
						wc_add_notice( apply_filters( 'accomulative_product_cats_limit_notice', $message, $rule ), 'error' );
					}
					WC()->session->set( 'is_valid_order', false );
				}
			}
		}
		if ( ! WC()->session->get( 'is_valid_order', false ) && isset( $wcol_settings['enable_checkout_button'] ) && 'on' === $wcol_settings['enable_checkout_button'] ) {
			remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
		}
	}
	/**
	 * Add script in footer.
	 *
	 * @since    3.0.0
	 */
	public function wc_order_limit_footer_script() {
		$wcol_settings = $this->get_wcol_settings();
		if ( is_cart() && isset( $wcol_settings['enable_checkout_button'] ) && 'on' === $wcol_settings['enable_checkout_button'] ) {
			wp_enqueue_script( 'order-limit-for-woocommerce', plugin_dir_url( __DIR__ ) . 'public/js/wc-order-limit-public.js', array( 'jquery' ), $this->version, true );
		}
	}
	/**
	 * GET Rules by user.
	 *
	 * @since    3.0.0
	 * @param    string $object_id    ID of rule.
	 * @param    string $object_type  Type of rule.
	 * @param    array  $rules  Array of rules.
	 */
	public function get_wcol_option_by_user( $object_id, $object_type, $rules = array() ) {
		if ( ! empty( $rules ) ) {
			$wcol_options = $rules;
		} else {
			$wcol_options = $this->get_rules( $object_id, $object_type );
		}
		$matched_rules = array();
		if ( is_array( $wcol_options ) ) {
			foreach ( $wcol_options as $key => $option ) {
				if ( 'on' !== $option['disable-limit'] ) {
					$option['key']   = $key;
					$matched_rules[] = $option;
				}
			}
		}

		if ( empty( $matched_rules ) ) {
			return false;
		}
		return $matched_rules;
	}

	/**
	 * Check order is valid.
	 *
	 * @since    3.0.0
	 * @param    string $object_id      ID of rule.
	 * @param    string $object_type    Type of rule.
	 * @param    string $total          Total of order.
	 */
	public function is_valid_order( $object_id, $object_type, $total ) {
		$rules = $this->get_wcol_option_by_user( $object_id, $object_type );
		if ( ! $rules ) {
			return true;
		}
		$rules_applied = array();
		if ( is_array( $rules ) ) {
			foreach ( $rules as $rule ) {
				$rule['remaining']        = '';
				$rule['cart_product_ids'] = $total['product_ids'];
				$accomulative_check       = ( isset( $rule['accomulative'] ) && 'on' === $rule['accomulative'] ) ? true : false;
				if ( ! $accomulative_check ) {
					$rule['applied_for'] = '';
					$check               = false;
					$wcol_settings       = $this->get_wcol_settings();
					$cart_total          = $total[ $rule['applied_on'] ];
					if ( 'amount' === $rule['applied_on'] ) {
						$cart_total = $total[ $rule['applied_on'] ];
					}
					if ( 'on' === $rule['enable-max-rule-limit'] && ( $cart_total < $rule['min-rule-limit'] || $cart_total > $rule['max-rule-limit'] ) ) {
						$remain_limit = $rule['max-rule-limit'];
						if ( $remain_limit < 0 ) {
							$remain_limit = 0;
						}
						$rule['remaining'] = $remain_limit;
						$check             = true;
					} elseif ( $cart_total < $rule['min-rule-limit'] ) {
						$check = true;
					}
					if ( $check ) {
						$rules_applied[] = $rule;
					}
				}
			}
		}
		if ( empty( $rules_applied ) ) {
			return false;
		}
		return $rules_applied;
	}
	/**
	 * Check the category of specfic product.
	 *
	 * @since    3.0.0
	 * @param    string $product_id      ID of Product.
	 * @param    string $category_id        ID of category.
	 */
	public function is_category_of_specific_product( $product_id, $category_id ) {
		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			return false;
		}
		$terms = get_the_terms( $product_id, 'product_cat' );
		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return false;
		}
		foreach ( $terms as $term ) {
			if ( $term->term_id === $category_id ) {
				return true;
			}
			if ( $term->parent > 0 ) {
				$parent_term = get_term( $term->parent, 'product_cat' );
				if ( $parent_term && $parent_term->term_id === $category_id ) {
					return true;
				}
			}
		}
		return false;
	}
	/**
	 * Get Plugin setting.
	 *
	 * @since    3.0.0
	 */
	public function get_wcol_settings() {
		$wcol_options = get_option( 'wcol_options', true );
		return $wcol_options['wcol_settings'];
	}
	/**
	 * Checkout restrict order limit.
	 *
	 * @since    3.0.0
	 */
	public function wc_order_limit_restrict_checkout() {
		$wcol_settings = $this->get_wcol_settings();
		if ( is_plugin_active( 'disable-cart-page-for-woocommerce/disable-cart-page-for-woocommerce.php' ) ) {
			return;
		}
		if ( is_plugin_active( 'woocommerce-direct-checkout/woocommerce-direct-checkout.php' ) ) {
			return;
		}
		if ( ! WC()->cart->is_empty() && is_checkout() ) {
			if ( ! WC()->session->get( 'is_valid_order' ) ) {
				if ( isset( $wcol_settings['enable_checkout_redirect'] ) && 'on' === $wcol_settings['enable_checkout_redirect'] ) {
					wp_safe_redirect( wc_get_cart_url() );
					exit();
				}
			}
		}
	}

	/**
	 * Get accomulative rules.
	 *
	 * @since    3.0.0
	 * @param  string $object_type Type of rule.
	 */
	public function get_accomulative_rules_on_cart( $object_type ) {
		$wcol_settings = $this->get_wcol_settings();
		$object_id     = 'accomulative';
		if ( empty( $selected_payment_method_id ) && is_checkout() ) {
			$selected_payment_method_id = WC()->session->get( 'chosen_payment_method' );
		}
		$current_time       = wp_date( 'y-m-d h:i:s A' );
		$current_time       = strtotime( $current_time );
		$accomulative_rules = $this->get_wcol_option_by_user( $object_id, $object_type );
		$applied_rules      = array();
		if ( is_array( $accomulative_rules ) ) {
			foreach ( $accomulative_rules as $rule ) {
				$qty               = 0;
				$amount            = 0;
				$other             = 0;
				$limit             = 0;
				$product_items_arr = array();
				if ( is_array( $rule['obj_ids'] ) ) {
					foreach ( $rule['obj_ids'] as $id ) {
						foreach ( WC()->cart->get_cart() as $key => $cart_item ) {
							if ( ( intval( $id ) === $cart_item['product_id'] || '-1' === $id ) && 'products' === $object_type ) {
								$product_items_arr[] = $cart_item;
							} elseif ( 'categories' === $object_type ) {
								if ( '-1' === $id ) {
									$cart_item['category_id'] = $id;
									$product_items_arr[]      = $cart_item;
								} else {
									$terms = get_the_terms( $cart_item['product_id'], 'product_cat' );
									if ( is_array( $terms ) && ! empty( $terms ) ) {
										foreach ( $terms as $term ) {
											if ( intval( $id ) === $term->term_id ) {
												$cart_item['category_id'] = $id;
												$product_items_arr[]      = $cart_item;
											}
										}
									}
								}
							} else {
								continue;
							}
						}
					}
				}
				if ( is_array( $product_items_arr ) && ! empty( $product_items_arr ) ) {
					$orders = array();
					foreach ( $product_items_arr as $key => $product_items ) {
						$rule['cart_product_ids'] = array();
						$qty                     += apply_filters( 'wcol_cart_item_qty', $product_items['quantity'], $product_items, $object_type );
						$amount                  += apply_filters( 'wcol_cart_item_total', $product_items['line_total'], $product_items, $object_type );
					}
					$rule['quantity'] = $qty;
					$rule['amount']   = $amount;
					$remain_limit     = 0;
					if ( isset( $rule['max-rule-limit'] ) && ! empty( $rule['max-rule-limit'] ) ) {
						$remain_limit = $rule['max-rule-limit'] - $limit;
					}
					if ( $remain_limit < 0 ) {
						$remain_limit = 0;
					}
					$rule['remaining'] = $remain_limit;
					$rule              = apply_filters( 'xswcol_get_accomulative_rules_on_cart', $rule, $product_items_arr, $other );
					if ( $rule[ $rule['applied_on'] ] < $rule['min-rule-limit'] || ( 'on' === $rule['enable-max-rule-limit'] && $rule[ $rule['applied_on'] ] > $rule['max-rule-limit'] ) ) {
						$applied_rules[] = $rule;
					}
				}
			}
		}
		return $applied_rules;
	}
	/**
	 * Save wcol option.
	 *
	 * @since    3.0.0
	 * @param  string $object_id Id of rule.
	 * @param  string $object_type type of rule.
	 */
	public function save_wcol_options( $object_id, $object_type ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! isset( $_POST['_wcol_save_rules_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wcol_save_rules_nonce'] ) ), 'wcol_save_rules' ) ) {
			return;
		}
		$raw_rules = array();

		if ( isset( $_POST['wcol_rules'] ) ) {
			//phpcs:ignore
			$raw_rules = $_POST['wcol_rules'];
		}
		$object_id    = strval( $object_id );
		$wcol_options = array();
		if ( count( $raw_rules ) > 0 ) {
			if ( is_array( $raw_rules['min-rule-limit'] ) ) {
				foreach ( $raw_rules['min-rule-limit'] as $key => $value ) {
					$rule_id = $raw_rules['id'][ $key ];
					$rules   = get_post_meta( $rule_id, 'wcol-post-rules', true );
					if ( $rules ) {
						$rules['min-rule-limit']        = '' !== $raw_rules['min-rule-limit'][ $key ] ? $raw_rules['min-rule-limit'][ $key ] : 0;
						$rules['applied_on']            = $raw_rules['applied_on'][ $key ];
						$rules['disable-limit']         = $raw_rules['disable-limit'][ $key ];
						$rules['enable-max-rule-limit'] = $raw_rules['enable-max-rule-limit'][ $key ];
						$rules['max-rule-limit']        = $raw_rules['max-rule-limit'][ $key ];
						$rules['enable-time-limit']     = $raw_rules['enable-time-limit'][ $key ];
						$rules['rule-time-span']        = $raw_rules['rule-time-span'][ $key ];
						$rules['yearly-start-day']      = $raw_rules['yearly-start-day'][ $key ];
						$rules['yearly-start-month']    = $raw_rules['yearly-start-month'][ $key ];
						$rules['weekly-start-day']      = $raw_rules['weekly-start-day'][ $key ];
						$rules['monthly-start-date']    = $raw_rules['monthly-start-date'][ $key ];
						$rules['enable-for-users']      = $raw_rules['enable-for-users'][ $key ];
						$rules['rule-start-time']       = $raw_rules['rule-start-time'][ $key ];
						$rules['rule-end-time']         = $raw_rules['rule-end-time'][ $key ];
						if ( 'vendor' !== $object_type ) {
							$rules['across-all-orders'] = $raw_rules['across-all-orders'][ $key ];
							$rules['accomulative']      = $raw_rules['accomulative'][ $key ];
							$rules['user-type']         = $raw_rules['user-type'][ $key ];
							if ( isset( $raw_rules['rule-users'][ $key ] ) ) {
								$rules['rule-users'] = $raw_rules['rule-users'][ $key ];
							}
							if ( isset( $raw_rules['rule-roles'][ $key ] ) ) {
								$rules['rule-roles'] = $raw_rules['rule-roles'][ $key ];
							}
						} else {
							$rules['editable'] = $raw_rules['editable'][ $key ];
						}
						update_post_meta( $rule_id, 'wcol-post-rules', $rules );
					} else {
						$rules                          = array();
						$rules['rule-type']             = $object_type;
						$rules['obj_ids']               = array( $object_id );
						$rules['min-rule-limit']        = '' !== $raw_rules['min-rule-limit'][ $key ] ? $raw_rules['min-rule-limit'][ $key ] : 0;
						$rules['applied_on']            = $raw_rules['applied_on'][ $key ];
						$rules['disable-limit']         = isset( $raw_rules['disable-limit'][ $key ] ) ? $raw_rules['disable-limit'][ $key ] : '';
						$rules['enable-max-rule-limit'] = $raw_rules['enable-max-rule-limit'][ $key ];
						$rules['max-rule-limit']        = $raw_rules['max-rule-limit'][ $key ];
						$rules['enable-time-limit']     = $raw_rules['enable-time-limit'][ $key ];
						$rules['rule-time-span']        = $raw_rules['rule-time-span'][ $key ];
						$rules['yearly-start-day']      = $raw_rules['yearly-start-day'][ $key ];
						$rules['yearly-start-month']    = $raw_rules['yearly-start-month'][ $key ];
						$rules['weekly-start-day']      = $raw_rules['weekly-start-day'][ $key ];
						$rules['monthly-start-date']    = $raw_rules['monthly-start-date'][ $key ];
						$rules['enable-for-users']      = $raw_rules['enable-for-users'][ $key ];
						$rules['rule-start-time']       = $raw_rules['rule-start-time'][ $key ];
						$rules['rule-end-time']         = $raw_rules['rule-end-time'][ $key ];
						if ( 'vendor' !== $object_type ) {
							$rules['accomulative']      = isset( $raw_rules['accomulative'][ $key ] ) ? $raw_rules['accomulative'][ $key ] : '';
							$rules['across-all-orders'] = $raw_rules['across-all-orders'][ $key ];
							$rules['user-type']         = $raw_rules['user-type'][ $key ];
							if ( isset( $raw_rules['rule-users'][ $key ] ) ) {
								$rules['rule-users'] = $raw_rules['rule-users'][ $key ];
							}
							if ( isset( $raw_rules['rule-roles'][ $key ] ) ) {
								$rules['rule-roles'] = $raw_rules['rule-roles'][ $key ];
							}
						} else {
								$rules['editable'] = $raw_rules['editable'][ $key ];
						}
						$post_arr = array(
							'post_title'  => get_the_title( $object_id ) . ' rule',
							'post_type'   => 'wcol_rule',
							'post_status' => 'publish',
							'post_author' => get_current_user_id(),
						);
						if ( 'categories' === $object_type ) {
							$term                   = get_term( $object_id, 'product_cat' );
							$post_arr['post_title'] = $term->name . ' rule';
						}
						if ( 'vendor' === $object_type ) {
							$user                   = get_user_by( 'id', $object_id );
							$post_arr['post_title'] = $user->display_name . ' vendor rule';
						}
						$rule_id = wp_insert_post( $post_arr );
						if ( $rule_id ) {
							update_post_meta( $rule_id, 'wcol-post-rules', $rules );
						}
					}
				}
			}
		}
	}
}
