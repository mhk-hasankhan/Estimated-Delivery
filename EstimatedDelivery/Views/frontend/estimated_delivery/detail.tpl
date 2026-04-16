{**
 * VendorEstimatedDelivery — Product detail delivery badge
 * Extend: frontend/detail/buy.tpl  (add after buy button block)
 *}

{block name="frontend_detail_buy_button" append}
    {if $estimatedDelivery && $estimatedDelivery.from}
        <div class="estimated-delivery-badge">
            <span class="estimated-delivery-badge__icon">
                {* Inline truck SVG — no external deps *}
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="3" width="15" height="13" rx="1"/>
                    <path d="M16 8h4l3 5v4h-7V8z"/>
                    <circle cx="5.5" cy="18.5" r="2.5"/>
                    <circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
            </span>
            <span class="estimated-delivery-badge__label">
                {$estimatedDelivery.label}:
            </span>
            <span class="estimated-delivery-badge__date">
                {if $estimatedDelivery.single_day}
                    {$estimatedDelivery.from}
                {else}
                    {$estimatedDelivery.from} &ndash; {$estimatedDelivery.to}
                {/if}
            </span>
        </div>
    {/if}
{/block}
