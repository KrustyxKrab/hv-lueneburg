<?php

if ( ! function_exists( 'topscorer_is_woo_page' ) ) {
	/**
	 * Function that check WooCommerce pages
	 *
	 * @param $page string
	 *
	 * @return bool
	 */
	function topscorer_is_woo_page( $page ) {
		switch ( $page ) {
			case 'shop':
				return function_exists( 'is_shop' ) && is_shop();
				break;
			case 'single':
				return is_singular( 'product' );
				break;
			case 'cart':
				return function_exists( 'is_cart' ) && is_cart();
				break;
			case 'checkout':
				return function_exists( 'is_checkout' ) && is_checkout();
				break;
			case 'account':
				return function_exists( 'is_account_page' ) && is_account_page();
				break;
			case 'category':
				return function_exists( 'is_product_category' ) && is_product_category();
				break;
			case 'tag':
				return function_exists( 'is_product_tag' ) && is_product_tag();
				break;
			case 'any':
				return (
					function_exists( 'is_shop' ) && is_shop() ||
					is_singular( 'product' ) ||
					function_exists( 'is_cart' ) && is_cart() ||
					function_exists( 'is_checkout' ) && is_checkout() ||
					function_exists( 'is_account_page' ) && is_account_page() ||
					function_exists( 'is_product_category' ) && is_product_category() ||
					function_exists( 'is_product_tag' ) && is_product_tag()
				);
				break;
			default:
				return false;
		}
	}
}

if ( ! function_exists( 'topscorer_get_woo_main_page_classes' ) ) {
	/**
	 * Function that return current WooCommerce page class name
	 *
	 * @return string
	 */
	function topscorer_get_woo_main_page_classes() {
		$classes = array();

		if ( topscorer_is_woo_page( 'shop' ) ) {
			$classes[] = 'qodef--list';
		}

		if ( topscorer_is_woo_page( 'single' ) ) {
			$classes[] = 'qodef--single';

			if ( topscorer_get_post_value_through_levels( 'qodef_woo_single_enable_image_lightbox' ) === 'photo-swipe' ) {
				$classes[] = 'qodef-popup--photo-swipe';
			}

			if ( topscorer_get_post_value_through_levels( 'qodef_woo_single_enable_image_lightbox' ) === 'thumb-featured-switch' ) {
				$classes[] = 'qodef--thumb-featured-switch';
			}

			if ( topscorer_get_post_value_through_levels( 'qodef_woo_single_enable_image_lightbox' ) === 'magnific-popup' ) {
				$classes[] = 'qodef-popup--magnific-popup';
				// add classes to initialize lightbox from theme
				$classes[] = 'qodef-magnific-popup';
				$classes[] = 'qodef-popup-gallery';
			}
		}

		if ( topscorer_is_woo_page( 'cart' ) ) {
			$classes[] = 'qodef--cart';
		}

		if ( topscorer_is_woo_page( 'checkout' ) ) {
			$classes[] = 'qodef--checkout';
		}

		if ( topscorer_is_woo_page( 'account' ) ) {
			$classes[] = 'qodef--account';
		}

		return apply_filters( 'topscorer_filter_main_page_classes', implode( ' ', $classes ) );
	}
}

if ( ! function_exists( 'topscorer_woo_get_global_product' ) ) {
	/**
	 * Function that return global WooCommerce object
	 *
	 * @return object
	 */
	function topscorer_woo_get_global_product() {
		global $product;

		return $product;
	}
}

if ( ! function_exists( 'topscorer_woo_get_main_shop_page_id' ) ) {
	/**
	 * Function that return main shop page ID
	 *
	 * @return int
	 */
	function topscorer_woo_get_main_shop_page_id() {
		// Get page id from options table
		$shop_id = get_option( 'woocommerce_shop_page_id' );

		if ( ! empty( $shop_id ) ) {
			return $shop_id;
		}

		return false;
	}
}

if ( ! function_exists( 'topscorer_woo_set_main_shop_page_id' ) ) {
	/**
	 * Function that set main shop page ID for get_post_meta options
	 *
	 * @param $post_id int
	 *
	 * @return int
	 */
	function topscorer_woo_set_main_shop_page_id( $post_id ) {

		if ( topscorer_is_woo_page( 'shop' ) || topscorer_is_woo_page( 'single' ) || topscorer_is_woo_page( 'category' ) || topscorer_is_woo_page( 'tag' ) ) {
			$shop_id = topscorer_woo_get_main_shop_page_id();

			if ( ! empty( $shop_id ) ) {
				$post_id = $shop_id;
			}
		}

		return $post_id;
	}

	add_filter( 'topscorer_filter_page_id', 'topscorer_woo_set_main_shop_page_id' );
	add_filter( 'qode_framework_filter_page_id', 'topscorer_woo_set_main_shop_page_id' );
}

