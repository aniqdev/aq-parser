<link rel="stylesheet" href="css/hot-line1.css">
<link rel="stylesheet" href="css/hot-line2.css">
<div class="container order-list" id="js-listdeligator">
    <?php
    $orders = arrayDB(" SELECT *,ebay_order_items.id as gig_item_id,ebay_orders.id as gig_order_id
                        FROM ebay_orders 
                        JOIN ebay_order_items
                        ON ebay_orders.id = ebay_order_items.gig_order_id
                        WHERE PaidTime <> 0 AND ShippedTime = 0 AND OrderStatus = 'Completed' AND `show` = 'yes' 
                        ORDER BY ebay_orders.id DESC
                        LIMIT 50");
    //sa($orders);
    $ebay_games = arrayDB("SELECT item_id,picture_hash FROM ebay_games");
    $pics_hashes = [];
    foreach ($ebay_games as $getve) {
        $pics_hashes[$getve['item_id']] = $getve['picture_hash'];
    }
    $order_id = 0;
    foreach ($orders as $key => $order):
    $address = json_decode($order['ShippingAddress'], true); //sa($order);
    $goods = json_decode($order['goods'], true); //sa($goods);
    $tovarov = 0;
    foreach ($goods as $key => $value) {
        $tovarov += $value['amount'];
    }
    if ($order_id === $order['order_id']) {
        $nomer++;
    }else{
        $nomer = 1;
        $order_id = $order['order_id'];
    }
    ?>
    <div class="cell gd-item flex-block flex-stretch flex-wrap">
        <div class="cell gd-box">
            <div class="cell gd-promo-brdr">
                <div class="gd-img-cell pic-tooltip">
                    <div class="inl-top">
                        <a class="g_statistic" href="http://i.ebayimg.com/images/g/<?= @$pics_hashes[$order['ebay_id']]?>/s-l200.jpg" title="<?= $order['title']?>">
                            <img class="max-120" alt="" src="http://i.ebayimg.com/images/g/<?= @$pics_hashes[$order['ebay_id']]?>/s-l200.jpg">
                        </a>
                        <span></span>
                    </div>
                </div>
                <div class="rel gd-info-cell">
                    <div class="cell text-13 p_b-10 title-fix">
                        <div class="cell3 fr txt-right no-adapt-480">
                            <b><a class="orng g_statistic" href="/" rel="nofollow" title="">Item: (<?= $nomer?>/<?= $tovarov?>)</a></b>
                        </div>
                        <b class="m_r-10"><a class="g_statistic" href="http://www.ebay.de/itm/<?= $order['ebay_id']?>" target="_blank" title=""><?= $order['title']?></a></b>
                    </div>
                    <div class="cell grey-6 gd-tech">
                        <dl class="dl-horizontal">
                          <dt>User ID</dt>
                          <dd title="<?= $address['CountryName']?>"><?= $order['BuyerUserID'].' ('.$order['BuyerFeedbackScore'].', '.$address['Country'].')';?></dd>
                          <dt>Buyer Email</dt>
                          <dd><?= $order['BuyerEmail'];?></dd>
                          <dt>Reg date</dt>
                          <dd><?= $order['BayerRegistrationDate'];?></dd>
                          <dt>Paid time</dt>
                          <dd><?= $order['PaidTime'];?></dd>
                          <dt>Created time</dt>
                          <dd><?= $order['CreatedTime'];?></dd>
                        </dl>
                    </div>
                    <div class="cell gd-item-nav hide">
                        <div class="btn btn-default btn-xs m_r-15 text-11">Button</div>
                        <div class="btn btn-default btn-xs m_r-15 text-11">Button</div>
                        <div class="btn btn-default btn-xs m_r-15 text-11">Button</div>
                        <div class="btn btn-default btn-xs m_r-15 text-11">Button</div>
                        <div class="inl-mid help-box m_l-10">
                            <a href="/" class="inl-mid ico-cart checkout-gd-line-popup-show"></a>
                        </div>
                    </div>
                    <div class="cell grey-6"><i><?= $order['comment'];?></i></div>
                </div>
                <div class="gd-price-cell">
                    <div class="gd-zati4ka cell4">&nbsp;</div>
                    <div class="gd-price-box fl cell-330 cf">
                        <div class="gd-price-sum inl-bot">
                            <div class="text-14 text-13-480 orng"><b><?= $order['price'];?> EUR</b></div>
                            <div class="text-14 text-12-640">
                                <!-- <b>924 - 1&nbsp;640 грн</b> -->
                            </div>
                            <div class="text-11 grey-6 p_t-5 p-0-480">

                            </div>
                        </div>
                        <div class="gd-price-but inl-bot">
                            <div class="cell adapt-480 sales-a">
                                <b><a class="orng" href="#sales" rel="nofollow" title="">Item: (<?= $nomer?>/<?= $tovarov?>)</a></b>
                            </div>
                            <div class="cell but-box m_t-10 js-checkout" title="<?= $order['gig_order_id'];?>-<?= $order['gig_item_id'];?>">Сheckout</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    endforeach;
    ?>
</div>

<!--===========order modal===================-->
<div class="modal fade" id="orderModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title col555">Order</h4>

      </div>
      <div class="order-modal-body" id="order_modal_body">

      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>
<!--===========/order modal===================-->

<script src="https://unpkg.com/react@15/dist/react.min.js"></script>
<script src="https://unpkg.com/react-dom@15/dist/react-dom.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.23/browser.min.js"></script>
<script type="text/babel" src="js/orders-list.js?fmt=<?= filemtime('js/orders-list.js')?>"></script>