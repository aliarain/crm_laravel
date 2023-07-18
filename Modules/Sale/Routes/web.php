<?php

use App\Exports\SaleCategoryExport;
use Modules\Sale\Http\Controllers\CouponController;
use Modules\Sale\Http\Controllers\GiftcardController;
use Modules\Sale\Http\Controllers\ProductBrandController;
use Modules\Sale\Http\Controllers\ProductCategoryController;
use Modules\Sale\Http\Controllers\ProductTaxController;
use Modules\Sale\Http\Controllers\ProductUnitController;
use Modules\Sale\Http\Controllers\ProductWarehouseController;
use Modules\Sale\Http\Controllers\SaleAdjusmentController;
use Modules\Sale\Http\Controllers\SaleCashRegisterController;
use Modules\Sale\Http\Controllers\SaleController;
use Modules\Sale\Http\Controllers\SaleDeliveryController;
use Modules\Sale\Http\Controllers\SaleExpenseCategoryController;
use Modules\Sale\Http\Controllers\SaleExpenseController;
use Modules\Sale\Http\Controllers\SaleProductController;
use Modules\Sale\Http\Controllers\SalePurchaseController;
use Modules\Sale\Http\Controllers\SaleQuotationController;
use Modules\Sale\Http\Controllers\SaleReturnController;
use Modules\Sale\Http\Controllers\SaleReturnPurchaseController;
use Modules\Sale\Http\Controllers\SaleStockCountController;
use Modules\Sale\Http\Controllers\SaleSupplierController;
use Modules\Sale\Http\Controllers\SaleTransferController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::group(['middleware' => ['xss', 'admin', 'TimeZone'], 'prefix' => 'sale'], function () {
    $permission = [
        'sales' => [
            'sale_create',
            'sale_edit',
            'sale_delete',
            'sale_view',
            'sale_list',
        ],
        'sale_return' => [
            'sale_return_create',
            'sale_return_edit',
            'sale_return_delete',
            'sale_return_view',
            'sale_return_list',
        ],
        'sale_delivery' => [
            'sale_delivery_create',
            'sale_delivery_edit',
            'sale_delivery_delete',
            'sale_delivery_view',
            'sale_delivery_list',
        ],
        'sale_transfer' => [
            'sale_transfer_create',
            'sale_transfer_edit',
            'sale_transfer_delete',
            'sale_transfer_view',
            'sale_transfer_list',
        ],
        'sale_purchase' => [
            'sale_purchase_create',
            'sale_purchase_edit',
            'sale_purchase_delete',
            'sale_purchase_view',
            'sale_purchase_list',
        ],
        'sale_return_purchase' => [
            'sale_return_purchase_create',
            'sale_return_purchase_edit',
            'sale_return_purchase_delete',
            'sale_return_purchase_view',
            'sale_return_purchase_list',
        ],
        'sale_quotation' => [
            'sale_quotation_create',
            'sale_quotation_edit',
            'sale_quotation_delete',
            'sale_quotation_view',
            'sale_quotation_list',
        ],
        'sale_cash_register' => [
            'sale_cash_register_create',
            'sale_cash_register_edit',
            'sale_cash_register_delete',
            'sale_cash_register_view',
            'sale_cash_register_list',
        ],
        'sale_stock_count' => [
            'sale_stock_count_create',
            'sale_stock_count_edit',
            'sale_stock_count_delete',
            'sale_stock_count_view',
            'sale_stock_count_list',
        ],
        'sale_adjusment' => [
            'sale_adjusment_create',
            'sale_adjusment_edit',
            'sale_adjusment_delete',
            'sale_adjusment_view',
            'sale_adjusment_list',
        ],
        'sale_expense' => [
            'sale_expense_create',
            'sale_expense_edit',
            'sale_expense_delete',
            'sale_expense_view',
            'sale_expense_list',
        ],
        'sale_supplier' => [
            'sale_supplier_create',
            'sale_supplier_edit',
            'sale_supplier_delete',
            'sale_supplier_view',
            'sale_supplier_list',
        ],
        'sale_product' => [
            'sale_product_create',
            'sale_product_edit',
            'sale_product_delete',
            'sale_product_view',
            'sale_product_list',
        ],
        'sale_product_category' => [
            'sale_product_category_create',
            'sale_product_category_edit',
            'sale_product_category_delete',
            'sale_product_category_view',
            'sale_product_category_list',
        ],
        'sale_product_brand' => [
            'sale_product_brand_create',
            'sale_product_brand_edit',
            'sale_product_brand_delete',
            'sale_product_brand_view',
            'sale_product_brand_list',
        ],
        'sale_product_unit' => [
            'sale_product_unit_create',
            'sale_product_unit_edit',
            'sale_product_unit_delete',
            'sale_product_unit_view',
            'sale_product_unit_list',
        ],
        'sale_product_warehouse' => [
            'sale_product_warehouse_create',
            'sale_product_warehouse_edit',
            'sale_product_warehouse_delete',
            'sale_product_warehouse_view',
            'sale_product_warehouse_list',
        ],
        'sale_product_tax' => [
            'sale_product_tax_create',
            'sale_product_tax_edit',
            'sale_product_tax_delete',
            'sale_product_tax_view',
            'sale_product_tax_list',
        ],
        'sale_product_discount' => [
            'sale_product_discount_create',
            'sale_product_discount_edit',
            'sale_product_discount_delete',
            'sale_product_discount_view',
            'sale_product_discount_list',
        ],

    ];
    Route::group(['prefix' => 'product'], function () {
        // Route::get('category/list', [ProductCategoryController::class, 'index'])->name('saleProductCategory.index');
        // // Route::get('category/create', [ProductCategoryController::class, 'create'])->name('saleProductCategory.create');
        // // Route::post('category/store', [ProductCategoryController::class, 'store'])->name('saleProductCategory.store');
        // // Route::get('category/edit/{id}', [ProductCategoryController::class, 'edit'])->name('saleProductCategory.edit');
        // // Route::post('category/update/{id}', [ProductCategoryController::class, 'update'])->name('saleProductCategory.update');
        // Route::get('category/destroy', [ProductCategoryController::class, 'destroy'])->name('saleProductCategory.destroy');

        // Route::post('category/category-data', [ProductCategoryController::class, 'categoryData'])->name('saleProductCategory.categoryData');
        // Route::any('category/deletebyselection', [ProductCategoryController::class, 'deleteBySelection'])->name('saleProductCategory.deleteBySelection');

        // category
        Route::get('category/list', [ProductCategoryController::class, 'index'])->name('saleProductCategory.index');
        Route::post('category/store', [ProductCategoryController::class, 'store'])->name('saleProductCategory.store');
        Route::get('category/{id}/edit', [ProductCategoryController::class, 'edit'])->name('saleProductCategory.edit');
        Route::post('category/update', [ProductCategoryController::class, 'update'])->name('saleProductCategory.update');
        Route::delete('category/delete/{id}', [ProductCategoryController::class, 'destroy'])->name('saleProductCategory.destroy');
        Route::post('importcategory', [ProductCategoryController::class, 'importCategory'])->name('saleProductCategory.import');
        Route::post('category/deletebyselection', [ProductCategoryController::class, 'deleteBySelection']);
        // export
        Route::get('category/export', [ProductCategoryController::class, 'exportCsv'])->name('saleProductCategory.export');

        // Route::get('category/export', function () {
        //     return Excel::download(new SaleCategoryExport, 'SaleCategoryExport_' . time() . '.csv');
        // });

        // brand
        Route::get('brand/list', [ProductBrandController::class, 'index'])->name('saleProductBrand.index');
        Route::post('brand/store', [ProductBrandController::class, 'store'])->name('saleProductBrand.store');
        Route::get('brand/{id}/edit', [ProductBrandController::class, 'edit'])->name('saleProductBrand.edit');
        Route::post('brand/update', [ProductBrandController::class, 'update'])->name('saleProductBrand.update');
        Route::delete('brand/delete/{id}', [ProductBrandController::class, 'destroy'])->name('saleProductBrand.destroy');
        Route::post('importbrand', [ProductBrandController::class, 'importBrand'])->name('saleProductBrand.import');
        Route::post('brand/deletebyselection', [ProductBrandController::class, 'deleteBySelection']);

        // unit
        Route::get('unit/list', [ProductUnitController::class, 'index'])->name('saleProductUnit.index');
        Route::post('unit/store', [ProductUnitController::class, 'store'])->name('saleProductUnit.store');
        Route::get('unit/{id}/edit', [ProductUnitController::class, 'edit'])->name('saleProductUnit.edit');
        Route::post('unit/update', [ProductUnitController::class, 'update'])->name('saleProductUnit.update');
        Route::delete('unit/delete/{id}', [ProductUnitController::class, 'destroy'])->name('saleProductUnit.destroy');
        Route::post('importunit', [ProductUnitController::class, 'importUnit'])->name('saleProductUnit.import');
        Route::post('unit/deletebyselection', [ProductUnitController::class, 'deleteBySelection']);

        // Tax
        Route::get('tax/list', [ProductTaxController::class, 'index'])->name('saleProductTax.index');
        Route::post('tax/store', [ProductTaxController::class, 'store'])->name('saleProductTax.store');
        Route::get('tax/{id}/edit', [ProductTaxController::class, 'edit'])->name('saleProductTax.edit');
        Route::post('tax/update', [ProductTaxController::class, 'update'])->name('saleProductTax.update');
        Route::delete('tax/delete/{id}', [ProductTaxController::class, 'destroy'])->name('saleProductTax.destroy');
        Route::post('importtax', [ProductTaxController::class, 'importTax'])->name('saleProductTax.import');
        Route::post('tax/deletebyselection', [ProductTaxController::class, 'deleteBySelection']);

        // warehouse
        Route::get('warehouse/list', [ProductWarehouseController::class, 'index'])->name('saleProductWarehouse.index');
        Route::post('warehouse/store', [ProductWarehouseController::class, 'store'])->name('saleProductWarehouse.store');
        Route::get('warehouse/{id}/edit', [ProductWarehouseController::class, 'edit'])->name('saleProductWarehouse.edit');
        Route::post('warehouse/update', [ProductWarehouseController::class, 'update'])->name('saleProductWarehouse.update');
        Route::delete('warehouse/delete/{id}', [ProductWarehouseController::class, 'destroy'])->name('saleProductWarehouse.destroy');
        Route::post('importwarehouse', [ProductWarehouseController::class, 'importWarehouse'])->name('saleProductWarehouse.import');

        // supplier
        Route::get('supplier/list', [SaleSupplierController::class, 'index'])->name('saleSupplier.index');
        Route::get('supplier/create', [SaleSupplierController::class, 'create'])->name('saleSupplier.create');
        Route::post('supplier/store', [SaleSupplierController::class, 'store'])->name('saleSupplier.store');
        Route::get('supplier/{id}/edit', [SaleSupplierController::class, 'edit'])->name('saleSupplier.edit');
        Route::post('supplier/update', [SaleSupplierController::class, 'update'])->name('saleSupplier.update');
        Route::delete('supplier/delete/{id}', [SaleSupplierController::class, 'destroy'])->name('saleSupplier.destroy');
        Route::post('importsupplier', [SaleSupplierController::class, 'importsupplier'])->name('saleSupplier.import');
        Route::post('supplier/deletebyselection', [SaleSupplierController::class, 'deleteBySelection']);
        Route::post('suppliers/clear-due', [SaleSupplierController::class, 'clearDue'])->name('saleSupplier.clearDue');

        // product
        Route::get('product/list', [SaleProductController::class, 'index'])->name('saleProduct.index');
        Route::get('product/create', [SaleProductController::class, 'create'])->name('saleProduct.create');
        Route::post('product/store', [SaleProductController::class, 'store'])->name('saleProduct.store');
        Route::get('product/{id}/edit', [SaleProductController::class, 'edit'])->name('saleProduct.edit');
        Route::post('product/update', [SaleProductController::class, 'update'])->name('saleProduct.update');
        Route::delete('product/delete/{id}', [SaleProductController::class, 'destroy'])->name('saleProduct.destroy');
        Route::post('importproduct', [SaleProductController::class, 'importproduct'])->name('saleProduct.import');
        Route::get('product/details-data/{id}', [SaleProductController::class, 'productDetails'])->name('saleProduct.details');

        Route::get('product/saleunit/{id}', [SaleProductController::class, 'saleUnit'])->name('saleProduct.saleUnit');
        Route::get('product/gencode', [SaleProductController::class, 'generateCode']);

        Route::get('products/print_barcode', [SaleProductController::class, 'printBarcode'])->name('saleProduct.printBarcode');
        Route::get('products/ot_crm_product_search', [SaleProductController::class, 'ot_crmProductSearch'])->name('saleProduct.search');
        Route::get('products/history/{id}', [SaleProductController::class, 'history'])->name('saleProduct.history');

        Route::post('products/product-data', 'ProductController@productData');
        Route::get('products/search', 'ProductController@search');
        Route::get('products/saleunit/{id}', 'ProductController@saleUnit');
        Route::get('products/getdata/{id}/{variant_id}', 'ProductController@getData');
        Route::get('products/product_warehouse/{id}', 'ProductController@productWarehouseData');
        // Route::post('importproduct', 'ProductController@importProduct')->name('product.import');
        Route::post('exportproduct', 'ProductController@exportProduct')->name('product.export');
        Route::post('products/deletebyselection', 'ProductController@deleteBySelection');
        Route::post('products/update', 'ProductController@updateProduct');
        Route::get('products/variant-data/{id}', 'ProductController@variantData');
        Route::post('products/sale-history-data', 'ProductController@saleHistoryData');
        Route::post('products/purchase-history-data', 'ProductController@purchaseHistoryData');
        Route::post('products/sale-return-history-data', 'ProductController@saleReturnHistoryData');
        Route::post('products/purchase-return-history-data', 'ProductController@purchaseReturnHistoryData');

        // adjustment
        Route::get('adjustment/list', [SaleAdjusmentController::class, 'index'])->name('saleAdjustment.index');
        Route::get('adjustment/create', [SaleAdjusmentController::class, 'create'])->name('saleAdjustment.create');
        Route::post('adjustment/store', [SaleAdjusmentController::class, 'store'])->name('saleAdjustment.store');
        Route::get('adjustment/{id}/edit', [SaleAdjusmentController::class, 'edit'])->name('saleAdjustment.edit');
        Route::post('adjustment/update/{id}', [SaleAdjusmentController::class, 'update'])->name('saleAdjustment.update');
        Route::delete('adjustment/delete/{id}', [SaleAdjusmentController::class, 'destroy'])->name('saleAdjustment.destroy');

        Route::get('adjustment/getproduct/{id}', [SaleAdjusmentController::class, 'getProduct'])->name('saleAdjustment.getproduct');
        Route::get('adjustment/ot_crm_product_search', [SaleAdjusmentController::class, 'ot_crmProductSearch'])->name('saleAdjustment.search');

        // Stock count
        Route::get('stock-count/list', [SaleStockCountController::class, 'index'])->name('saleStockCount.index');
        Route::post('stock-count/store', [SaleStockCountController::class, 'store'])->name('saleStockCount.store');

        Route::post('stock-count/finalize', [SaleStockCountController::class, 'finalize'])->name('saleStockCount.finalize');
        Route::get('stock-count/stockdif/{id}', [SaleStockCountController::class, 'stockDif']);
        Route::get('stock-count/{id}/qty_adjustment', [SaleStockCountController::class, 'qtyAdjustment'])->name('saleStockCount.adjustment');

    });
    // expense
    Route::group(['prefix' => 'expense'], function () {
        // category
        Route::get('category/list', [SaleExpenseCategoryController::class, 'index'])->name('saleProductExpenseCategory.index');
        Route::post('category/store', [SaleExpenseCategoryController::class, 'store'])->name('saleProductExpenseCategory.store');
        Route::get('category/{id}/edit', [SaleExpenseCategoryController::class, 'edit'])->name('saleProductExpenseCategory.edit');
        Route::post('category/update', [SaleExpenseCategoryController::class, 'update'])->name('saleProductExpenseCategory.update');
        Route::delete('category/delete/{id}', [SaleExpenseCategoryController::class, 'destroy'])->name('saleProductExpenseCategory.destroy');
        Route::post('importcategory', [SaleExpenseCategoryController::class, 'importcategory'])->name('saleProductExpenseCategory.import');
        Route::post('category/deletebyselection', [SaleExpenseCategoryController::class, 'deleteBySelection']);

        Route::get('category/gencode', [SaleExpenseCategoryController::class, 'generateCode']);

        // expense
        Route::get('list', [SaleExpenseController::class, 'index'])->name('saleExpense.index');
        Route::post('store', [SaleExpenseController::class, 'store'])->name('saleExpense.store');
        Route::get('{id}/edit', [SaleExpenseController::class, 'edit'])->name('saleExpense.edit');
        Route::post('update', [SaleExpenseController::class, 'update'])->name('saleExpense.update');
        Route::delete('delete/{id}', [SaleExpenseController::class, 'destroy'])->name('saleExpense.destroy');
    });

    Route::group(['prefix' => 'purchase'], function () {
        Route::get('list', [SalePurchaseController::class, 'index'])->name('salePurchase.index');
        Route::get('create', [SalePurchaseController::class, 'create'])->name('salePurchase.create');
        Route::post('store', [SalePurchaseController::class, 'store'])->name('salePurchase.store');
        Route::get('{id}/edit', [SalePurchaseController::class, 'edit'])->name('salePurchase.edit');
        Route::post('update/{id}', [SalePurchaseController::class, 'update'])->name('salePurchase.update');
        Route::delete('delete/{id}', [SalePurchaseController::class, 'destroy'])->name('salePurchase.destroy');

        Route::post('purchase-data', [SalePurchaseController::class, 'purchaseData'])->name('salePurchase.data');
        Route::get('product_purchase/{id}', [SalePurchaseController::class, 'productPurchaseData']);
        Route::get('ot_crm_product_search', [SalePurchaseController::class, 'ot_crmProductSearch'])->name('product_purchase.search');
        Route::post('add_payment', [SalePurchaseController::class, 'addPayment'])->name('salePurchase.add-payment');
        Route::get('getpayment/{id}', [SalePurchaseController::class, 'getPayment'])->name('salePurchase.get-payment');
        Route::post('updatepayment', [SalePurchaseController::class, 'updatePayment'])->name('salePurchase.update-payment');
        Route::post('deletepayment', [SalePurchaseController::class, 'deletePayment'])->name('salePurchase.delete-payment');
        Route::get('purchase_by_csv', [SalePurchaseController::class, 'purchaseByCsv'])->name('salePurchase.by_csv');
        Route::post('importpurchase', [SalePurchaseController::class, 'importPurchase'])->name('salePurchase.import');

    });

    // sale
    Route::group(['prefix' => 'sale'], function () {

        // sale
        Route::get('list', [SaleController::class, 'index'])->name('saleSale.index');
        Route::get('create', [SaleController::class, 'create'])->name('saleSale.create');
        Route::post('store', [SaleController::class, 'store'])->name('saleSale.store');
        Route::get('{id}/edit', [SaleController::class, 'edit'])->name('saleSale.edit');
        Route::post('update/{id}', [SaleController::class, 'update'])->name('saleSale.update');
        Route::delete('delete/{id}', [SaleController::class, 'destroy'])->name('saleSale.destroy');

        Route::post('sale-data', [SaleController::class, 'saleData']);
        Route::post('sendmail', [SaleController::class, 'sendMail'])->name('saleSale.sendmail');
        Route::get('sale_by_csv', [SaleController::class, 'saleByCsv']);
        Route::get('product_sale/{id}', [SaleController::class, 'productSaleData']);
        Route::post('importsale', [SaleController::class, 'importSale'])->name('saleSale.import');
        Route::get('pos', [SaleController::class, 'posSale'])->name('saleSale.pos');
        Route::get('ot_crm_sale_search', [SaleController::class, 'ot_crmSaleSearch'])->name('saleSale.search');
        Route::get('ot_crm_product_search', [SaleController::class, 'ot_crmProductSearch'])->name('product_saleSale.search');
        Route::get('getcustomergroup/{id}', [SaleController::class, 'getCustomerGroup'])->name('saleSale.getcustomergroup');
        Route::get('getproduct/{id}', [SaleController::class, 'getProduct'])->name('saleSale.getproduct');
        Route::get('getproduct/{category_id}/{brand_id}', [SaleController::class, 'getProductByFilter']);
        Route::get('getfeatured', [SaleController::class, 'getFeatured']);
        Route::get('get_gift_card', [SaleController::class, 'getGiftCard']);
        Route::get('paypalSuccess', [SaleController::class, 'paypalSuccess']);
        Route::get('paypalPaymentSuccess/{id}', [SaleController::class, 'paypalPaymentSuccess']);
        Route::get('gen_invoice/{id}', [SaleController::class, 'genInvoice'])->name('saleSale.invoice');
        Route::post('add_payment', [SaleController::class, 'addPayment'])->name('saleSale.add-payment');
        Route::get('getpayment/{id}', [SaleController::class, 'getPayment'])->name('saleSale.get-payment');
        Route::post('updatepayment', [SaleController::class, 'updatePayment'])->name('saleSale.update-payment');
        Route::post('deletepayment', [SaleController::class, 'deletePayment'])->name('saleSale.delete-payment');
        Route::get('{id}/create', [SaleController::class, 'createSale']);
        Route::post('deletebyselection', [SaleController::class, 'deleteBySelection']);
        Route::get('print-last-reciept', [SaleController::class, 'printLastReciept'])->name('sales.printLastReciept');
        Route::get('today-sale', [SaleController::class, 'todaySale']);
        Route::get('today-profit/{warehouse_id}', [SaleController::class, 'todayProfit']);
        Route::get('check-discount', [SaleController::class, 'checkDiscount']);

        Route::get('cash-register', [SaleCashRegisterController::class, 'index'])->name('cashRegister.index');
        Route::get('cash-register/check-availability/{warehouse_id}', [SaleCashRegisterController::class, 'checkAvailability'])->name('cashRegister.checkAvailability');
        Route::post('cash-register/store', [SaleCashRegisterController::class, 'store'])->name('cashRegister.store');
        Route::get('cash-register/getDetails/{id}', [SaleCashRegisterController::class, 'getDetails']);
        Route::get('cash-register/showDetails/{warehouse_id}', [SaleCashRegisterController::class, 'showDetails']);
        Route::post('cash-register/close', [SaleCashRegisterController::class, 'close'])->name('cashRegister.close');

        // coupon
        Route::get('coupon/list', [CouponController::class, 'index'])->name('saleCoupon.index');
        Route::post('coupon/store', [CouponController::class, 'store'])->name('saleCoupon.store');
        Route::get('coupon/{id}/edit', [CouponController::class, 'edit'])->name('saleCoupon.edit');
        Route::post('coupon/update', [CouponController::class, 'update'])->name('saleCoupon.update');
        Route::delete('coupon/delete/{id}', [CouponController::class, 'destroy'])->name('saleCoupon.destroy');
        Route::post('coupon/deletebyselection', [CouponController::class, 'deleteBySelection']);

        Route::get('coupon/gencode', [CouponController::class, 'generateCode']);

        // giftcard
        Route::get('giftcard/list', [GiftcardController::class, 'index'])->name('saleGiftcard.index');
        Route::post('giftcard/store', [GiftcardController::class, 'store'])->name('saleGiftcard.store');
        Route::get('giftcard/{id}/edit', [GiftcardController::class, 'edit'])->name('saleGiftcard.edit');
        Route::post('giftcard/update', [GiftcardController::class, 'update'])->name('saleGiftcard.update');
        Route::delete('giftcard/delete/{id}', [GiftcardController::class, 'destroy'])->name('saleGiftcard.destroy');
        Route::post('giftcard/deletebyselection', [GiftcardController::class, 'deleteBySelection']);

        Route::post('giftcard/recharge/{id}', [GiftcardController::class, 'recharge'])->name('saleGiftcard.recharge');

        Route::get('giftcard/gencode', [GiftcardController::class, 'generateCode']);

        // Delivery
        Route::get('delivery/list', [SaleDeliveryController::class, 'index'])->name('saleDelivery.index');
        Route::get('delivery/create/{id}', [SaleDeliveryController::class, 'create']);
        Route::post('delivery/store', [SaleDeliveryController::class, 'store'])->name('saleDelivery.store');
        Route::get('delivery/{id}/edit', [SaleDeliveryController::class, 'edit'])->name('saleDelivery.edit');
        Route::post('delivery/update', [SaleDeliveryController::class, 'update'])->name('saleDelivery.update');
        Route::delete('delivery/delete/{id}', [SaleDeliveryController::class, 'destroy'])->name('saleDelivery.destroy');
        Route::get('delivery/product_delivery/{id}', [SaleDeliveryController::class, 'productDeliveryData'])->name('saleDelivery.productDeliveryData');
        Route::post('delivery/sendmail', [SaleDeliveryController::class, 'sendMail'])->name('saleDelivery.sendMail');
    });

    // quotation
    Route::group(['prefix' => 'quotation'], function () {
        Route::get('list', [SaleQuotationController::class, 'index'])->name('saleQuotation.index');
        Route::get('create', [SaleQuotationController::class, 'create'])->name('saleQuotation.create');
        Route::post('store', [SaleQuotationController::class, 'store'])->name('saleQuotation.store');
        Route::get('{id}/edit', [SaleQuotationController::class, 'edit'])->name('saleQuotation.edit');
        Route::post('update/{id}', [SaleQuotationController::class, 'update'])->name('saleQuotation.update');
        Route::delete('delete/{id}', [SaleQuotationController::class, 'destroy'])->name('saleQuotation.destroy');

        Route::post('quotation-data', [SaleQuotationController::class, 'quotationData'])->name('saleQuotation.data');
        Route::get('product_quotation/{id}', [SaleQuotationController::class, 'productQuotationData'])->name('saleQuotation.data');
        Route::get('ot_crm_product_search', [SaleQuotationController::class, 'ot_crmProductSearch'])->name('saleQuotation.search');
        Route::get('getcustomergroup/{id}', [SaleQuotationController::class, 'getCustomerGroup'])->name('quotation.getcustomergroup');
        Route::get('getproduct/{id}', [SaleQuotationController::class, 'getProduct'])->name('saleQuotation.getproduct');
        Route::get('{id}/create_sale', [SaleQuotationController::class, 'createSale'])->name('saleQuotation.create_sale');
        Route::get('{id}/create_purchase', [SaleQuotationController::class, 'createPurchase'])->name('saleQuotation.create_purchase');

    });

    // return
    Route::group(['prefix' => 'return-sale'], function () {
        Route::get('list', [SaleReturnController::class, 'index'])->name('saleReturn.index');
        Route::get('create', [SaleReturnController::class, 'create'])->name('saleReturn.create');
        Route::post('store', [SaleReturnController::class, 'store'])->name('saleReturn.store');
        Route::get('{id}/edit', [SaleReturnController::class, 'edit'])->name('saleReturn.edit');
        Route::post('update/{id}', [SaleReturnController::class, 'update'])->name('saleReturn.update');
        Route::delete('delete/{id}', [SaleReturnController::class, 'destroy'])->name('saleReturn.destroy');

        Route::post('return-data', [SaleReturnController::class, 'returnData']);
        Route::get('getcustomergroup/{id}', [SaleReturnController::class, 'getCustomerGroup'])->name('return-sale.getcustomergroup');
        Route::post('sendmail', [SaleReturnController::class, 'sendMail'])->name('return-sale.sendmail');
        Route::get('getproduct/{id}', [SaleReturnController::class, 'getProduct'])->name('return-sale.getproduct');
        Route::get('ot_crm_product_search', [SaleReturnController::class, 'ot_crmProductSearch'])->name('product_return-sale.search');
        Route::get('product_return/{id}', [SaleReturnController::class, 'productReturnData']);
        Route::post('deletebyselection', [SaleReturnController::class, 'deleteBySelection']);

    });

    Route::group(['prefix' => 'return-purchase'], function () {
        Route::get('list', [SaleReturnPurchaseController::class, 'index'])->name('purchaseReturn.index');
        Route::get('create', [SaleReturnPurchaseController::class, 'create'])->name('purchaseReturn.create');
        Route::post('store', [SaleReturnPurchaseController::class, 'store'])->name('purchaseReturn.store');
        Route::get('{id}/edit', [SaleReturnPurchaseController::class, 'edit'])->name('purchaseReturn.edit');
        Route::post('update/{id}', [SaleReturnPurchaseController::class, 'update'])->name('purchaseReturn.update');
        Route::delete('delete/{id}', [SaleReturnPurchaseController::class, 'destroy'])->name('purchaseReturn.destroy');

        Route::post('return-data', [SaleReturnPurchaseController::class, 'returnData']);
        Route::get('getcustomergroup/{id}', [SaleReturnPurchaseController::class, 'getCustomerGroup'])->name('return-sale.getcustomergroup');
        Route::post('sendmail', [SaleReturnPurchaseController::class, 'sendMail'])->name('return-sale.sendmail');
        Route::get('getproduct/{id}', [SaleReturnPurchaseController::class, 'getProduct'])->name('return-sale.getproduct');
        Route::get('ot_crm_product_search', [SaleReturnPurchaseController::class, 'ot_crmProductSearch'])->name('product_return-sale.search');
        Route::get('product_return/{id}', [SaleReturnPurchaseController::class, 'productReturnData']);
        Route::post('deletebyselection', [SaleReturnPurchaseController::class, 'deleteBySelection']);

    });
    Route::group(['prefix' => 'transfer'], function () {
        Route::get('list', [SaleTransferController::class, 'index'])->name('saleTransfer.index');
        Route::get('create', [SaleTransferController::class, 'create'])->name('saleTransfer.create');
        Route::post('store', [SaleTransferController::class, 'store'])->name('saleTransfer.store');
        Route::get('{id}/edit', [SaleTransferController::class, 'edit'])->name('saleTransfer.edit');
        Route::post('update/{id}', [SaleTransferController::class, 'update'])->name('saleTransfer.update');
        Route::delete('delete/{id}', [SaleTransferController::class, 'destroy'])->name('saleTransfer.destroy');

        Route::post('transfer-data', [SaleTransferController::class, 'transferData'])->name('saleTransfer.data');
        Route::get('product_transfer/{id}', [SaleTransferController::class, 'productTransferData']);
        Route::get('transfer_by_csv', [SaleTransferController::class, 'transferByCsv']);
        Route::post('importtransfer', [SaleTransferController::class, 'importTransfer'])->name('transfer.import');
        Route::get('getproduct/{id}', [SaleTransferController::class, 'getProduct'])->name('transfer.getproduct');
        Route::get('ot_crm_product_search', [SaleTransferController::class, 'ot_crmProductSearch'])->name('product_transfer.search');
        Route::post('deletebyselection', [SaleTransferController::class, 'deleteBySelection']);
    });
});
