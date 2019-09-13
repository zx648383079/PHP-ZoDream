<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '售后记录';
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
                        <a href="" class="active">申请退换货</a>
                        <a href="">退换货记录</a>
                        <a href="">退款明细</a>
                    </div>
               </div>
               <div class="search-box">
                   <input type="text">
                   <button>搜索</button>
               </div>
           </div>
           <table class="applyed-list">
               <thead>
                   <th>返修/退换货编号</th>
                   <th>订单编号</th>
                   <th>商品名称</th>
                   <th>申请时间</th>
                   <th>状态</th>
                   <th>操作</th>
               </thead>
               <tbody>
                   <tr>
                       <td>
                           1231231
                       </td>
                       <td>96635467821</td>
                       <td>
                           <a href="">123123213</a>
                       </td>
                       <td>2019-06-12</td>
                       <td>已完成</td>
                       <td>
                           <a href="">查看</a> | 
                           <a href="">退款明细</a>
                       </td>
                   </tr>
               </tbody>
           </table>
        </div>
    </div>
</div>

