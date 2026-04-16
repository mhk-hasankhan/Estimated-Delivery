{**
 * VendorEstimatedDelivery — Checkout delivery info block
 * Extend: frontend/checkout/confirm.tpl
 *}

{block name="frontend_checkout_confirm_information_addresses" append}
    {if $estimatedDelivery && $estimatedDelivery.from}
        <div class="estimated-delivery-checkout">
            <div class="estimated-delivery-checkout__inner">
                <strong class="estimated-delivery-checkout__label">
                    {$estimatedDelivery.label}
                </strong>
                <span class="estimated-delivery-checkout__date">
                    {if $estimatedDelivery.single_day}
                        {$estimatedDelivery.from}
                    {else}
                        {$estimatedDelivery.from} &ndash; {$estimatedDelivery.to}
                    {/if}
                </span>
            </div>
        </div>
    {/if}
{/block}
