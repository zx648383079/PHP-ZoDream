<header class="top">
    <a class="back" href="<?=isset($header_back) ? $header_back : 'javascript:history.back(-1);'?>">
        <i class="fa fa-chevron-left" aria-hidden="true"></i>
    </a>
    <span class="title">
        <?=$this->title?>
    </span>
    <?=isset($header_btn) ? $header_btn : null?>
</header>