<?php
defined('APP_DIR') or exit();
$this->extend(array(
		'layout' => array(
				'head'
		)), array(
				function () {?>
<style>
html,
body {
  height: 100%;
  background-color: #333;
}
body {
  color: #000;
  text-align: center;
  text-shadow: 0 1px 3px rgba(f,0,0,.5);
}

/* Extra markup and styles for table-esque vertical and horizontal centering */
.site-wrapper {
  display: table;
  width: 100%;
  height: 100%; /* For at least Firefox */
  min-height: 100%;
}
.site-wrapper-inner {
  display: table-cell;
  vertical-align: top;
}
.cover-container {
  margin-right: auto;
  margin-left: auto;
}

.cover-heading {
	font-size: 100px;
}

/* Padding for spacing */
.inner {
  padding: 30px;
}

/*
 * Cover
 */

.cover {
  padding: 0 20px;
}
.cover .btn-lg {
  padding: 10px 20px;
  font-weight: bold;
}

/*
 * Affix and center
 */

@media (min-width: 768px) {
  /* Start the vertical centering */
  .site-wrapper-inner {
    vertical-align: middle;
  }
}

@media (min-width: 992px) {
  .cover-container {
    width: 700px;
  }
}


</style>
				<?php }
		)
);
?>

<body id="login">
      <div class="site-wrapper">

      <div class="site-wrapper-inner">

        <div class="cover-container">

          <div class="inner cover">
            <p class="cover-heading"><?php $this->ech('status', 404);?></p>
            <p class="lead"><?php $this->ech('error', '页面已丢失！')?></p>
            <p class="lead">
              <a href="<?php $this->url('/');?>" class="btn btn-lg btn-default">返回首页</a>
            </p>
          </div>

        </div>

      </div>

    </div>
<?php 
$this->extend(array(
		'layout' => array(
				'foot'
		))
);
?>