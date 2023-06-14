<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<footer>
    <div class="footer-top">
        <div class="container">
            <div class="top-item">
                <h4>Customer Service</h4>
                <a>
                    <i class="fa fa-headset"></i>
                    <p>Online Service</p>
                </a>
                <a>
                    <i class="fa fa-comment-dots" aria-hidden="true"></i>
                    <p>Feedback</p>
                </a>
            </div>
            <div class="top-item">
                <h4>About Us</h4>
                <p>
                    
                </p>
            </div>
            <div class="top-item">
                <h4>Contact</h4>
                <img src="/assets/images/wx.jpg" alt="自在test" width="104" height="104">
            </div>
        </div>
    </div>
    <div class="back-to-top">
        Back To Top
    </div>
    <div class="footer-main">
        <div class="container">
            <div class="top-grid">
                <div class="top-item">
                    <i class="fa fa-recycle"></i>
                    <span>Worldwide Delivery</span>
                </div>
                <div class="top-item">
                    <i class="fa fa-rocket"></i>
                    <span>Shipping Rates & Policies</span>
                </div>
                <div class="top-item">
                    <i class="fa fa-shield-alt"></i>
                    <span>Quality Assurance</span>
                </div>
            </div>
            <div class="footer-hr"></div>
            <div class="footer-info">
                <div class="nav-list">
                    <a>About</a>
                    <b>|</b>
                    <a>Help</a>
                    <b>|</b>
                    <a>Service</a>
                    <b>|</b>
                    <a>Shipping</a>
                    <b>|</b>
                    <a>Business</a>
                    <b>|</b>
                    <!-- <a >企业采购</a>
                    <b>|</b> -->
                    <a>Develop</a>
                    <b>|</b>
                    <a>Search</a>
                    <b>|</b>
                    <a>Friend Links</a>
                </div>
                <div class="copyright">
                    Copyright ©<?= request()->host() ?>, All Rights Reserved.
                </div>
            </div>
        </div>
    </div>
</footer>