<?php

namespace App\Models;

use App\Models\AppBaseModel;

class OrderLineItemModel extends AppBaseModel
{
    protected $table         = 'order_line_item';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'id',
        'order_id',
        'product_variant_id',
        'product_name',
        'product_variant_name',
        'line_quantity',
        'unit_price',
        'line_subtotal',
        'item_need_delivery',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * @throws \ReflectionException
     */
    public function buyItem(int $orderId, int $productVariantId, string $productName, string $variantName, int $lineQuantity, float $unitPrice, float $lineSubtotal, string $needDelivery): string
    {
        $itemName     = "{$productName} - {$variantName}";
        $variantModel = new ProductVariantModel();
        $variant      = $variantModel->find($productVariantId);
        if (empty($variant)) {
            return lang('Checkout.error.adding-item', [$itemName]);
        }
        if ($lineQuantity > $variant['inventory_count']) {
            // if allow pre-order, update logic here (future)
            return lang('Checkout.error.product-out-of-stock', [$itemName]);
        }
        // add line item
        $lineItem = [
            'order_id'             => $orderId,
            'product_variant_id'   => $productVariantId,
            'product_name'         => $productName,
            'product_variant_name' => $variantName,
            'line_quantity'        => $lineQuantity,
            'unit_price'           => $unitPrice,
            'line_subtotal'        => $lineSubtotal,
            'item_need_delivery'   => $needDelivery,
        ];
        $this->insert($lineItem);
        // update variant quantity
        $newCount      = $variant['inventory_count'] - $lineQuantity;
        $variantModel->update($productVariantId, ['inventory_count' => $newCount]);
        // log variant inventory
        $inventoryModel = new ProductVariantInventoryModel();
        $quantityChange = -1 * $lineQuantity;
        $inventoryLine  = [
            'variant_id'      => $productVariantId,
            'activity_key'    => 'buy',
            'quantity_change' => $quantityChange,
            'new_inventory'   => $newCount,
        ];
        $inventoryModel->insert($inventoryLine);
        return '';
    }

    public function getLineItemsByOrderId(int $orderId): array
    {
        return $this->select('order_line_item.*, product_variant.variant_name AS main_variant_name, product_variant.variant_local_names, product_variant.variant_sku, product_variant.product_id,
            product_master.product_name AS main_product_name, product_master.product_local_names, product_master.product_slug')
            ->join('product_variant', 'order_line_item.product_variant_id = product_variant.id')
            ->join('product_master', 'product_variant.product_id = product_master.id')
            ->where('order_id', $orderId)
            ->findAll();
    }
}