<!-- start Sales -->
@if (hasPermission('sales_menu'))
    <li
        class="sidebar-menu-item {{ set_menu([route('saleProductCategory.index', 'saleProductBrand.index', 'saleProductUnit.index', 'saleProductTax.index', 'saleProductWarehouse.index', 'saleSupplier.index', 'saleProduct.index', 'saleProduct.create', 'saleProduct.printBarcode', 'saleAdjustment.index', 'saleAdjustment.create', 'saleStockCount.index', 'salePurchase.index', 'salePurchase.create', 'salePurchase.by_csv', 'saleExpense.index', 'saleProductExpenseCategory.index', 'saleQuotation.index', 'saleQuotation.create', 'saleTransfer.index', 'saleTransfer.create', 'saleReturn.index', 'purchaseReturn.index')]) }}">
        <a href="javascript:void(0)"
            class="parent-item-content has-arrow  {{ menu_active_by_route(['saleProductCategory.index', 'saleProductBrand.index', 'saleProductUnit.index', 'saleProductTax.index', 'saleProductWarehouse.index', 'saleSupplier.index', 'saleProduct.index', 'saleProduct.create', 'saleProduct.printBarcode', 'saleAdjustment.index', 'saleAdjustment.create', 'saleStockCount.index', 'salePurchase.index', 'salePurchase.create', 'salePurchase.by_csv', 'saleExpense.index', 'saleProductExpenseCategory.index', 'saleQuotation.index', 'saleQuotation.create', 'saleTransfer.index', 'saleTransfer.create', 'saleReturn.index', 'purchaseReturn.index']) }}">
            <i class="las la-chart-pie"></i>
            <span class="on-half-expanded">
                {{ _trans('common.Sales') }}
            </span>
        </a>
        <ul
            class="child-menu-list {{ set_active(['sale/product*', 'sale/expense*', 'sale/purchase*', 'sale/sale*', 'sale/quotation*', 'sale/return-sale*', 'sale/return-purchase*', 'sale/transfer*']) }}">
            @if (hasPermission('sales_products_menu'))
                <!-- start Products -->
                <li
                    class="sidebar-menu-item {{ set_menu([route('saleProductCategory.index', 'saleProductBrand.index', 'saleProductUnit.index', 'saleProductTax.index', 'saleProductWarehouse.index', 'saleSupplier.index', 'saleProduct.index', 'saleProduct.create', 'saleProduct.printBarcode', 'saleAdjustment.index', 'saleAdjustment.create', 'saleStockCount.index')]) }}">
                    <a href="javascript:void(0)"
                        class="parent-item-content has-arrow {{ menu_active_by_route(['saleProductCategory.index', 'saleProductBrand.index', 'saleProductUnit.index', 'saleProductTax.index', 'saleProductWarehouse.index', 'saleSupplier.index', 'saleProduct.index', 'saleProduct.create', 'saleProduct.printBarcode', 'saleAdjustment.index', 'saleAdjustment.create', 'saleStockCount.index']) }}">
                        <span class="on-half-expanded">
                            {{ _trans('common.Products') }}
                        </span>
                    </a>

                    <ul
                        class="child-menu-list {{ set_active(['sale/product/category*', 'sale/product/brand*', 'sale/product/unit*', 'sale/product/tax*', 'sale/product/warehouse*', 'sale/product/supplier*', 'sale/product/product*', 'sale/product/products*', 'sale/product/adjustment*', 'sale/product/stock-count*']) }}">

                        <!-- start Category -->
                        @if (hasPermission('sales_product_category_menu'))
                            <li
                                class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleProductCategory.index']) }}">
                                <a href="{{ route('saleProductCategory.index') }}"
                                    class="  {{ set_active(route('saleProductCategory.index')) }}">
                                    <span>{{ _trans('common.Category') }}</span>
                                </a>
                            </li>
                        @endif
                        <!-- end Category -->
                        <!-- start brand -->
                        @if (hasPermission('sales_product_brand_menu'))
                            <li
                                class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleProductBrand.index']) }}">
                                <a href="{{ route('saleProductBrand.index') }}"
                                    class="  {{ set_active(route('saleProductBrand.index')) }}">
                                    <span>{{ _trans('common.Brand') }}</span>
                                </a>
                            </li>
                        @endif
                        <!-- end brand -->

                        <!-- start Unit -->
                        @if (hasPermission('sales_product_unit_menu'))
                            <li
                                class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleProductUnit.index']) }}">
                                <a href="{{ route('saleProductUnit.index') }}"
                                    class="  {{ set_active(route('saleProductUnit.index')) }}">
                                    <span>{{ _trans('common.Unit') }}</span>
                                </a>
                            </li>
                        @endif
                        <!-- end Unit -->

                        <!-- start Tax -->
                        @if (hasPermission('sales_product_tax_menu'))
                            <li
                                class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleProductTax.index']) }}">
                                <a href="{{ route('saleProductTax.index') }}"
                                    class="  {{ set_active(route('saleProductTax.index')) }}">
                                    <span>{{ _trans('common.Tax') }}</span>
                                </a>
                            </li>
                        @endif
                        <!-- end Tax -->

                        <!-- start Warehouse -->
                        @if (hasPermission('sales_product_warehouse_menu'))
                            <li
                                class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleProductWarehouse.index']) }}">
                                <a href="{{ route('saleProductWarehouse.index') }}"
                                    class="  {{ set_active(route('saleProductWarehouse.index')) }}">
                                    <span>{{ _trans('common.Warehouse') }}</span>
                                </a>
                            </li>
                        @endif
                        <!-- end Warehouse -->

                        <!-- start supplier -->
                        @if (hasPermission('sales_product_supplier_menu'))
                            <li
                                class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleSupplier.index']) }}">
                                <a href="{{ route('saleSupplier.index') }}"
                                    class="  {{ set_active(route('saleSupplier.index')) }}">
                                    <span>{{ _trans('common.Supplier') }}</span>
                                </a>
                            </li>
                        @endif
                        <!-- end supplier -->



                        <!-- start Product Menu -->
                        @if (hasPermission('sales_products_menu'))
                            <li
                                class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleProduct.index']) }}">
                                <a href="{{ route('saleProduct.index') }}"
                                    class="  {{ set_active(route('saleProduct.index')) }}">
                                    <span>{{ _trans('common.Product List') }}</span>
                                </a>
                            </li>
                        @endif
                        <!-- end Product Menu -->


                        <!-- Start: Add Product List Menu -->

                        @if (hasPermission('sales_products_create'))
                            <li
                                class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleProduct.create']) }}">
                                <a href="{{ route('saleProduct.create') }}"
                                    class="  {{ set_active(route('saleProduct.create')) }}">
                                    <span>{{ _trans('common.Add Product') }}</span>
                                </a>
                            </li>
                        @endif
                        <!-- End: Add Product List Menu -->




                        <!-- Start: Print Barcode Menu -->
                        @if (hasPermission('sales_product_barcode_menu'))
                            <li
                                class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleProduct.printBarcode']) }}">
                                <a href="{{ route('saleProduct.printBarcode') }}"
                                    class="  {{ set_active(route('saleProduct.printBarcode')) }}">
                                    <span>{{ _trans('common.Print Barcode') }}</span>
                                </a>
                            </li>
                        @endif
                        <!-- End: Print Barcode Menu -->




                        <!-- Start: Adjustment Menu -->
                        @if (hasPermission('sales_product_stock_adjustment_menu'))
                            <li
                                class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleAdjustment.index']) }}">
                                <a href="{{ route('saleAdjustment.index') }}"
                                    class="  {{ set_active(route('saleAdjustment.index')) }}">
                                    <span>{{ _trans('common.Adjustment List') }}</span>
                                </a>
                            </li>
                        @endif
                        <!-- End: Adjustment List Menu -->




                        <!-- Start: Stock Menu -->
                        @if (hasPermission('sales_product_stock_count_menu'))
                            <li
                                class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleStockCount.index']) }}">
                                <a href="{{ route('saleStockCount.index') }}"
                                    class="  {{ set_active(route('saleStockCount.index')) }}">
                                    <span>{{ _trans('common.Stock Count') }}</span>
                                </a>
                            </li>
                        @endif
                        <!-- End: Stock Menu -->

                    </ul>
                </li>
                <!-- End Product Menu -->
            @endif

            @if (hasPermission('sales_purchase_menu'))
                <!-- Start Purchase -->
                <li
                    class="sidebar-menu-item {{ set_menu([route('salePurchase.index', 'salePurchase.create', 'salePurchase.by_csv')]) }}">
                    <a href="javascript:void(0)"
                        class="parent-item-content has-arrow {{ menu_active_by_route(['salePurchase.index', 'salePurchase.create', 'salePurchase.by_csv']) }}">
                        <span class="on-half-expanded">
                            {{ _trans('common.Purchase') }}
                        </span>
                    </a>


                    <ul class="child-menu-list {{ set_active(['sale/purchase*']) }}">
                            <!--  Start Purchase List menu  -->
                            @if (hasPermission('sales_purchase_menu'))
                                <li
                                    class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['salePurchase.index']) }}">
                                    <a href="{{ route('salePurchase.index') }}"
                                        class="  {{ set_active(route('salePurchase.index')) }}">
                                        <span>{{ _trans('common.Purchase List') }}</span>
                                    </a>
                                </li>
                            @endif
                            <!-- Start Purchase List menu  -->
                            
                            <!-- Start: Purchase Import menu  -->
                            @if (hasPermission('sales_purchase_import'))
                                <li
                                    class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['salePurchase.by_csv']) }}">
                                    <a href="{{ route('salePurchase.by_csv') }}"
                                        class="  {{ set_active(route('salePurchase.by_csv')) }}">
                                        <span>{{ _trans('common.Import Purchase By CSV') }}</span>
                                    </a>
                                </li>
                            @endif
                            <!-- END: Purchase Import menu  -->
                    </ul>
                </li>
                <!-- End Purchase -->
            @endif

            @if (hasPermission('sales_menu'))
                <!-- Start Sale -->
                <li
                    class="sidebar-menu-item {{ set_menu([route('saleSale.index', 'saleSale.create', 'saleSale.pos', 'saleGiftcard', 'saleCoupon', 'saleDelivery')]) }}">
                    <a href="javascript:void(0)"
                        class="parent-item-content has-arrow {{ menu_active_by_route(['saleSale.index', 'saleSale.create', 'saleSale.pos', 'saleGiftcard', 'saleCoupon', 'saleDelivery']) }}">
                        <span class="on-half-expanded">
                            {{ _trans('common.Sale') }}
                        </span>
                    </a>


                    <ul class="child-menu-list {{ set_active(['sale/sale*']) }}">

                        <!-- Start Sale list /saleSale.index -->
                        @if (hasPermission('sales_menu'))
                            <li
                                class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleSale.index']) }}">
                                <a href="{{ route('saleSale.index') }}"
                                    class="  {{ set_active(route('saleSale.index')) }}">
                                    <span>{{ _trans('common.Sale List') }}</span>
                                </a>
                            </li>
                        @endif
                        <!-- End Sale list -->

                        <!-- Start POS list /saleSale.pos -->
                        @if (hasPermission('sales_pos_menu'))
                            <li
                                class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleSale.pos']) }}">
                                <a href="{{ route('saleSale.pos') }}"
                                    class="  {{ set_active(route('saleSale.pos')) }}">
                                    <span>{{ _trans('common.POS') }}</span>
                                </a>
                            </li>
                        @endif
                        <!-- End POS list -->

                        <!-- Start Add Sale list /saleSale.create -->
                        @if (hasPermission('sales_create'))
                            <li
                                class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleSale.create']) }}">
                                <a href="{{ route('saleSale.create') }}"
                                    class="  {{ set_active(route('saleSale.create')) }}">
                                    <span>{{ _trans('common.Add Sale') }}</span>
                                </a>
                            </li>
                        @endif
                        <!-- End Add Sale list -->
                        <!-- Start: Gift Card Menu -->
                        @if (hasPermission('sales_giftcard_menu'))
                            <li
                                class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleGiftcard.index']) }}">
                                <a href="{{ route('saleGiftcard.index') }}"
                                    class="  {{ set_active(route('saleGiftcard.index')) }}">
                                    <span>{{ _trans('common.Gift Card List') }}</span>
                                </a>
                            </li>
                            <!-- END: Gift Card Menu -->

                            <!-- Start: Coupon List Menu -->
                            @if (hasPermission('sales_coupon_menu'))
                                <li
                                    class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleCoupon.index']) }}">
                                    <a href="{{ route('saleCoupon.index') }}"
                                        class="  {{ set_active(route('saleCoupon.index')) }}">
                                        <span>{{ _trans('common.Coupon List') }}</span>
                                    </a>
                                </li>
                            @endif
                            <!-- END: Coupon List Menu -->

                            <!-- Start: Delivery List Menu -->
                            @if (hasPermission('sales_delivery_menu'))
                                <li
                                    class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleDelivery.index']) }}">
                                    <a href="{{ route('saleDelivery.index') }}"
                                        class="  {{ set_active(route('saleDelivery.index')) }}">
                                        <span>{{ _trans('common.Delivery List') }}</span>
                                    </a>
                                </li>
                            @endif
                            <!-- END: Delivery List Menu -->
                        @endif
                    </ul>
                </li>
                <!-- End Sale -->
            @endif


            @if (hasPermission('sales_expense_menu'))
                <!-- Start Expense -->
                <li class="sidebar-menu-item  {{ set_active(['sale/expense*']) }}">
                    <a href="javascript:void(0)"
                        class="parent-item-content has-arrow {{ menu_active_by_route(['saleExpense.index', 'saleProductExpenseCategory.index']) }}">
                        <span class="on-half-expanded">
                            {{ _trans('common.Expense') }}
                        </span>
                    </a>

                    <ul class="child-menu-list {{ set_active(['sale/expense*']) }}">
                        <!-- Start: Expense Category Menu -->
                        @if (hasPermission('sales_expense_category_menu'))
                            <li
                                class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleProductExpenseCategory.index']) }}">
                                <a href="{{ route('saleProductExpenseCategory.index') }}"
                                    class="  {{ set_active(route('saleProductExpenseCategory.index')) }}">
                                    <span>{{ _trans('common.Expense Category') }}</span>
                                </a>
                            </li>
                        @endif
                        <!-- Start: Expense Category Menu -->

                        <!-- Start: Expense Category List Menu -->
                        @if (hasPermission('sales_expense_menu'))
                            <li
                                class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleExpense.index']) }}">
                                <a href="{{ route('saleExpense.index') }}"
                                    class="  {{ set_active(route('saleExpense.index')) }}">
                                    <span>{{ _trans('common.Expense List') }}</span>
                                </a>
                            </li>
                        @endif
                        <!-- Start: Expense Category List Menu -->
                    </ul>
                </li>
                <!-- End Expense -->
            @endif

            @if (hasPermission('sales_quotation_menu'))
                <!-- Start Quotation -->
                <li class="sidebar-menu-item {{ set_active(['sale/quotation*']) }}">
                    <a href="javascript:void(0)"
                        class="parent-item-content has-arrow {{ menu_active_by_route(['saleQuotation.index', 'saleQuotation.create']) }}">
                        <span class="on-half-expanded">
                            {{ _trans('common.Quotation') }}
                        </span>
                    </a>


                    <ul class="child-menu-list {{ set_active(['sale/quotation*']) }}">

                        @if (hasPermission('sales_quotation_menu'))
                            <li
                                class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleQuotation.index']) }}">
                                <a href="{{ route('saleQuotation.index') }}"
                                    class="  {{ set_active(route('saleQuotation.index')) }}">
                                    <span>{{ _trans('common.Quotation List') }}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                <!-- End Quotation -->
            @endif

            @if (hasPermission('sales_transfer_menu'))
                <!-- Start Transfer -->
                <li class="sidebar-menu-item ">
                    <a href="javascript:void(0)"
                        class="parent-item-content has-arrow {{ menu_active_by_route(['saleTransfer.index', 'saleTransfer.create']) }}">
                        <span class="on-half-expanded">
                            {{ _trans('common.Transfer') }}
                        </span>
                    </a>


                    <ul class="child-menu-list {{ set_active(['sale/transfer*']) }}">

                            <!--  Start Tranfer List menu  -->
                            @if (hasPermission('sales_transfer_menu'))
                                <li
                                    class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleTransfer.index']) }}">
                                    <a href="{{ route('saleTransfer.index') }}"
                                        class="  {{ set_active(route('saleTransfer.index')) }}">
                                        <span>{{ _trans('common.Transfer List') }}</span>
                                    </a>
                                </li>
                            @endif
                            <!-- Start Tranfer List menu  -->
                    </ul>
                </li>
                <!-- End Tranfer -->
            @endif


            @if (hasPermission('sales_return_menu'))
                <!-- Start Return -->
                <li class="sidebar-menu-item ">
                    <a href="javascript:void(0)"
                        class="parent-item-content has-arrow {{ menu_active_by_route(['saleReturn.index', 'purchaseReturn.index']) }}">
                        <span class="on-half-expanded">
                            {{ _trans('common.Return') }}
                        </span>
                    </a>

                    <ul class="child-menu-list {{ set_active(['sale/return-sale*', 'sale/return-purchase*']) }}">
                        <!--  Start Return menu  -->
                        @if (hasPermission('sales_return_sale_menu'))
                            <li
                                class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['saleReturn.index']) }}">
                                <a href="{{ route('saleReturn.index') }}"
                                    class="  {{ set_active(route('saleReturn.index')) }}">
                                    <span>{{ _trans('common.Sale List') }}</span>
                                </a>
                            </li>
                        @endif
                        <!-- Start Return menu  -->



                        <!-- Start Return menu  -->
                        @if (hasPermission('sales_return_purchase_menu'))
                            <li
                                class="sidebar-menu-item third-menu-child {{ menu_active_by_route(['purchaseReturn.index']) }}">
                                <a href="{{ route('purchaseReturn.index') }}"
                                    class="  {{ set_active(route('purchaseReturn.index')) }}">
                                    <span>{{ _trans('common.Purchase List') }}</span>
                                </a>
                            </li>
                        @endif
                        <!-- END Return menu  -->
                    </ul>
                </li>
                <!-- End Return -->
            @endif

        </ul>
    </li>
@endif
<!-- End Sale -->