if ( ! function_exists( 'topscorer_woo_set_page_title_text' ) ) {
	/**
	 * Function that returns current page title text for WooCommerce pages
	 *
	 * @param $title string
	 *
	 * @return string
	 */
	function topscorer_woo_set_page_title_text( $title ) {

		if ( topscorer_is_woo_page( 'shop' ) || topscorer_is_woo_page( 'single' ) ) {
			$shop_id = topscorer_woo_get_main_shop_page_id();

			$title = ! empty( $shop_id ) ? get_the_title( $shop_id ) : esc_html__( 'Shop', 'topscorer' );
		} else if ( topscorer_is_woo_page( 'category' ) || topscorer_is_woo_page( 'tag' ) ) {
			$taxonomy_slug = topscorer_is_woo_page( 'tag' ) ? 'product_tag' : 'product_cat';
			$taxonomy      = get_term( get_queried_object_id(), $taxonomy_slug );

			if ( ! empty( $taxonomy ) ) {
				$title = esc_html( $taxonomy->name );
			}
		}

		return $title;
	}

	add_filter( 'topscorer_filter_page_title_text', 'topscorer_woo_set_page_title_text' );
}

if ( ! function_exists( 'topscorer_woo_single_add_theme_supports' ) ) {
	/**
	 * Function that add native WooCommerce supports
	 */
	function topscorer_woo_single_add_theme_supports() {
		// Add featured image zoom functionality on product single page
		$is_zoom_enabled = topscorer_get_post_value_through_levels( 'qodef_woo_single_enable_image_zoom' ) !== 'no';

		if ( $is_zoom_enabled ) {
			add_theme_support( 'wc-product-gallery-zoom' );
		}

		// Add photo swipe lightbox functionality on product single images page
		$is_photo_swipe_enabled = topscorer_get_post_value_through_levels( 'qodef_woo_single_enable_image_lightbox' ) === 'photo-swipe';

		if ( $is_photo_swipe_enabled ) {
			add_theme_support( 'wc-product-gallery-lightbox' );
		}
	}

	add_action( 'wp_loaded', 'topscorer_woo_single_add_theme_supports', 11 ); // permission 11 is set because options are init with permission 10 inside framework plugin
}

if ( ! function_exists( 'topscorer_woo_single_disable_page_title' ) ) {
	/**
	 * Function that disable page title area for single product page
	 *
	 * @param $enable_page_title bool
	 *
	 * @return bool
	 */
	function topscorer_woo_single_disable_page_title( $enable_page_title ) {
		$is_enabled = topscorer_get_post_value_through_levels( 'qodef_woo_single_enable_page_title' ) !== 'no';

		if ( ! $is_enabled && topscorer_is_woo_page( 'single' ) ) {
			$enable_page_title = false;
		}

		return $enable_page_title;
	}

	add_filter( 'topscorer_filter_enable_page_title', 'topscorer_woo_single_disable_page_title' );
}

if ( ! function_exists( 'topscorer_woo_single_thumb_images_position' ) ) {
	/**
	 * Function that changes the layout of thumbnails on single product page
	 */
	function topscorer_woo_single_thumb_images_position( $classes ) {
		$product_thumbnail_position = topscorer_is_installed( 'core' ) ? topscorer_get_post_value_through_levels( 'qodef_woo_single_thumb_images_position' ) : 'below';

		if ( ! empty( $product_thumbnail_position ) ) {
			$classes[] = 'qodef-position--' . $product_thumbnail_position;
		}

		return $classes;
	}

	add_filter( 'woocommerce_single_product_image_gallery_classes', 'topscorer_woo_single_thumb_images_position' );
}

