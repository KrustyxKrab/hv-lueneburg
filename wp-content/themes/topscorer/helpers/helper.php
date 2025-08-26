<?php

if ( ! function_exists( 'topscorer_is_installed' ) ) {
	/**
	 * Function that checks if forward plugin installed
	 *
	 * @param $plugin string - plugin name
	 *
	 * @return bool
	 */
	function topscorer_is_installed( $plugin ) {

		switch ( $plugin ) {
			case 'framework';
				return class_exists( 'QodeFramework' );
				break;
			case 'core';
				return class_exists( 'TopScorerCore' );
				break;
			case 'woocommerce';
				return class_exists( 'WooCommerce' );
				break;
			default:
				return false;
		}
	}
}

if ( ! function_exists( 'topscorer_include_theme_is_installed' ) ) {
	/**
	 * Function that set case is installed element for framework functionality
	 *
	 * @param $installed bool
	 * @param $plugin    string - plugin name
	 *
	 * @return bool
	 */
	function topscorer_include_theme_is_installed( $installed, $plugin ) {

		if ( $plugin === 'theme' ) {
			return class_exists( 'TopscorerHandler' );
		}

		return $installed;
	}

	add_filter( 'qode_framework_filter_is_plugin_installed', 'topscorer_include_theme_is_installed', 10, 2 );
}

if ( ! function_exists( 'topscorer_template_part' ) ) {
	/**
	 * Function that echo module template part.
	 *
	 * @param string $module   name of the module from inc folder
	 * @param string $template full path of the template to load
	 * @param string $slug
	 * @param array  $params   array of parameters to pass to template
	 */
	function topscorer_template_part( $module, $template, $slug = '', $params = array() ) {
		echo topscorer_get_template_part( $module, $template, $slug, $params );
	}
}

if ( ! function_exists( 'topscorer_get_template_part' ) ) {
	/**
	 * Function that load module template part.
	 *
	 * @param string $module   name of the module from inc folder
	 * @param string $template full path of the template to load
	 * @param string $slug
	 * @param array  $params   array of parameters to pass to template
	 *
	 * @return string - string containing html of template
	 */
	function topscorer_get_template_part( $module, $template, $slug = '', $params = array() ) {
		$available_characters = '/[^A-Za-z0-9\_\-\/]/';

		if ( is_scalar( $module ) ) {
			$module = preg_replace( $available_characters, '', $module );
		} else {
			$module = '';
		}

		if ( is_scalar( $template ) ) {
			$template = preg_replace( $available_characters, '', $template );
		} else {
			$template = '';
		}

		if ( is_scalar( $slug ) ) {
			$slug = preg_replace( $available_characters, '', $slug );
		} else {
			$slug = '';
		}

		// HTML Content from template.
		$html          = '';
		$template_path = TOPSCORER_INC_ROOT_DIR . '/' . $module;

		$temp = $template_path . '/' . $template;

		// The array of parameters to pass to the template.
		if ( is_array( $params ) && count( $params ) ) {
			extract( $params, EXTR_SKIP ); // @codingStandardsIgnoreLine
		}

		$template = '';

		if ( ! empty( $temp ) ) {
			if ( ! empty( $slug ) ) {
				$template = "{$temp}-{$slug}.php";

				if ( ! file_exists( $template ) ) {
					$template = $temp . '.php';
				}
			} else {
				$template = $temp . '.php';
			}
		}

		if ( $template ) {
			ob_start();
			include $template; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			$html = ob_get_clean();
		}

		return $html;
	}
}

if ( ! function_exists( 'topscorer_get_page_id' ) ) {
	/**
	 * Function that returns current page id
	 * Additional conditional is to check if current page is any wp archive page (archive, category, tag, date etc.) and returns -1
	 *
	 * @return int
	 */
	function topscorer_get_page_id() {
		$page_id = get_queried_object_id();

		if ( topscorer_is_wp_template() ) {
			$page_id = - 1;
		}

		return apply_filters( 'topscorer_filter_page_id', $page_id );
	}
}

if ( ! function_exists( 'topscorer_is_wp_template' ) ) {
	/**
	 * Function that checks if current page default wp page
	 *
	 * @return bool
	 */
	function topscorer_is_wp_template() {
		return is_archive() || is_search() || is_404() || ( is_front_page() && is_home() );
	}
}

if ( ! function_exists( 'topscorer_get_ajax_status' ) ) {
	/**
	 * Function that return status from ajax functions
	 *
	 * @param $status   string - success or error
	 * @param $message  string - ajax message value
	 * @param $data     string|array - returned value
	 * @param $redirect string - url address
	 */
	function topscorer_get_ajax_status( $status, $message, $data = null, $redirect = '' ) {
		$response = array (
			'status'   => esc_attr( $status ),
			'message'  => esc_html( $message ),
			'data'     => $data,
			'redirect' => ! empty( $redirect ) ? esc_url( $redirect ) : '',
		);

		$output = json_encode( $response );

		exit( $output );
	}
}

