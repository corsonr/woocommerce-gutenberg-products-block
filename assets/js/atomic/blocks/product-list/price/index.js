/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { Disabled } from '@wordpress/components';
import Gridicon from 'gridicons';

/**
 * Internal dependencies
 */
import { ProductListPrice } from '../../../components/product-list';
import sharedConfig from '../shared-config';

/**
 * Register and run the "All Products" block.
 */
const blockConfig = {
	title: __( 'Product Price', 'woo-gutenberg-products-block' ),
	description: __(
		'Display the price of a product.',
		'woo-gutenberg-products-block'
	),
	icon: {
		src: <Gridicon icon="money" />,
		foreground: '#96588a',
	},
	edit( props ) {
		const { attributes } = props;

		return (
			<Disabled>
				<ProductListPrice product={ attributes.product } />
			</Disabled>
		);
	},
};

registerBlockType( 'woocommerce/product-list-price', {
	...sharedConfig,
	...blockConfig,
} );