if ( ! function_exists( 'topscorer_set_woo_custom_sidebar_name' ) ) {
	/**
	 * Function that return sidebar name
	 *
	 * @param $sidebar_name string
	 *
	 * @return string
	 */
	function topscorer_set_woo_custom_sidebar_name( $sidebar_name ) {
		// check local/meta and global vars on shop page
		if ( topscorer_is_woo_page( 'shop' ) || topscorer_is_woo_page( 'category' ) || topscorer_is_woo_page( 'tag' ) ) {
			$local_option  = topscorer_get_post_value_through_levels( 'qodef_page_custom_sidebar' );
			$global_option = topscorer_get_post_value_through_levels( 'qodef_woo_product_list_custom_sidebar' );

			// set default sidebar name to override 'woocommerce' name from plugin
			$sidebar_name = 'main-sidebar';

			// since var keys are different, check manually, first local then global
			if ( isset( $local_option ) && ! empty( $local_option ) ) {
				$sidebar_name = $local_option;
			} elseif ( isset( $global_option ) && ! empty( $global_option ) ) {
				$sidebar_name = $global_option;
			}
		} // check global vars on category and tag product archives
		elseif ( topscorer_is_woo_page( 'category' ) || topscorer_is_woo_page( 'tag' ) ) {
			$option = topscorer_get_post_value_through_levels( 'qodef_woo_product_list_custom_sidebar' );

			$sidebar_name = isset( $option ) && ! empty( $option ) ? $option : 'main-sidebar';
		}

		return $sidebar_name;
	}

	add_filter( 'topscorer_filter_sidebar_name', 'topscorer_set_woo_custom_sidebar_name' );
}

if ( ! function_exists( 'topscorer_set_woo_sidebar_layout' ) ) {
	/**
	 * Function that return sidebar layout
	 *
	 * @param $layout string
	 *
	 * @return string
	 */
	function topscorer_set_woo_sidebar_layout( $layout ) {
		// check local/meta and global vars on shop page
		if ( topscorer_is_woo_page( 'shop' ) ) {
			$local_option  = topscorer_get_post_value_through_levels( 'qodef_page_sidebar_layout' );
			$global_option = topscorer_get_post_value_through_levels( 'qodef_woo_product_list_sidebar_layout' );

			// set default sidebar layout
			$layout = 'no-sidebar';

			// since var keys are different, check manually, first local then global
			if ( isset( $local_option ) && ! empty( $local_option ) ) {
				$layout = $local_option;
			} elseif ( isset( $global_option ) && ! empty( $global_option ) ) {
				$layout = $global_option;
			}
		} // check global vars on category and tag product archives
		elseif ( topscorer_is_woo_page( 'category' ) || topscorer_is_woo_page( 'tag' ) ) {
			$option = topscorer_get_post_value_through_levels( 'qodef_woo_product_list_sidebar_layout' );

			$layout = isset( $option ) && ! empty( $option ) ? $option : 'no-sidebar';
		} // set sidebar layout for single, cart, checkout and account pages
		elseif ( topscorer_is_woo_page( 'single' ) || topscorer_is_woo_page( 'cart' ) || topscorer_is_woo_page( 'checkout' ) || topscorer_is_woo_page( 'account' ) ) {
			$layout = 'no-sidebar';
		}

		return $layout;
	}

	add_filter( 'topscorer_filter_sidebar_layout', 'topscorer_set_woo_sidebar_layout' );
}

if ( ! function_exists( 'topscorer_set_woo_sidebar_grid_gutter_classes' ) ) {
	/**
	 * Function that returns grid gutter classes
	 *
	 * @param $classes string
	 *
	 * @return string
	 */
	function topscorer_set_woo_sidebar_grid_gutter_classes( $classes ) {
		// check local/meta and global vars on shop page
		if ( topscorer_is_woo_page( 'shop' ) ) {
			$local_option  = topscorer_get_post_value_through_levels( 'qodef_page_sidebar_grid_gutter' );
			$global_option = topscorer_get_post_value_through_levels( 'qodef_woo_product_list_sidebar_grid_gutter' );

			// since var keys are different, check manually, first local then global
			if ( isset( $local_option ) && ! empty( $local_option ) ) {
				$classes = 'qodef-gutter--' . esc_attr( $local_option );
			} elseif ( isset( $global_option ) && ! empty( $global_option ) ) {
				$classes = 'qodef-gutter--' . esc_attr( $global_option );
			}
		} // check global vars on category and tag product archives
		elseif ( topscorer_is_woo_page( 'category' ) || topscorer_is_woo_page( 'tag' ) ) {
			$option = topscorer_get_post_value_through_levels( 'qodef_woo_product_list_sidebar_grid_gutter' );

			if ( isset( $option ) && ! empty( $option ) ) {
				$classes = 'qodef-gutter--' . esc_attr( $option );
			}
		}

		return $classes;
	}

	add_filter( 'topscorer_filter_grid_gutter_classes', 'topscorer_set_woo_sidebar_grid_gutter_classes' );
}