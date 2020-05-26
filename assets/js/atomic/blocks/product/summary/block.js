/**
 * External dependencies
 */
import PropTypes from 'prop-types';
import classnames from 'classnames';
import {
	useInnerBlockConfigurationContext,
	useProductDataContextContext,
} from '@woocommerce/shared-context';
import Summary from '@woocommerce/base-components/summary';
import { getSetting } from '@woocommerce/settings';

const ProductSummary = ( { className } ) => {
	const { product } = useProductDataContextContext();
	const { layoutStyleClassPrefix } = useInnerBlockConfigurationContext();
	const componentClass = `${ layoutStyleClassPrefix }__product-summary`;

	if ( ! product ) {
		return (
			<div
				className={ classnames(
					className,
					componentClass,
					'is-loading'
				) }
			/>
		);
	}

	const source = product.short_description
		? product.short_description
		: product.description;

	if ( ! source ) {
		return null;
	}

	const countType = getSetting( 'wordCountType', 'words' );

	return (
		<Summary
			className={ classnames( className, componentClass ) }
			source={ source }
			maxLength={ 150 }
			countType={ countType }
		/>
	);
};

ProductSummary.propTypes = {
	className: PropTypes.string,
	product: PropTypes.object,
};

export default ProductSummary;