if ( ! function_exists( 'topscorer_get_icon' ) ) {
	/**
	 * Function that return icon html
	 *
	 * @param $icon        string - icon class name
	 * @param $icon_pack   string - icon pack name
	 * @param $backup_text string - backup text label if framework is not installed
	 * @param $params      array - icon parameters
	 *
	 * @return string|mixed
	 */
	function topscorer_get_icon( $icon, $icon_pack, $backup_text, $params = array() ) {
		$value = topscorer_is_installed( 'framework' ) && topscorer_is_installed( 'core' ) ? qode_framework_icons()->render_icon( $icon, $icon_pack, $params ) : $backup_text;

		return $value;
	}
}

if ( ! function_exists( 'topscorer_render_icon' ) ) {
	/**
	 * Function that render icon html
	 *
	 * @param $icon        string - icon class name
	 * @param $icon_pack   string - icon pack name
	 * @param $backup_text string - backup text label if framework is not installed
	 * @param $params      array - icon parameters
	 */
	function topscorer_render_icon( $icon, $icon_pack, $backup_text, $params = array() ) {
		echo topscorer_get_icon( $icon, $icon_pack, $backup_text, $params );
	}
}

if ( ! function_exists( 'topscorer_get_button_element' ) ) {
	/**
	 * Function that returns button with provided params
	 *
	 * @param $params array - array of parameters
	 *
	 * @return string - string representing button html
	 */
	function topscorer_get_button_element( $params ) {
		if ( class_exists( 'TopScorerCoreButtonShortcode' ) ) {
			return TopScorerCoreButtonShortcode ::call_shortcode( $params );
		} else {
			$link   = isset( $params[ 'link' ] ) ? $params[ 'link' ] : '#';
			$target = isset( $params[ 'target' ] ) ? $params[ 'target' ] : '_self';
			$text   = isset( $params[ 'text' ] ) ? $params[ 'text' ] : '';

			return '<a itemprop="url" class="qodef-theme-button" href="' . esc_url( $link ) . '" target="' . esc_attr( $target ) . '">' . esc_html( $text ) . '</a>';
		}
	}
}

if ( ! function_exists( 'topscorer_render_button_element' ) ) {
	/**
	 * Function that render button with provided params
	 *
	 * @param $params array - array of parameters
	 */
	function topscorer_render_button_element( $params ) {
		echo topscorer_get_button_element( $params );
	}
}

if ( ! function_exists( 'topscorer_class_attribute' ) ) {
	/**
	 * Function that render class attribute
	 *
	 * @param $class string
	 */
	function topscorer_class_attribute( $class ) {
		echo topscorer_get_class_attribute( $class );
	}
}

if ( ! function_exists( 'topscorer_get_class_attribute' ) ) {
	/**
	 * Function that return class attribute
	 *
	 * @param $class string
	 *
	 * @return string|mixed
	 */
	function topscorer_get_class_attribute( $class ) {
		$value = topscorer_is_installed( 'framework' ) ? qode_framework_get_class_attribute( $class ) : '';

		return $value;
	}
}

if ( ! function_exists( 'topscorer_get_post_value_through_levels' ) ) {
	/**
	 * Function that returns meta value if exists
	 *
	 * @param string $name    name of option
	 * @param int    $post_id id of
	 *
	 * @return string value of option
	 */
	function topscorer_get_post_value_through_levels( $name, $post_id = null ) {
		return ( topscorer_is_installed( 'framework' ) && topscorer_is_installed( 'core' ) ) ? topscorer_core_get_post_value_through_levels( $name, $post_id ) : '';
	}
}

if ( ! function_exists( 'topscorer_wp_kses_html' ) ) {
	/**
	 * Function that does escaping of specific html.
	 * It uses wp_kses function with predefined attributes array.
	 *
	 * @param string $type    - type of html element
	 * @param string $content - string to escape
	 *
	 * @return string escaped output
	 * @see wp_kses()
	 *
	 */
	function topscorer_wp_kses_html( $type, $content ) {
		return topscorer_is_installed( 'framework' ) ? qode_framework_wp_kses_html( $type, $content ) : $content;
	}
}
if ( ! function_exists( 'topscorer_escape_title_tag' ) ) {
	/**
	 * Function that escape title tag variable for modules
	 *
	 * @param string $title_tag
	 *
	 * @return string
	 */
	function topscorer_escape_title_tag( $title_tag ) {
		$allowed_tags = array(
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6',
			'p',
			'span',
			'ul',
			'ol',
			'div',
		);

		$escaped_title_tag = '';
		$title_tag         = strtolower( sanitize_key( $title_tag ) );

		if ( in_array( $title_tag, $allowed_tags, true ) ) {
			$escaped_title_tag = $title_tag;
		}

		return $escaped_title_tag;
	}
}


if ( ! function_exists( 'topscorer_get_space_value' ) ) {
	/**
	 * Function that returns spacing value based on selected option
	 *
	 * @param string $text_value - textual value of spacing
	 *
	 * @return int
	 */
	function topscorer_get_space_value( $text_value ) {
		return topscorer_is_installed( 'core' ) ? topscorer_core_get_space_value( $text_value ) : 0;
	}
}
