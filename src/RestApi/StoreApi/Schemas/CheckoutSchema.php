<?php
/**
 * Checkout schema for the Store API.
 *
 * @package WooCommerce/Blocks
 */

namespace Automattic\WooCommerce\Blocks\RestApi\StoreApi\Schemas;

defined( 'ABSPATH' ) || exit;

use Automattic\WooCommerce\Blocks\Payments\PaymentResult;

/**
 * CheckoutSchema class.
 */
class CheckoutSchema extends AbstractSchema {
	/**
	 * The schema item name.
	 *
	 * @var string
	 */
	protected $title = 'checkout';

	/**
	 * Checkout schema properties.
	 *
	 * @return array
	 */
	public function get_properties() {
		return [
			'order_id'         => [
				'description' => __( 'The order ID to process during checkout.', 'woo-gutenberg-products-block' ),
				'type'        => 'integer',
				'context'     => [ 'view', 'edit' ],
				'readonly'    => true,
			],
			'status'           => [
				'description' => __( 'Order status. Payment providers will update this value after payment.', 'woo-gutenberg-products-block' ),
				'type'        => 'string',
				'context'     => [ 'view', 'edit' ],
				'readonly'    => true,
			],
			'order_key'        => [
				'description' => __( 'Order key used to check validity or protect access to certain order data.', 'woo-gutenberg-products-block' ),
				'type'        => 'string',
				'context'     => [ 'view', 'edit' ],
				'readonly'    => true,
			],
			'customer_note'    => [
				'description' => __( 'Note added to the order by the customer during checkout.', 'woo-gutenberg-products-block' ),
				'type'        => 'string',
				'context'     => [ 'view', 'edit' ],
			],
			'billing_address'  => [
				'description' => __( 'Billing address.', 'woo-gutenberg-products-block' ),
				'type'        => 'object',
				'context'     => [ 'view', 'edit' ],
				'properties'  => ( new BillingAddressSchema() )->get_properties(),
			],
			'shipping_address' => [
				'description' => __( 'Shipping address.', 'woo-gutenberg-products-block' ),
				'type'        => 'object',
				'context'     => [ 'view', 'edit' ],
				'properties'  => ( new ShippingAddressSchema() )->get_properties(),
			],
			'payment_method'   => [
				'description' => __( 'The ID of the payment method being used to process the payment.', 'woo-gutenberg-products-block' ),
				'type'        => 'string',
				'context'     => [ 'view', 'edit' ],
			],
			'payment_result'   => [
				'description' => __( 'Result of payment processing, or false if not yet processed.', 'woo-gutenberg-products-block' ),
				'type'        => 'object',
				'context'     => [ 'view', 'edit' ],
				'readonly'    => true,
				'properties'  => [
					'payment_status'  => [
						'description' => __( 'Status of the payment returned by the gateway. One of success, pending, failure, error.', 'woo-gutenberg-products-block' ),
						'readonly'    => true,
						'type'        => 'string',
					],
					'payment_details' => [
						'description' => __( 'An array of data being returned from the payment gateway.', 'woo-gutenberg-products-block' ),
						'readonly'    => true,
						'type'        => 'array',
						'items'       => [
							'type'       => 'object',
							'properties' => [
								'key'   => [
									'type' => 'string',
								],
								'value' => [
									'type' => 'string',
								],
							],
						],
					],
					'redirect_url'    => [
						'description' => __( 'A URL to redirect the customer after checkout. This could be, for example, a link to the payment processors website.', 'woo-gutenberg-products-block' ),
						'readonly'    => true,
						'type'        => 'string',
					],
				],
			],
		];
	}

	/**
	 * Return the response for checkout.
	 *
	 * @param object $item Results from checkout action.
	 * @return array
	 */
	public function get_item_response( $item ) {
		return $this->get_checkout_response( $item->order, $item->payment_result );
	}

	/**
	 * Get the checkout response based on the current order and any payments.
	 *
	 * @param \WC_Order     $order Order object.
	 * @param PaymentResult $payment_result Payment result object.
	 * @return array
	 */
	protected function get_checkout_response( \WC_Order $order, PaymentResult $payment_result = null ) {
		return [
			'order_id'         => $order->get_id(),
			'status'           => $order->get_status(),
			'order_key'        => $order->get_order_key(),
			'customer_note'    => $order->get_customer_note(),
			'billing_address'  => ( new BillingAddressSchema() )->get_item_response( $order ),
			'shipping_address' => ( new ShippingAddressSchema() )->get_item_response( $order ),
			'payment_method'   => $order->get_payment_method(),
			'payment_result'   => [
				'payment_status'  => $payment_result->status,
				'payment_details' => $payment_result->payment_details,
				'redirect_url'    => $payment_result->redirect_url,
			],
		];
	}
}