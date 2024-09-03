<div class="tab-content margin-bottom">
    <div class="tab-panel fade in active" id="tabOverview">
            <div class="product-details clearfix">
                <div class="row">
                    <div class="col-md-6">
                        <div class="product-status">
                            <div class="productdetailicon">
                                <img src="https://portal.qwords.com/templates/qwordsv2/img/list manage produk/globe.ng">
                            </div>
                            <div class="product-icon">
                                <h3><strong>{$LANG.navservices}</strong> - {$product}</h3>
                                <h5>{$groupname}</h5>
                                <a class="inline-text" href="http://{$domain}"><h4>{$domain}</h4></a> <a class="inline-text" href="clientarea.php?action=domaindetails&id={$domainId}"><img src="https://portal.qwords.com/templates/qwordsv2/img/list manage produk/settings.png"></a>
                            </div>
                            <div class="product-icon">
                                {if $billingcycle != $LANG.orderpaymenttermonetime && $billingcycle != $LANG.orderfree}
                                    <h5 class="inline-text">{$LANG.recurringamount}:</h5> <h5 class="inline-text">{$recurringamount}</h5>
                                {/if}
                            </div>
                            <div class="product-icon">
                                {if $suspendreason}
                                    <h5>{$LANG.suspendreason}</h5>
                                    {$suspendreason}
                                {/if}
                                {if $customfields && $isShowAuction}
                                <p style="margin-top:1em">
                                    {foreach from=$customfields item=field}
                                        {if $field.id == 1215 }
                                            <a class="btn btn-primary" href="https://portal.qwords.com/index.php?m=auction&domain={$field.value}"> Lelang Domain </a>
                                        {/if}
                                    {/foreach}
                                </p>
                                {/if}
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="product-status">
                            <div class="productdetailicon">
                                <img src="https://portal.qwords.com/templates/qwordsv2/img/list manage produk/calendar.png">
                            </div>
                            <div class="row product-icon">
                                <div class="col-md-6" style="padding: 0px !important;">
                                     <h5>{$LANG.clientareahostingnextduedate}</h5>
                                    {$nextduedate}
                                </div>
                                <div class="col-md-6" style="padding: 0px !important;">
                                     <h5>{$LANG.orderbillingcycle}:</h5>
                                    {$billingcycle}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
            </div>

    </div>
</div>