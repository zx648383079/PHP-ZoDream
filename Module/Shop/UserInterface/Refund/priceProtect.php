<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '价格保护';
?>
<div class="user-page">
    <div class="container side-box">
        <div>
            <?php $this->extend('layouts/user_menu');?>
        </div>
        <div>
           <div class="order-search">
               <div>
                    <div class="order-tab">
                        <a href="" class="active">价保申请记录</a>
                        <a href="">价格保护申请</a>
                        <a href="">价格保护规则</a>
                    </div>
               </div>
               <div class="search-box">
                   <input type="text">
                   <button>搜索</button>
               </div>
           </div>
           <table class="applyed-list">
                <colgroup>
                    <col width="96">
                    <col width="105">
                    <col width="115">
                    <col width="125">
                    <col width="74">
                    <col width="124">
                    <col width="61">
                    <col width="112">
                    <col width="230">
                </colgroup>
               <thead>
                   <th>商品</th>
                   <th>订单编号</th>
                   <th>申请时间</th>
                   <th>下单时价格</th>
                   <th>数量</th>
                   <th>价保时价格</th>
                   <th>价保数量</th>
                   <th>价保金额</th>
                   <th>申请状态</th>
               </thead>
               <tbody>
                   <tr>
                       <td>
                           <a href="">
                                <img src="https://img10.360buyimg.com/N5/jfs/t1/72278/5/861/77041/5cf098f4E25b74bcb/c964357d495c8dd6.jpg" alt="">
                           </a>
                       </td>
                       <td>96635467821</td>
                       <td>2019-06-12 22:20:03</td>
                       <td>￥199.00</td>
                       <td>x1</td>
                       <td>￥179.00</td>
                       <td>x1</td>
                       <td>￥20.00</td>
                       <td>
                           <p>价保成功</p>
                           <a href="" class="btn">查看详情</a>
                       </td>
                   </tr>
               </tbody>
           </table>
        </div>
    </div>
</div>

