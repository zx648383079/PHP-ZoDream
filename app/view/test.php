<?php 
use App\Main;	

?>
<!DOCTYPE html>
<html lang="ch-ZHS">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Application Name</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<?php 
    Main::jcs('fabric.css','fabric.components.css',function(){
      ?>
<style type="text/css">
    body {
      margin: auto 20px;
    }
    h1 {
      padding: 8px;
    }
    #componentWrapper {
      width: 100%;
      height: 100%;
    }

    .sample-wrapper {
      margin: 20px;
    }

    .ms-ContextualMenu.is-open  {
      position: relative;
      margin-bottom: 10px;
    }
    .ms-Dialog {
      position: relative;
      margin-bottom: 10px;
    }
    .ms-NavBar-item .ms-ContextualMenu {
      position: absolute;
    }
    .ms-CommandBar .ms-ContextualMenu {
      position: absolute;
    }
  </style>
      <?php
    });
    ?>
	
  </head>
  <body>
    
    <!-- 导航栏  -->
  <div id="componentWrapper">
    <div class="ms-NavBar">
      <div class="ms-NavBar-openMenu js-openMenu">
        <i class="ms-Icon ms-Icon--menu"></i>
      </div>
      <ul class="ms-NavBar-items">
        <li class="ms-NavBar-item ms-NavBar-item--search ms-u-hiddenSm">
          <div class="ms-TextField">
            <input class="ms-TextField-field">
          </div>
        </li>
        <li class="ms-NavBar-item"><a class="ms-NavBar-link" href="#">Home</a></li>
        <li class="ms-NavBar-item ms-NavBar-item--hasMenu">
          <a class="ms-NavBar-link" href="#">Channels</a>
          <i class="ms-NavBar-chevronDown ms-Icon ms-Icon--chevronDown"></i>
          <ul class="ms-ContextualMenu">
            <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Animals</a></li>
            <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Education</a></li>
            <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Music</a></li>
            <li class="ms-ContextualMenu-item "><a class="ms-ContextualMenu-link" href="#">Sports</a></li>
          </ul>
        </li>
        <li class="ms-NavBar-item"><a class="ms-NavBar-link" href="#">My Videos</a></li>
        <li class="ms-NavBar-item is-disabled"><a class="ms-NavBar-link" href="#">More</a></li>
        <li class="ms-NavBar-item ms-NavBar-item--right"><a class="ms-NavBar-link" href="#"><i class="ms-Icon ms-Icon--upload"></i> Upload</a></li>
      </ul>
    </div>
  </div>
  
  <!-- 排版  -->
	<div class="ms-Grid">
		<div class="ms-Grid-row">
			<div class="ms-Grid-col ms-u-sm6 ms-u-md4 ms-u-lg2">First</div>
			<div class="ms-Grid-col ms-u-sm6 ms-u-md8 ms-u-lg10">Second</div>
		</div>
	</div>
<!-- 按钮  -->
<button class="ms-Button">
  <span class="ms-Button-icon"><i class="ms-Icon ms-Icon--plus"></i></span>
  <span class="ms-Button-label">Create account</span>
  <span class="ms-Button-description">Description of the action this button takes</span>
</button>

<!-- 弹出框  -->
  <div id="componentWrapper">
    <!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Callout ms-Callout--arrowLeft">
  <div class="ms-Callout-main">
    <div class="ms-Callout-header">
      <p class="ms-Callout-title">All of your favorite people</p>
    </div>
    <div class="ms-Callout-inner">
      <div class="ms-Callout-content">
        <p class="ms-Callout-subText">Message body is optional. If help documentation is available, consider adding a link to learn more at the bottom.</p>
      </div>
      <div class="ms-Callout-actions">
        <a href="#" class="ms-Callout-link ms-Link ms-Link--hero">Learn more</a>
      </div>
    </div>    
  </div>
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Callout ms-Callout--close ms-Callout--arrowLeft">
  <div class="ms-Callout-main">
    <div class="ms-Callout-header">
      <p class="ms-Callout-title">All of your favorite people</p>
    </div>
    <button class="ms-Callout-close">
      <i class="ms-Icon ms-Icon--x"></i>
    </button>
    <div class="ms-Callout-inner">
      <div class="ms-Callout-content">
        <p class="ms-Callout-subText">Message body is optional. If help documentation is available, consider adding a link to learn more at the bottom.</p>
      </div>
      <div class="ms-Callout-actions">
        <a href="#" class="ms-Callout-link ms-Link ms-Link--hero">Learn more</a>
      </div>
    </div>
  </div>
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Callout ms-Callout--actionText ms-Callout--arrowLeft">
  <div class="ms-Callout-main">
    <div class="ms-Callout-header">
      <p class="ms-Callout-title">All of your favorite people</p>
    </div>
    <div class="ms-Callout-inner">
      <div class="ms-Callout-content">
        <p class="ms-Callout-subText ms-Callout-subText--s">People automatically puts together all of the people you care most about in one place.</p>
      </div>
      <div class="ms-Callout-actions">
        <button class="ms-Callout-action ms-Button ms-Button--command">
          <span class="ms-Callout-actionText ms-Button-icon"><i class="ms-Icon ms-Icon--check"></i></span>
          <span class="ms-Button-label">Save</span>
        </button>
        <button class="ms-Callout-action ms-Button ms-Button--command">
          <span class="ms-Button-icon"><i class="ms-Icon ms-Icon--x"></i></span>
          <span class="ms-Callout-actionText ms-Button-label">Cancel</span>
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Callout ms-Callout--OOBE ms-Callout--arrowLeft">
  <div class="ms-Callout-main">
    <div class="ms-Callout-header">
      <p class="ms-Callout-title">All of your favorite people</p>
    </div>
    <div class="ms-Callout-inner">
      <div class="ms-Callout-content">
        <p class="ms-Callout-subText">People automatically puts together all of the people you care most about in one place.</p>
      </div>
      <div class="ms-Callout-actions">
        <button class="ms-Callout-button ms-Button ms-Button--primary">
          <span class="ms-Button-label">More</span>
          <span class="ms-Button-description">Description of the action this button takes</span>
        </button>
        <button class="ms-Callout-button ms-Button">
          <span class="ms-Button-label">Got it</span>
          <span class="ms-Button-description">Description of the action this button takes</span>
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Callout ms-Callout--peek ms-Callout--arrowLeft">
  <div class="ms-Callout-main">
    <div class="ms-Callout-header">
      <p class="ms-Callout-title">Uploaded 2 items to <span class="ms-Link">Alton's OneDrive</span></p>
    </div>
    <div class="ms-Callout-inner">
      <div class="ms-Callout-actions">
        <button class="ms-Callout-button ms-Button">
          <span class="ms-Button-label">More</span>
          <span class="ms-Button-description">Description of the action this button takes</span>
        </button>
      </div>
    </div>
  </div>
</div>
  </div>

<!-- 单选多选 -->

  <div id="componentWrapper">
    <!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-ChoiceField">
  <input id="demo-checkbox-selected" class="ms-ChoiceField-input" type="checkbox" checked>
  <label for="demo-checkbox-selected" class="ms-ChoiceField-field"><span class="ms-Label">Selected</span></label>
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-ChoiceField">
  <input id="demo-checkbox-unselected" class="ms-ChoiceField-input" type="checkbox">
  <label for="demo-checkbox-unselected" class="ms-ChoiceField-field"><span class="ms-Label">Selected</span></label>
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-ChoiceFieldGroup">
  <div class="ms-ChoiceFieldGroup-title">
    <label class="ms-Label is-required">Pick one</label>
  </div>
  <div class="ms-ChoiceField">
    <input id="radio1-a" class="ms-ChoiceField-input" type="radio" name="radio1">
    <label for="radio1-a" class="ms-ChoiceField-field"><span class="ms-Label">A</span></label>
  </div>
  <div class="ms-ChoiceField">
    <input id="radio1-b" class="ms-ChoiceField-input" type="radio" name="radio1">
    <label for="radio1-b" class="ms-ChoiceField-field"><span class="ms-Label">B</span></label>
  </div>
  <div class="ms-ChoiceField">
    <input id="radio1-c" class="ms-ChoiceField-input" type="radio" name="radio1">
    <label for="radio1-c" class="ms-ChoiceField-field"><span class="ms-Label">C</span></label>
  </div>
  <div class="ms-ChoiceField">
    <input id="radio1-d" class="ms-ChoiceField-input" type="radio" name="radio1">
    <label for="radio1-d" class="ms-ChoiceField-field"><span class="ms-Label">D</span></label>
  </div>
</div>  

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-ChoiceField">
  <input id="demo-radio-unselected" class="ms-ChoiceField-input" type="radio" checked>
  <label for="demo-radio-unselected" class="ms-ChoiceField-field"><span class="ms-Label">Selected</span></label>
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-ChoiceField">
  <input id="demo-radio-unselected" class="ms-ChoiceField-input" type="radio">
  <label for="demo-radio-unselected" class="ms-ChoiceField-field"><span class="ms-Label">Unselected</span></label>
</div>

  </div>

<!-- commandbar 工具条 -->

<div id="componentWrapper">
    <!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-CommandBar">
  <div class="ms-CommandBarSearch">
    <a class="ms-CommandBarSearch-iconWrapper ms-CommandBarSearch-iconArrowWrapper">
      <i class="ms-Icon ms-Icon--arrowLeft"></i>
    </a>
    <input class="ms-CommandBarSearch-input" type="text" />
    <a class="ms-CommandBarSearch-iconWrapper ms-CommandBarSearch-iconSearchWrapper" href="javascript:;">
      <i class="ms-Icon ms-Icon--search"></i>
    </a>
    <a class="ms-CommandBarSearch-iconWrapper ms-CommandBarSearch-iconClearWrapper ms-font-s" href="javascript:;">
      <i class="ms-Icon ms-Icon--x"></i>
    </a>
  </div>  
  <div class="ms-CommandBar-sideCommands"> 
    <div class="ms-CommandBarItem ms-CommandBarItem--iconOnly">
        <div class="ms-CommandBarItem-linkWrapper">
            <div class="ms-CommandBarItem-link">
                <span class="ms-CommandBarItem-icon ms-Icon ms-Icon--circleFilled"></span>
                <span class="ms-CommandBarItem-commandText ms-font-m ms-font-weight-regular">Stars</span>
                <i class="ms-CommandBarItem-chevronDown ms-Icon ms-Icon--chevronDown"></i>
            </div>
        </div>
    </div>
    <div class="ms-CommandBarItem">
      <div class="ms-CommandBarItem-linkWrapper">
        <div class="ms-CommandBarItem-link">
          <span class="ms-CommandBarItem-icon ms-Icon ms-Icon--circleFilled"></span>
          <span class="ms-CommandBarItem-commandText ms-font-m ms-font-weight-regular">Stars</span>
          <i class="ms-CommandBarItem-chevronDown ms-Icon ms-Icon--chevronDown"></i>
        </div>
      </div>
    </div>
    <div class="ms-CommandBarItem">
      <div class="ms-CommandBarItem-linkWrapper">
        <div class="ms-CommandBarItem-link">
          <span class="ms-CommandBarItem-icon ms-Icon ms-Icon--circleFilled"></span>
          <span class="ms-CommandBarItem-commandText ms-font-m ms-font-weight-regular">Stars</span>
          <i class="ms-CommandBarItem-chevronDown ms-Icon ms-Icon--chevronDown"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="ms-CommandBar-mainArea">
    <div class="ms-CommandBarItem">
      <div class="ms-CommandBarItem-linkWrapper">
        <div class="ms-CommandBarItem-link">
          <span class="ms-CommandBarItem-icon ms-Icon ms-Icon--star"></span>
          <span class="ms-CommandBarItem-commandText ms-font-m ms-font-weight-regular">Stars</span>
          <i class="ms-CommandBarItem-chevronDown ms-Icon ms-Icon--chevronDown"></i>
        </div>
      </div>
    </div>
    <div class="ms-CommandBarItem">
      <div class="ms-CommandBarItem-linkWrapper">
        <div class="ms-CommandBarItem-link">
          <span class="ms-CommandBarItem-icon ms-Icon ms-Icon--onedriveAdd"></span>
          <span class="ms-CommandBarItem-commandText ms-font-m ms-font-weight-regular">Documents</span>
           <i class="ms-CommandBarItem-chevronDown ms-Icon ms-Icon--chevronDown"></i>
        </div>
      </div>
    </div>
    <div class="ms-CommandBarItem">
      <div class="ms-CommandBarItem-linkWrapper">
        <div class="ms-CommandBarItem-link">
          <span class="ms-CommandBarItem-icon ms-Icon ms-Icon--flag"></span>
          <span class="ms-CommandBarItem-commandText ms-font-m ms-font-weight-regular">Flags</span>
          <i class="ms-CommandBarItem-chevronDown ms-Icon ms-Icon--chevronDown"></i>
        </div>
      </div>
    </div>
    <div class="ms-CommandBarItem">
      <div class="ms-CommandBarItem-linkWrapper">
        <div class="ms-CommandBarItem-link">
          <span class="ms-CommandBarItem-icon ms-Icon ms-Icon--smiley"></span>
          <span class="ms-CommandBarItem-commandText ms-font-m ms-font-weight-regular">Smiley</span>
          <i class="ms-CommandBarItem-chevronDown ms-Icon ms-Icon--chevronDown"></i>
        </div>
      </div>
    </div>
    <div class="ms-CommandBarItem">
      <div class="ms-CommandBarItem-linkWrapper">
        <div class="ms-CommandBarItem-link">
          <span class="ms-CommandBarItem-icon ms-Icon ms-Icon--officeStore"></span>
          <span class="ms-CommandBarItem-commandText ms-font-m ms-font-weight-regular">Office Store</span>
          <i class="ms-CommandBarItem-chevronDown ms-Icon ms-Icon--chevronDown"></i>
        </div>
      </div>
    </div>
    <div class="ms-CommandBarItem">
      <div class="ms-CommandBarItem-linkWrapper">
        <div class="ms-CommandBarItem-link">
          <span class="ms-CommandBarItem-icon ms-Icon ms-Icon--lync"></span>
          <span class="ms-CommandBarItem-commandText ms-font-m ms-font-weight-regular">Lync</span>
          <i class="ms-CommandBarItem-chevronDown ms-Icon ms-Icon--chevronDown"></i>
        </div>
      </div>
    </div>
    <!-- Overflow Command -->
    <div class="ms-CommandBarItem ms-CommandBarItem--iconOnly ms-CommandBarItem-overflow">
      <div class="ms-CommandBarItem-linkWrapper">
        <div class="ms-CommandBarItem-link">
          <span class="ms-CommandBarItem-icon ms-Icon ms-Icon--ellipsis"></span>
          <span class="ms-CommandBarItem-commandText ms-font-m ms-font-weight-regular">Ellipsis</span>
          <i class="ms-CommandBarItem-chevronDown ms-Icon ms-Icon--chevronDown"></i>
        </div>
      </div>
      <ul class="ms-CommandBar-overflowMenu ms-ContextualMenu"></ul>
    </div>
    <!-- End Overflow Command -->
  </div>
</div>

  </div>

<!-- 右键菜单 -->
 <div id="componentWrapper">
    <!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<ul class="ms-ContextualMenu is-open">
  <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Animals</a></li>
  <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Books</a></li>
  <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link is-selected" href="#">Education</a></li>
  <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Music</a></li>
  <li class="ms-ContextualMenu-item "><a class="ms-ContextualMenu-link is-disabled" href="#">Sports</a></li>
</ul>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<ul class="ms-ContextualMenu is-open">
  <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Animals</a></li>
  <li class="ms-ContextualMenu-item">
    <a class="ms-ContextualMenu-link ms-ContextualMenu-link--hasMenu" href="#">Books</a>
    <i class="ms-ContextualMenu-subMenuIcon ms-Icon ms-Icon--chevronRight"></i>
    <ul class="ms-ContextualMenu">
      <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Fiction</a></li>
      <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Humor</a></li>
      <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link is-selected" href="#">Magazines</a></li>
      <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Non-fiction</a></li>
      <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Textbooks</a></li>
    </ul>

  </li>
  <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link is-selected" href="#">Education</a></li>
  <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Music</a></li>
  <li class="ms-ContextualMenu-item "><a class="ms-ContextualMenu-link is-disabled" href="#">Sports</a></li>
</ul>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<ul class="ms-ContextualMenu is-open">
  <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Delete</a></li>
  <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Flag</a></li>
  <li class="ms-ContextualMenu-item ms-ContextualMenu-item--divider"></li>
  <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Important</a></li>
  <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Move</a></li>
  <li class="ms-ContextualMenu-item ms-ContextualMenu-item--divider"></li>
  <li class="ms-ContextualMenu-item">
    <a class="ms-ContextualMenu-link ms-ContextualMenu-link--hasMenu" href="#">Move</a>
    <i class="ms-ContextualMenu-subMenuIcon ms-Icon ms-Icon--chevronRight"></i>
    <ul class="ms-ContextualMenu">
      <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Flag</a></li>
      <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Important</a></li>
      <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link is-selected" href="#">Label</a></li>
      <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Snooze</a></li>
    </ul>
  </li>
  <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link is-selected" href="#">Create Rule...</a></li>
  <li class="ms-ContextualMenu-item ms-ContextualMenu-item--divider"></li>
  <li class="ms-ContextualMenu-item "><a class="ms-ContextualMenu-link is-disabled" href="#">Verdana</a></li>
</ul>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<ul class="ms-ContextualMenu ms-ContextualMenu--multiselect is-open">
  <li class="ms-ContextualMenu-item ms-ContextualMenu-item--header">SORT BY</li>
  <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Date</a></li>
  <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Sender</a></li>
  <li class="ms-ContextualMenu-item ms-ContextualMenu-item--divider"></li>
  <li class="ms-ContextualMenu-item ms-ContextualMenu-item--header">ORDER</li>
  <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Newest on top</a></li>
  <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Oldest on top</a></li>
  <li class="ms-ContextualMenu-item ms-ContextualMenu-item--divider"></li>
  <li class="ms-ContextualMenu-item ms-ContextualMenu-item--header">CONVERSATIONS</li>
  <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">On</a></li>
  <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Off</a></li>
</ul>

  </div>
  
  <!-- 日期选择 -->

<div id="componentWrapper">
    <!-- This is a sample Date Picker that works for Gregorian calendars. It uses jQuery and pickadate.js for demonstration. -->

<div class="ms-DatePicker">
  <div class="ms-TextField">
    <label class="ms-Label">Start date</label>
    <i class="ms-DatePicker-event ms-Icon ms-Icon--event"></i>
    <input class="ms-TextField-field" type="text" placeholder="Select a date&hellip;">
  </div>
  <div class="ms-DatePicker-monthComponents">
    <span class="ms-DatePicker-nextMonth js-nextMonth"><i class="ms-Icon ms-Icon--chevronRight"></i></span>
    <span class="ms-DatePicker-prevMonth js-prevMonth"><i class="ms-Icon ms-Icon--chevronLeft"></i></span>
    <div class="ms-DatePicker-headerToggleView js-showMonthPicker"></div>
  </div>
  <span class="ms-DatePicker-goToday js-goToday">Go to today</span>
  <div class="ms-DatePicker-monthPicker">
    <div class="ms-DatePicker-header">
      <div class="ms-DatePicker-yearComponents">
        <span class="ms-DatePicker-nextYear js-nextYear"><i class="ms-Icon ms-Icon--chevronRight"></i></span>
        <span class="ms-DatePicker-prevYear js-prevYear"><i class="ms-Icon ms-Icon--chevronLeft"></i></span>
      </div>
      <div class="ms-DatePicker-currentYear js-showYearPicker"></div>
    </div>
    <div class="ms-DatePicker-optionGrid">
      <span class="ms-DatePicker-monthOption js-changeDate" data-month="0">Jan</span>
      <span class="ms-DatePicker-monthOption js-changeDate" data-month="1">Feb</span>
      <span class="ms-DatePicker-monthOption js-changeDate" data-month="2">Mar</span>
      <span class="ms-DatePicker-monthOption js-changeDate" data-month="3">Apr</span>
      <span class="ms-DatePicker-monthOption js-changeDate" data-month="4">May</span>
      <span class="ms-DatePicker-monthOption js-changeDate" data-month="5">Jun</span>
      <span class="ms-DatePicker-monthOption js-changeDate" data-month="6">Jul</span>
      <span class="ms-DatePicker-monthOption js-changeDate" data-month="7">Aug</span>
      <span class="ms-DatePicker-monthOption js-changeDate" data-month="8">Sep</span>
      <span class="ms-DatePicker-monthOption js-changeDate" data-month="9">Oct</span>
      <span class="ms-DatePicker-monthOption js-changeDate" data-month="10">Nov</span>
      <span class="ms-DatePicker-monthOption js-changeDate" data-month="11">Dec</span>
    </div>
  </div>
  <div class="ms-DatePicker-yearPicker">
    <div class="ms-DatePicker-decadeComponents">
      <span class="ms-DatePicker-nextDecade js-nextDecade"><i class="ms-Icon ms-Icon--chevronRight"></i></span>
      <span class="ms-DatePicker-prevDecade js-prevDecade"><i class="ms-Icon ms-Icon--chevronLeft"></i></span>
    </div>
  </div>
</div>

  </div>
  
  <!-- 对话框 -->
  <h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">Dialog</h1>
  <div id="componentWrapper">
    <!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Dialog">
  <div class="ms-Overlay"></div>
  <div class="ms-Dialog-main">
    <button class="ms-Dialog-button ms-Dialog-button--close">
      <i class="ms-Icon ms-Icon--x"></i>
    </button>
    <div class="ms-Dialog-header">
      <p class="ms-Dialog-title">All emails together</p>
    </div>
    <div class="ms-Dialog-inner">
      <div class="ms-Dialog-content">
        <p class="ms-Dialog-subText">Your Inbox has changed. No longer does it include favorites, it is a singular destination for your emails.</p>
        <div class="ms-ChoiceField">
          <input id="demo-checkbox-unselected1" class="ms-ChoiceField-input" type="checkbox">
          <label for="demo-checkbox-unselected1" class="ms-ChoiceField-field"><span class="ms-Label">Option1</span></label>
        </div>
        <div class="ms-ChoiceField">
          <input id="demo-checkbox-unselected2" class="ms-ChoiceField-input" type="checkbox">
          <label for="demo-checkbox-unselected2" class="ms-ChoiceField-field"><span class="ms-Label">Option2</span></label>
        </div>
      </div>
      <div class="ms-Dialog-actions">
        <div class="ms-Dialog-actionsRight">
          <button class="ms-Dialog-action ms-Button ms-Button--primary">
            <span class="ms-Button-label">Save</span>
          </button>
          <button class="ms-Dialog-action ms-Button">
            <span class="ms-Button-label">Cancel</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Dialog">
  <div class="ms-Overlay"></div>
  <div class="ms-Dialog-main">
    <button class="ms-Dialog-button ms-Dialog-button--close">
      <i class="ms-Icon ms-Icon--x"></i>
    </button>
    <div class="ms-Dialog-header">
      <p class="ms-Dialog-title">All emails together now</p>
    </div>
    <div class="ms-Dialog-inner">
      <div class="ms-Dialog-content">
        <p class="ms-Dialog-subText">Your Inbox has changed. No longer does it include favorites, it is a singular destination for your emails.</p>
      </div>
      <div class="ms-Dialog-actions">
        <div class="ms-Dialog-actionsRight">
          <button class="ms-Dialog-action ms-Button ms-Button--primary">
            <span class="ms-Button-label">More</span>
          </button>
          <button class="ms-Dialog-action ms-Button">
            <span class="ms-Button-label">Got it</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Dialog">
  <div class="ms-Overlay"></div>
  <div class="ms-Dialog-main">
    <button class="ms-Dialog-button ms-Dialog-button--close">
      <i class="ms-Icon ms-Icon--x"></i>
    </button>
    <div class="ms-Dialog-header">
      <p class="ms-Dialog-title">Create account</p>
    </div>
    <div class="ms-Dialog-inner">
      <div class="ms-Dialog-content">
        <button class="ms-Button ms-Button--compound">
          <span class="ms-Button-icon"><i class="ms-Icon ms-Icon--plus"></i></span>
          <span class="ms-Button-label">Create account</span>
          <span class="ms-Button-description">Description of the action this button takes</span>
        </button>
        <button class="ms-Button ms-Button--compound">
          <span class="ms-Button-icon"><i class="ms-Icon ms-Icon--plus"></i></span>
          <span class="ms-Button-label">Create account</span>
          <span class="ms-Button-description">Description of the action this button takes</span>
        </button>
        <button class="ms-Button ms-Button--compound">
          <span class="ms-Button-icon"><i class="ms-Icon ms-Icon--plus"></i></span>
          <span class="ms-Button-label">Create account</span>
          <span class="ms-Button-description">Description of the action this button takes</span>
        </button>
      </div>
    </div>
  </div>
</div>

  </div>
  
  <!-- 下拉 -->
  <h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">Dropdown</h1>
  <div id="componentWrapper">
    <!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Dropdown" tabindex="0">
  <i class="ms-Dropdown-caretDown ms-Icon ms-Icon--caretDown"></i>
  <select class="ms-Dropdown-select">
    <option>Choose a sound&hellip;</option>
    <option>Dog barking</option>
    <option>Wind blowing</option>
    <option>Duck quacking</option>
    <option>Cow mooing</option>
  </select>
</div>

  </div>
  
 <!-- 标签 -->
  <h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">Label</h1>
  <div id="componentWrapper">
    <!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<label class="ms-Label">
  Name
</label>

<!-- 链接 -->
 <h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">Link</h1>
  <div id="componentWrapper">
    <!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<a href="#" class="ms-font-l ms-Link">Link to a webpage</a>

<!-- 菜单 -->
 <h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">List</h1>
  <div id="componentWrapper">
    <div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<ul class="ms-List">
  <div class="ms-ListItem is-unread is-selectable">
    <span class="ms-ListItem-primaryText">Alton Lafferty</span>
    <span class="ms-ListItem-secondaryText">Meeting notes</span>
    <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
    <span class="ms-ListItem-metaText">2:42p</span>
    <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
    <div class="ms-ListItem-actions">
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
    </div>
  </div>
  <div class="ms-ListItem is-unread is-selectable">
    <span class="ms-ListItem-primaryText">Alton Lafferty</span>
    <span class="ms-ListItem-secondaryText">Meeting notes</span>
    <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
    <span class="ms-ListItem-metaText">2:42p</span>
    <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
    <div class="ms-ListItem-actions">
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
    </div>
  </div>
  <div class="ms-ListItem is-unread is-selectable">
    <span class="ms-ListItem-primaryText">Alton Lafferty</span>
    <span class="ms-ListItem-secondaryText">Meeting notes</span>
    <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
    <span class="ms-ListItem-metaText">2:42p</span>
    <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
    <div class="ms-ListItem-actions">
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
    </div>
  </div>
  <div class="ms-ListItem is-selectable">
    <span class="ms-ListItem-primaryText">Alton Lafferty</span>
    <span class="ms-ListItem-secondaryText">Meeting notes</span>
    <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
    <span class="ms-ListItem-metaText">2:42p</span>
    <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
    <div class="ms-ListItem-actions">
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
    </div>
  </div>
  <div class="ms-ListItem is-selected is-selectable">
    <span class="ms-ListItem-primaryText">Alton Lafferty</span>
    <span class="ms-ListItem-secondaryText">Meeting notes</span>
    <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
    <span class="ms-ListItem-metaText">2:42p</span>
    <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
    <div class="ms-ListItem-actions">
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
    </div>
  </div>
  <div class="ms-ListItem is-selectable">
    <span class="ms-ListItem-primaryText">Alton Lafferty</span>
    <span class="ms-ListItem-secondaryText">Meeting notes</span>
    <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
    <span class="ms-ListItem-metaText">2:42p</span>
    <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
    <div class="ms-ListItem-actions">
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
    </div>
  </div>
  <div class="ms-ListItem is-selectable">
    <span class="ms-ListItem-primaryText">Alton Lafferty</span>
    <span class="ms-ListItem-secondaryText">Meeting notes</span>
    <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
    <span class="ms-ListItem-metaText">2:42p</span>
    <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
    <div class="ms-ListItem-actions">
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
    </div>
  </div>
  <div class="ms-ListItem is-selectable">
    <span class="ms-ListItem-primaryText">Alton Lafferty</span>
    <span class="ms-ListItem-secondaryText">Meeting notes</span>
    <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
    <span class="ms-ListItem-metaText">2:42p</span>
    <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
    <div class="ms-ListItem-actions">
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
    </div>
  </div>
</ul>
</div>
<div class="sample-wrapper"><ul class="ms-List ms-List--grid">
  <div class="ms-ListItem is-unread is-selectable">
    <span class="ms-ListItem-primaryText">Alton Lafferty</span>
    <span class="ms-ListItem-secondaryText">Meeting notes</span>
    <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
    <span class="ms-ListItem-metaText">2:42p</span>
    <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
    <div class="ms-ListItem-actions">
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
    </div>
  </div>
  <div class="ms-ListItem is-unread is-selectable">
    <span class="ms-ListItem-primaryText">Alton Lafferty</span>
    <span class="ms-ListItem-secondaryText">Meeting notes</span>
    <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
    <span class="ms-ListItem-metaText">2:42p</span>
    <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
    <div class="ms-ListItem-actions">
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
    </div>
  </div>
  <div class="ms-ListItem is-unread is-selectable">
    <span class="ms-ListItem-primaryText">Alton Lafferty</span>
    <span class="ms-ListItem-secondaryText">Meeting notes</span>
    <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
    <span class="ms-ListItem-metaText">2:42p</span>
    <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
    <div class="ms-ListItem-actions">
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
    </div>
  </div>
  <div class="ms-ListItem is-selectable">
    <span class="ms-ListItem-primaryText">Alton Lafferty</span>
    <span class="ms-ListItem-secondaryText">Meeting notes</span>
    <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
    <span class="ms-ListItem-metaText">2:42p</span>
    <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
    <div class="ms-ListItem-actions">
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
    </div>
  </div>
  <div class="ms-ListItem is-selected is-selectable">
    <span class="ms-ListItem-primaryText">Alton Lafferty</span>
    <span class="ms-ListItem-secondaryText">Meeting notes</span>
    <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
    <span class="ms-ListItem-metaText">2:42p</span>
    <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
    <div class="ms-ListItem-actions">
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
    </div>
  </div>
  <div class="ms-ListItem is-selectable">
    <span class="ms-ListItem-primaryText">Alton Lafferty</span>
    <span class="ms-ListItem-secondaryText">Meeting notes</span>
    <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
    <span class="ms-ListItem-metaText">2:42p</span>
    <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
    <div class="ms-ListItem-actions">
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
    </div>
  </div>
  <div class="ms-ListItem is-selectable">
    <span class="ms-ListItem-primaryText">Alton Lafferty</span>
    <span class="ms-ListItem-secondaryText">Meeting notes</span>
    <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
    <span class="ms-ListItem-metaText">2:42p</span>
    <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
    <div class="ms-ListItem-actions">
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
    </div>
  </div>
  <div class="ms-ListItem is-selectable">
    <span class="ms-ListItem-primaryText">Alton Lafferty</span>
    <span class="ms-ListItem-secondaryText">Meeting notes</span>
    <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
    <span class="ms-ListItem-metaText">2:42p</span>
    <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
    <div class="ms-ListItem-actions">
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
      <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
    </div>
  </div>
</ul>
</div>
  </div>
  
  <!-- 菜单项 -->
 <h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">ListItem</h1>
  <div id="componentWrapper">
    <!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-ListItem">
  <span class="ms-ListItem-primaryText">Alton Lafferty</span>
  <span class="ms-ListItem-secondaryText">Meeting notes</span>
  <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
  <span class="ms-ListItem-metaText">2:42p</span>
  <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
  <div class="ms-ListItem-actions">
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
  </div>
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-ListItem is-selectable">
  <span class="ms-ListItem-primaryText">Alton Lafferty</span>
  <span class="ms-ListItem-secondaryText">Meeting notes</span>
  <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
  <span class="ms-ListItem-metaText">2:42p</span>
  <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
  <div class="ms-ListItem-actions">
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
  </div>
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-ListItem is-selected is-selectable">
  <span class="ms-ListItem-primaryText">Alton Lafferty</span>
  <span class="ms-ListItem-secondaryText">Meeting notes</span>
  <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
  <span class="ms-ListItem-metaText">2:42p</span>
  <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
  <div class="ms-ListItem-actions">
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
  </div>
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-ListItem is-unseen is-selectable">
  <span class="ms-ListItem-primaryText">Alton Lafferty</span>
  <span class="ms-ListItem-secondaryText">Meeting notes</span>
  <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
  <span class="ms-ListItem-metaText">2:42p</span>
  <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
  <div class="ms-ListItem-actions">
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
  </div>
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-ListItem is-unread is-selectable">
  <span class="ms-ListItem-primaryText">Alton Lafferty</span>
  <span class="ms-ListItem-secondaryText">Meeting notes</span>
  <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
  <span class="ms-ListItem-metaText">2:42p</span>
  <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
  <div class="ms-ListItem-actions">
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
  </div>
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-ListItem ms-ListItem--image">
  <div class="ms-ListItem-image" style="background-color: #767676;">&nbsp;</div>
  <span class="ms-ListItem-primaryText">Alton Lafferty</span>
  <span class="ms-ListItem-secondaryText">Meeting notes</span>
  <span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
  <span class="ms-ListItem-metaText">2:42p</span>
  <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
  <div class="ms-ListItem-actions">
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
  </div>
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-ListItem ms-ListItem--document">
  <div class="ms-ListItem-itemIcon ms-ListItem-itemIcon--ppt"></div>
  <span class="ms-ListItem-primaryText">Perdivn Pitch.mp3</span>
  <span class="ms-ListItem-secondaryText">08/11/14 12:32p</span>
</div>

  </div>
  
  <!--  -->
  <h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">OrgChart</h1>
  <div id="componentWrapper">
    <div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-OrgChart">
  <div class="ms-OrgChart-group">
    <ul class="ms-OrgChart-list">
        <li class="ms-OrgChart-listItem">
          <button class="ms-OrgChart-listItemBtn">
            <div class="ms-Persona">
              <div class="ms-Persona-imageArea">
                <div class="ms-Persona-imageCircle">
                  <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                  <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                </div>
                <div class="ms-Persona-presence"></div>
              </div>
              <div class="ms-Persona-details">
                <div class="ms-Persona-primaryText">Russel Miller</div>
                <div class="ms-Persona-secondaryText">Sales</div>
              </div>
            </div>
          </button>
        </li>
        <li class="ms-OrgChart-listItem">
          <button class="ms-OrgChart-listItemBtn">
            <div class="ms-Persona">
              <div class="ms-Persona-imageArea">
                <div class="ms-Persona-imageCircle">
                  <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                  <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                </div>
                <div class="ms-Persona-presence"></div>
              </div>                            
              <div class="ms-Persona-details">
                <div class="ms-Persona-primaryText">Douglas Fielder</div>
                <div class="ms-Persona-secondaryText">Public Relations</div>
              </div>
            </div>
          </button>
        </li>
    </ul>
  </div>
  <div class="ms-OrgChart-group">
    <div class="ms-OrgChart-groupTitle">Manager</div>
    <ul class="ms-OrgChart-list">
        <li class="ms-OrgChart-listItem">
          <button class="ms-OrgChart-listItemBtn">
            <div class="ms-Persona">
              <div class="ms-Persona-imageArea">
                <div class="ms-Persona-imageCircle">
                  <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                  <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                </div>
                <div class="ms-Persona-presence"></div>
              </div>                            
              <div class="ms-Persona-details">
                <div class="ms-Persona-primaryText">Grant Steel</div>
                <div class="ms-Persona-secondaryText">Sales</div>
              </div>
            </div>
          </button>
        </li>
      </ul>
  </div>
  <div class="ms-OrgChart-group">
    <div class="ms-OrgChart-groupTitle">Staff</div>
    <ul class="ms-OrgChart-list">
      <li class="ms-OrgChart-listItem">
        <button class="ms-OrgChart-listItemBtn">
          <div class="ms-Persona">
            <div class="ms-Persona-imageArea">
              <div class="ms-Persona-imageCircle">
                <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
              </div>
              <div class="ms-Persona-presence"></div>
            </div>                            
            <div class="ms-Persona-details">
              <div class="ms-Persona-primaryText">Harvey Wallin</div>
              <div class="ms-Persona-secondaryText">Public Relations</div>
            </div>
          </div>
        </button>
      </li>
      <li class="ms-OrgChart-listItem">
        <button class="ms-OrgChart-listItemBtn">
          <div class="ms-Persona">
            <div class="ms-Persona-imageArea">
              <div class="ms-Persona-imageCircle">
                <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
              </div>
              <div class="ms-Persona-presence"></div>
            </div>                            
            <div class="ms-Persona-details">
              <div class="ms-Persona-primaryText">Marcus Lauer</div>
              <div class="ms-Persona-secondaryText">Technical Support</div>
            </div>
          </div>
        </button>
      </li>
      <li class="ms-OrgChart-listItem">
        <button class="ms-OrgChart-listItemBtn">
          <div class="ms-Persona">
            <div class="ms-Persona-imageArea">
              <div class="ms-Persona-imageCircle">
                <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
              </div>
              <div class="ms-Persona-presence"></div>
            </div>                            
            <div class="ms-Persona-details">
              <div class="ms-Persona-primaryText">Marcel Groce</div>
              <div class="ms-Persona-secondaryText">Delivery</div>
            </div>
          </div>
        </button>
      </li>
      <li class="ms-OrgChart-listItem">
        <button class="ms-OrgChart-listItemBtn">
          <div class="ms-Persona">
            <div class="ms-Persona-imageArea">
              <div class="ms-Persona-imageCircle">
                <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
              </div>
              <div class="ms-Persona-presence"></div>
            </div>                            
            <div class="ms-Persona-details">
              <div class="ms-Persona-primaryText">Jessica Fischer</div>
              <div class="ms-Persona-secondaryText">Marketing</div>
            </div>
          </div>
        </button>
      </li>
    </ul>
  </div>
</div></div>
<div class="sample-wrapper"><div class="ms-OrgChart">
  <div class="ms-OrgChart-group">
    <ul class="ms-OrgChart-list">
        <li class="ms-OrgChart-listItem">
          <button class="ms-OrgChart-listItemBtn">
            <div class="ms-Persona ms-Persona--square">
              <div class="ms-Persona-imageArea">
                <div class="ms-Persona-imageCircle">
                  <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                  <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                </div>
                <div class="ms-Persona-presence"></div>
              </div>
              <div class="ms-Persona-details">
                <div class="ms-Persona-primaryText">Russel Miller</div>
                <div class="ms-Persona-secondaryText">Sales</div>
              </div>
            </div>
          </button>
        </li>
        <li class="ms-OrgChart-listItem">
          <button class="ms-OrgChart-listItemBtn">
            <div class="ms-Persona ms-Persona--square">
              <div class="ms-Persona-imageArea">
                <div class="ms-Persona-imageCircle">
                  <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                  <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                </div>
                <div class="ms-Persona-presence"></div>
              </div>                            
              <div class="ms-Persona-details">
                <div class="ms-Persona-primaryText">Douglas Fielder</div>
                <div class="ms-Persona-secondaryText">Public Relations</div>
              </div>
            </div>
          </button>
        </li>
    </ul>
  </div>
  <div class="ms-OrgChart-group">
    <div class="ms-OrgChart-groupTitle">Manager</div>
    <ul class="ms-OrgChart-list">
        <li class="ms-OrgChart-listItem">
          <button class="ms-OrgChart-listItemBtn">
            <div class="ms-Persona ms-Persona--square">
              <div class="ms-Persona-imageArea">
                <div class="ms-Persona-imageCircle">
                  <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                  <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                </div>
                <div class="ms-Persona-presence"></div>
              </div>                            
              <div class="ms-Persona-details">
                <div class="ms-Persona-primaryText">Grant Steel</div>
                <div class="ms-Persona-secondaryText">Sales</div>
              </div>
            </div>
          </button>
        </li>
      </ul>
  </div>
  <div class="ms-OrgChart-group">
    <div class="ms-OrgChart-groupTitle">Staff</div>
    <ul class="ms-OrgChart-list">
      <li class="ms-OrgChart-listItem">
        <button class="ms-OrgChart-listItemBtn">
          <div class="ms-Persona ms-Persona--square">
            <div class="ms-Persona-imageArea">
              <div class="ms-Persona-imageCircle">
                <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
              </div>
              <div class="ms-Persona-presence"></div>
            </div>                            
            <div class="ms-Persona-details">
              <div class="ms-Persona-primaryText">Harvey Wallin</div>
              <div class="ms-Persona-secondaryText">Public Relations</div>
            </div>
          </div>
        </button>
      </li>
      <li class="ms-OrgChart-listItem">
        <button class="ms-OrgChart-listItemBtn">
          <div class="ms-Persona ms-Persona--square">
            <div class="ms-Persona-imageArea">
              <div class="ms-Persona-imageCircle">
                <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
              </div>
              <div class="ms-Persona-presence"></div>
            </div>                            
            <div class="ms-Persona-details">
              <div class="ms-Persona-primaryText">Marcus Lauer</div>
              <div class="ms-Persona-secondaryText">Technical Support</div>
            </div>
          </div>
        </button>
      </li>
      <li class="ms-OrgChart-listItem">
        <button class="ms-OrgChart-listItemBtn">
          <div class="ms-Persona ms-Persona--square">
            <div class="ms-Persona-imageArea">
              <div class="ms-Persona-imageCircle">
                <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
              </div>
              <div class="ms-Persona-presence"></div>
            </div>                            
            <div class="ms-Persona-details">
              <div class="ms-Persona-primaryText">Marcel Groce</div>
              <div class="ms-Persona-secondaryText">Delivery</div>
            </div>
          </div>
        </button>
      </li>
      <li class="ms-OrgChart-listItem">
        <button class="ms-OrgChart-listItemBtn">
          <div class="ms-Persona ms-Persona--square">
            <div class="ms-Persona-imageArea">
              <div class="ms-Persona-imageCircle">
                <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
              </div>
              <div class="ms-Persona-presence"></div>
            </div>                            
            <div class="ms-Persona-details">
              <div class="ms-Persona-primaryText">Jessica Fischer</div>
              <div class="ms-Persona-secondaryText">Marketing</div>
            </div>
          </div>
        </button>
      </li>
    </ul>
  </div>
</div></div>
  </div>
  
  <!--  -->
    <h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">Overlay</h1>
  <div id="componentWrapper">
    <!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Overlay"></div>
</div>

<!-- 面板 -->
    <h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">Panel</h1>
  <div id="componentWrapper">
    <!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<button class="ms-Button js-togglePanel">
  <span class="ms-Button-label">Open panel</span>
</button>

<div class="ms-Panel">
  <div class="ms-Overlay ms-Overlay--dark js-togglePanel"></div>
  <div class="ms-Panel-main">
    <div class="ms-Panel-commands">
        <div class="ms-CommandBar">
            <div class="ms-CommandBar-sideCommands">
                <div class="ms-CommandBarItem icon-only">
                    <div class="ms-CommandBarItem-linkWrapper">
                        <div class="ms-CommandBarItem-link">
                            <span class="ms-CommandBarItem-icon ms-Icon ms-Icon--circleFilled"></span>
                            <span class="ms-CommandBarItem-commandText ms-font-m ms-font-weight-regular">Stars</span>
                            <i class="ms-CommandBarItem-chevronDown ms-Icon ms-Icon--chevronDown"></i>
                        </div>
                    </div>
                </div>
                <div class="ms-CommandBarItem">
                    <div class="ms-CommandBarItem-linkWrapper">
                        <div class="ms-CommandBarItem-link">
                            <span class="ms-CommandBarItem-icon ms-Icon ms-Icon--circleFilled"></span>
                            <span class="ms-CommandBarItem-commandText ms-font-m ms-font-weight-regular">Stars</span>
                            <i class="ms-CommandBarItem-chevronDown ms-Icon ms-Icon--chevronDown"></i>
                        </div>
                    </div>
                </div>
                <div class="ms-CommandBarItem">
                    <div class="ms-CommandBarItem-linkWrapper">
                        <div class="ms-CommandBarItem-link">
                            <span class="ms-CommandBarItem-icon ms-Icon ms-Icon--circleFilled"></span>
                            <span class="ms-CommandBarItem-commandText ms-font-m ms-font-weight-regular">Stars</span>
                            <i class="ms-CommandBarItem-chevronDown ms-Icon ms-Icon--chevronDown"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ms-CommandBar-mainArea">
                <div class="ms-CommandBarItem">
                    <div class="ms-CommandBarItem-linkWrapper">
                        <div class="ms-CommandBarItem-link">
                            <span class="ms-CommandBarItem-icon ms-Icon ms-Icon--star"></span>
                            <span class="ms-CommandBarItem-commandText ms-font-m ms-font-weight-regular">Stars</span>
                            <i class="ms-CommandBarItem-chevronDown ms-Icon ms-Icon--chevronDown"></i>
                        </div>
                    </div>
                </div>
                <div class="ms-CommandBarItem">
                    <div class="ms-CommandBarItem-linkWrapper">
                        <div class="ms-CommandBarItem-link">
                            <span class="ms-CommandBarItem-icon ms-Icon ms-Icon--onedriveAdd"></span>
                            <span class="ms-CommandBarItem-commandText ms-font-m ms-font-weight-regular">Documents</span>
                            <i class="ms-CommandBarItem-chevronDown ms-Icon ms-Icon--chevronDown"></i>
                        </div>
                    </div>
                </div>
                <div class="ms-CommandBarItem">
                    <div class="ms-CommandBarItem-linkWrapper">
                        <div class="ms-CommandBarItem-link">
                            <span class="ms-CommandBarItem-icon ms-Icon ms-Icon--flag"></span>
                            <span class="ms-CommandBarItem-commandText ms-font-m ms-font-weight-regular">Flags</span>
                            <i class="ms-CommandBarItem-chevronDown ms-Icon ms-Icon--chevronDown"></i>
                        </div>
                    </div>
                </div>
                <div class="ms-CommandBarItem">
                    <div class="ms-CommandBarItem-linkWrapper">
                        <div class="ms-CommandBarItem-link">
                            <span class="ms-CommandBarItem-icon ms-Icon ms-Icon--smiley"></span>
                            <span class="ms-CommandBarItem-commandText ms-font-m ms-font-weight-regular">Smiley</span>
                            <i class="ms-CommandBarItem-chevronDown ms-Icon ms-Icon--chevronDown"></i>
                        </div>
                    </div>
                </div>
                <div class="ms-CommandBarItem">
                    <div class="ms-CommandBarItem-linkWrapper">
                        <div class="ms-CommandBarItem-link">
                            <span class="ms-CommandBarItem-icon ms-Icon ms-Icon--officeStore"></span>
                            <span class="ms-CommandBarItem-commandText ms-font-m ms-font-weight-regular">Office Store</span>
                            <i class="ms-CommandBarItem-chevronDown ms-Icon ms-Icon--chevronDown"></i>
                        </div>
                    </div>
                </div>
                <div class="ms-CommandBarItem">
                    <div class="ms-CommandBarItem-linkWrapper">
                        <div class="ms-CommandBarItem-link">
                            <span class="ms-CommandBarItem-icon ms-Icon ms-Icon--lync"></span>
                            <span class="ms-CommandBarItem-commandText ms-font-m ms-font-weight-regular">Lync</span>
                            <i class="ms-CommandBarItem-chevronDown ms-Icon ms-Icon--chevronDown"></i>
                        </div>
                    </div>
                </div>
                <!-- Overflow Command -->
                <div class="ms-CommandBarItem icon-only ms-CommandBarItem-overflow">
                    <div class="ms-CommandBarItem-linkWrapper">
                        <div class="ms-CommandBarItem-link">
                            <span class="ms-CommandBarItem-icon ms-Icon ms-Icon--ellipsis"></span>
                            <span class="ms-CommandBarItem-commandText ms-font-m ms-font-weight-regular">Ellipsis</span>
                            <i class="ms-CommandBarItem-chevronDown ms-Icon ms-Icon--chevronDown"></i>
                        </div>
                    </div>
                    <ul class="ms-CommandBar-overflowMenu ms-ContextualMenu"></ul>
                </div>
                <!-- End Overflow Command -->
            </div>
        </div>
    </div>
    <div class="ms-Panel-contentInner">
      <span class="ms-Panel-headerText">Panel</span>
      <p class="ms-font-m">Content goes here.</p>
    </div>
  </div>
</div>

  </div>
  
  <!-- 人员选择 -->
    <h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">PeoplePicker</h1>
  <div id="componentWrapper">
    <!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-PeoplePicker">
  <div class="ms-PeoplePicker-searchBox">
    <input class="ms-PeoplePicker-searchField" type="text">
  </div>
  <div class="ms-PeoplePicker-results">
      <div class="ms-PeoplePicker-resultGroups">
          <div class="ms-PeoplePicker-resultGroup">
              <div class="ms-PeoplePicker-resultGroupTitle">Top results</div>
              <ul class="ms-PeoplePicker-resultList">
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn ms-PeoplePicker-resultBtn--compact">
                      <div class="ms-Persona ms-Persona--xs">
                        <div class="ms-Persona-imageArea">
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png">
                          <div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Russel Miller</div>
                          <div class="ms-Persona-secondaryText">Sales</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                    </button>
                  </li>
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn ms-PeoplePicker-resultBtn--compact">
                      <div class="ms-Persona ms-Persona--xs">
                        <div class="ms-Persona-imageArea">
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png">
                          <div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Douglas Fielder</div>
                          <div class="ms-Persona-secondaryText">Public Relations</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                    </button>
                  </li>
              </ul>
          </div>
          <div class="ms-PeoplePicker-resultGroup">
              <div class="ms-PeoplePicker-resultGroupTitle">Other results</div>
              <ul class="ms-PeoplePicker-resultList">
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn ms-PeoplePicker-resultBtn--compact">
                      <div class="ms-Persona ms-Persona--xs">
                        <div class="ms-Persona-imageArea">
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png">
                          <div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Russel Miller</div>
                          <div class="ms-Persona-secondaryText">Sales</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                    </button>
                  </li>
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn ms-PeoplePicker-resultBtn--compact">
                      <div class="ms-Persona ms-Persona--xs">
                        <div class="ms-Persona-imageArea">
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Douglas Fielder</div>
                          <div class="ms-Persona-secondaryText">Public Relations</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                    </button>
                  </li>
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn ms-PeoplePicker-resultBtn--compact">
                      <div class="ms-Persona ms-Persona--xs">
                        <div class="ms-Persona-imageArea">
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Grant Steel</div>
                          <div class="ms-Persona-secondaryText">Technical Support</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultExpand"><i class="ms-Icon ms-Icon--chevronsDown"></i></button>
                    </button>
                      <div class="ms-PeoplePicker-resultAdditionalContent">
                          <ul class="ms-PeoplePicker-resultList">
                              <li class="ms-PeoplePicker-result">
                                <button class="ms-PeoplePicker-resultBtn ms-PeoplePicker-resultBtn--compact">
                                  <div class="ms-Persona ms-Persona--xs">
                                    <div class="ms-Persona-imageArea">
                                      <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png">   
                                      <div class="ms-Persona-presence"></div>
                                    </div>
                                    <div class="ms-Persona-details">
                                      <div class="ms-Persona-primaryText">Bill B. (billb)</div>
                                      <div class="ms-Persona-secondaryText">Public Relations</div>
                                    </div>
                                  </div>
                                  <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                                </button>
                              </li>
                              <li class="ms-PeoplePicker-result">
                                <button class="ms-PeoplePicker-resultBtn ms-PeoplePicker-resultBtn--compact">
                                  <div class="ms-Persona ms-Persona--xs">
                                    <div class="ms-Persona-imageArea">
                                      <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"> 
                                      <div class="ms-Persona-presence"></div>
                                    </div>
                                    <div class="ms-Persona-details">
                                      <div class="ms-Persona-primaryText">Grant Steel (bask)</div>
                                      <div class="ms-Persona-secondaryText">Public Relations</div>
                                    </div>
                                  </div>
                                  <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                                </button>
                              </li>
                          </ul>
                      </div>
                  </li>
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn ms-PeoplePicker-resultBtn--compact">
                      <div class="ms-Persona ms-Persona--xs">
                        <div class="ms-Persona-imageArea">
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Harvey Wallin</div>
                          <div class="ms-Persona-secondaryText">Delivery</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                    </button>
                  </li>
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn ms-PeoplePicker-resultBtn--compact">
                      <div class="ms-Persona ms-Persona--xs">
                        <div class="ms-Persona-imageArea">
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Marcus Lauer</div>
                          <div class="ms-Persona-secondaryText">Marketing</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                    </button>
                  </li>
              </ul>
          </div>
      </div>
      <div class="ms-PeoplePicker-searchMore js-searchMore">
        <button class="ms-PeoplePicker-searchMoreBtn ms-PeoplePicker-searchMoreBtn--compact">
          <div class="ms-PeoplePicker-searchMoreIcon">
            <i class="ms-Icon ms-Icon--search"></i>
          </div>
          <div class="ms-PeoplePicker-searchMoreSecondary">Showing top 5 results</div>
          <div class="ms-PeoplePicker-searchMorePrimary">Search Contacts &amp; Directory</div>
        </button>
      </div>
  </div>
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-PeoplePicker">
  <div class="ms-PeoplePicker-searchBox">
    <input class="ms-PeoplePicker-searchField" type="text">
  </div>
  <div class="ms-PeoplePicker-results">
      <div class="ms-PeoplePicker-resultGroups">
          <div class="ms-PeoplePicker-resultGroup">
              <div class="ms-PeoplePicker-resultGroupTitle">Top results</div>
              <ul class="ms-PeoplePicker-resultList">
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Russel Miller</div>
                          <div class="ms-Persona-secondaryText">Sales</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                    </button>
                  </li>
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Douglas Fielder</div>
                          <div class="ms-Persona-secondaryText">Public Relations</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                    </button>
                  </li>
              </ul>
          </div>
          <div class="ms-PeoplePicker-resultGroup">
              <div class="ms-PeoplePicker-resultGroupTitle">Other results</div>
              <ul class="ms-PeoplePicker-resultList">
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Russel Miller</div>
                          <div class="ms-Persona-secondaryText">Sales</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                    </button>
                  </li>
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Douglas Fielder</div>
                          <div class="ms-Persona-secondaryText">Public Relations</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                    </button>
                  </li>
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Grant Steel</div>
                          <div class="ms-Persona-secondaryText">Technical Support</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultExpand"><i class="ms-Icon ms-Icon--chevronsDown"></i></button>
                    </button>
                      <div class="ms-PeoplePicker-resultAdditionalContent">
                          <ul class="ms-PeoplePicker-resultList">
                              <li class="ms-PeoplePicker-result">
                                <button class="ms-PeoplePicker-resultBtn">
                                  <div class="ms-Persona">
                                    <div class="ms-Persona-imageArea">
                                      <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"> 
                                      <div class="ms-Persona-presence"></div>
                                    </div>
                                    <div class="ms-Persona-details">
                                      <div class="ms-Persona-primaryText">Bill B. (billb)</div>
                                      <div class="ms-Persona-secondaryText">Public Relations</div>
                                    </div>
                                  </div>
                                  <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                                </button>
                              </li>
                              <li class="ms-PeoplePicker-result">
                                <button class="ms-PeoplePicker-resultBtn">
                                  <div class="ms-Persona">
                                    <div class="ms-Persona-imageArea">
                                      <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png">    
                                      <div class="ms-Persona-presence"></div>
                                    </div>
                                    <div class="ms-Persona-details">
                                      <div class="ms-Persona-primaryText">Grant Steel (bask)</div>
                                      <div class="ms-Persona-secondaryText">Public Relations</div>
                                    </div>
                                  </div>
                                  <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                                </button>
                              </li>
                          </ul>
                      </div>
                  </li>
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Harvey Wallin</div>
                          <div class="ms-Persona-secondaryText">Delivery</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                    </button>
                  </li>
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Marcus Lauer</div>
                          <div class="ms-Persona-secondaryText">Marketing</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                    </button>
                  </li>
              </ul>
          </div>
      </div>
      <div class="ms-PeoplePicker-searchMore ms-PeoplePicker-searchMore--disconnected">
        <button class="ms-PeoplePicker-searchMoreBtn">
          <div class="ms-PeoplePicker-searchMoreIcon">
            <i class="ms-Icon ms-Icon--alert"></i>
          </div>
          <div class="ms-PeoplePicker-searchMorePrimary">We are having trouble connecting to the server.<br>Please try again in a few minutes.</div>
        </button>
      </div>
  </div>
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-PeoplePicker">
  <label class="ms-Label">People picker</label>
  <div class="ms-PeoplePicker-searchBox">
    <input class="ms-PeoplePicker-searchField" type="text">
  </div>
  <div class="ms-PeoplePicker-results">
      <div class="ms-PeoplePicker-resultGroups">
          <div class="ms-PeoplePicker-resultGroup">
              <ul class="ms-PeoplePicker-resultList">
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Russel Miller</div>
                          <div class="ms-Persona-secondaryText">Sales</div>
                        </div>
                      </div>
                    </button>
                  </li>
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Douglas Fielder</div>
                          <div class="ms-Persona-secondaryText">Public Relations</div>
                        </div>
                      </div>
                    </button>
                  </li>
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png">
                          <div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Bill B. (billb)</div>
                          <div class="ms-Persona-secondaryText">Public Relations</div>
                        </div>
                      </div>
                    </button>
                  </li>
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png">
                          <div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Grant Steel (bask)</div>
                          <div class="ms-Persona-secondaryText">Public Relations</div>
                        </div>
                      </div>
                    </button>
                  </li>
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Harvey Wallin</div>
                          <div class="ms-Persona-secondaryText">Delivery</div>
                        </div>
                      </div>
                    </button>
                  </li>
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Marcus Lauer</div>
                          <div class="ms-Persona-secondaryText">Marketing</div>
                        </div>
                      </div>
                    </button>
                  </li>
              </ul>
          </div>
      </div>
  </div>
  <div class="ms-PeoplePicker-selected">
    <div class="ms-PeoplePicker-selectedHeader">
      <span class="ms-PeoplePicker-selectedCount"></span> newly added member<span>s</span>
    </div>
    <ul class="ms-PeoplePicker-selectedPeople"></ul>
  </div>
</div>
<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-PeoplePicker">
  <div class="ms-PeoplePicker-searchBox">
    <input class="ms-PeoplePicker-searchField" type="text">
  </div>
  <div class="ms-PeoplePicker-results">
      <div class="ms-PeoplePicker-resultGroups">
          <div class="ms-PeoplePicker-resultGroup">
              <div class="ms-PeoplePicker-resultGroupTitle">Top results</div>
              <ul class="ms-PeoplePicker-resultList">
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Russel Miller</div>
                          <div class="ms-Persona-secondaryText">Sales</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                    </button>
                  </li>
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Douglas Fielder</div>
                          <div class="ms-Persona-secondaryText">Public Relations</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                    </button>
                  </li>
              </ul>
          </div>
          <div class="ms-PeoplePicker-resultGroup">
              <div class="ms-PeoplePicker-resultGroupTitle">Other results</div>
              <ul class="ms-PeoplePicker-resultList">
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Russel Miller</div>
                          <div class="ms-Persona-secondaryText">Sales</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                    </button>
                  </li>
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Douglas Fielder</div>
                          <div class="ms-Persona-secondaryText">Public Relations</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                    </button>
                  </li>
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Grant Steel</div>
                          <div class="ms-Persona-secondaryText">Technical Support</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultExpand"><i class="ms-Icon ms-Icon--chevronsDown"></i></button>
                    </button>
                      <div class="ms-PeoplePicker-resultAdditionalContent">
                          <ul class="ms-PeoplePicker-resultList">
                              <li class="ms-PeoplePicker-result">
                                <button class="ms-PeoplePicker-resultBtn">
                                  <div class="ms-Persona">
                                    <div class="ms-Persona-imageArea">
                                      <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                                      <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png">                                      <div class="ms-Persona-presence"></div>
                                    </div>
                                    <div class="ms-Persona-details">
                                      <div class="ms-Persona-primaryText">Bill B. (billb)</div>
                                      <div class="ms-Persona-secondaryText">Public Relations</div>
                                    </div>
                                  </div>
                                  <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                                </button>
                              </li>
                              <li class="ms-PeoplePicker-result">
                                <button class="ms-PeoplePicker-resultBtn">
                                  <div class="ms-Persona">
                                    <div class="ms-Persona-imageArea">
                                      <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                                      <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png">                                      <div class="ms-Persona-presence"></div>
                                    </div>
                                    <div class="ms-Persona-details">
                                      <div class="ms-Persona-primaryText">Grant Steel (bask)</div>
                                      <div class="ms-Persona-secondaryText">Public Relations</div>
                                    </div>
                                  </div>
                                  <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                                </button>
                              </li>
                          </ul>
                      </div>
                  </li>
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Harvey Wallin</div>
                          <div class="ms-Persona-secondaryText">Delivery</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                    </button>
                  </li>
                  <li class="ms-PeoplePicker-result">
                    <button class="ms-PeoplePicker-resultBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                          <img class="ms-Persona-image" src="../src/components/persona/Persona.Person2.png"><div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Marcus Lauer</div>
                          <div class="ms-Persona-secondaryText">Marketing</div>
                        </div>
                      </div>
                      <button class="ms-PeoplePicker-resultAction js-resultRemove"><i class="ms-Icon ms-Icon--x"></i></button>
                    </button>
                  </li>
              </ul>
          </div>
      </div>
      <div class="ms-PeoplePicker-searchMore js-searchMore">
        <button class="ms-PeoplePicker-searchMoreBtn">
          <div class="ms-PeoplePicker-searchMoreIcon">
            <i class="ms-Icon ms-Icon--search"></i>
          </div>
          <div class="ms-PeoplePicker-searchMoreSecondary">Showing top 5 results</div>
          <div class="ms-PeoplePicker-searchMorePrimary">Search Contacts &amp; Directory</div>
        </button>
      </div>
  </div>
</div>

  </div>
  
  <!--  -->
   <h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">Persona</h1>
  <div id="componentWrapper">
    <div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona--tiny">
  <div class="ms-Persona-imageArea">
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
  </div>
</div>

</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona--xs">
  <div class="ms-Persona-imageArea">
    <div class="ms-Persona-imageCircle">
      <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
      <img class="ms-Persona-image" src="Persona.Person2.png">
    </div>
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
  </div>
</div>
</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona--sm">
  <div class="ms-Persona-imageArea">
    <div class="ms-Persona-imageCircle">
      <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
      <img class="ms-Persona-image" src="Persona.Person2.png">
    </div>
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
    <div class="ms-Persona-secondaryText">Accountant</div>
  </div>
</div>

</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona">
  <div class="ms-Persona-imageArea">
    <div class="ms-Persona-imageCircle">
      <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
      <img class="ms-Persona-image" src="Persona.Person2.png">
    </div>
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
    <div class="ms-Persona-secondaryText">Accountant</div>
  </div>
</div>
</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona--lg">
  <div class="ms-Persona-imageArea">
    <div class="ms-Persona-imageCircle">
      <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
      <img class="ms-Persona-image" src="Persona.Person2.png">
    </div>
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
    <div class="ms-Persona-secondaryText">Accountant</div>
    <div class="ms-Persona-tertiaryText">In a meeting</div>
  </div>
</div>

</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona--xl">
  <div class="ms-Persona-imageArea">
    <div class="ms-Persona-imageCircle">
      <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
      <img class="ms-Persona-image" src="Persona.Person2.png">
    </div>
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
    <div class="ms-Persona-secondaryText">Accountant</div>
    <div class="ms-Persona-tertiaryText">In a meeting</div>
    <div class="ms-Persona-optionalText">Available at 4:00pm</div>
  </div>
</div>
</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona ms-Persona--square ms-Persona--tiny">
  <div class="ms-Persona-imageArea">
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
  </div>
</div>
</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona ms-Persona--square ms-Persona--xs">
  <div class="ms-Persona-imageArea">
    <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
    <img class="ms-Persona-image" src="Persona.Person2.png">
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
  </div>
</div>
</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona ms-Persona--square ms-Persona--sm">
  <div class="ms-Persona-imageArea">
    <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
    <img class="ms-Persona-image" src="Persona.Person2.png">
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
    <div class="ms-Persona-secondaryText">Accountant</div>
  </div>
</div>
</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona--square">
  <div class="ms-Persona-imageArea">
    <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
    <img class="ms-Persona-image" src="Persona.Person2.png">
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
    <div class="ms-Persona-secondaryText">Accountant</div>
  </div>
</div>
</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona ms-Persona--square ms-Persona--lg">
  <div class="ms-Persona-imageArea">
    <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
    <img class="ms-Persona-image" src="Persona.Person2.png">
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
    <div class="ms-Persona-secondaryText">Accountant</div>
    <div class="ms-Persona-tertiaryText">In a meeting</div>
  </div>
</div>
</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona--square ms-Persona--xl">
  <div class="ms-Persona-imageArea">
    <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
    <img class="ms-Persona-image" src="Persona.Person2.png">
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
    <div class="ms-Persona-secondaryText">Accountant</div>
    <div class="ms-Persona-tertiaryText">In a meeting</div>
    <div class="ms-Persona-optionalText">Available at 4:00pm</div>
  </div>
</div>
</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona--available">
  <div class="ms-Persona-imageArea">
    <div class="ms-Persona-imageCircle">
      <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
      <img class="ms-Persona-image" src="Persona.Person2.png">
    </div>
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
    <div class="ms-Persona-secondaryText">Accountant</div>
  </div>
</div>
</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona--away">
  <div class="ms-Persona-imageArea">
    <div class="ms-Persona-imageCircle">
      <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
      <img class="ms-Persona-image" src="Persona.Person2.png">
    </div>
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
    <div class="ms-Persona-secondaryText">Accountant</div>
  </div>
</div>
</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona--blocked">
  <div class="ms-Persona-imageArea">
    <div class="ms-Persona-imageCircle">
      <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
      <img class="ms-Persona-image" src="Persona.Person2.png">
    </div>
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
    <div class="ms-Persona-secondaryText">Accountant</div>
  </div>
</div>
</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona--busy">
  <div class="ms-Persona-imageArea">
    <div class="ms-Persona-imageCircle">
      <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
      <img class="ms-Persona-image" src="Persona.Person2.png">
    </div>
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
    <div class="ms-Persona-secondaryText">Accountant</div>
  </div>
</div>
</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona--dnd">
  <div class="ms-Persona-imageArea">
    <div class="ms-Persona-imageCircle">
      <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
      <img class="ms-Persona-image" src="Persona.Person2.png">
    </div>
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
    <div class="ms-Persona-secondaryText">Accountant</div>
  </div>
</div>
</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona--offline">
  <div class="ms-Persona-imageArea">
    <div class="ms-Persona-imageCircle">
      <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
      <img class="ms-Persona-image" src="Persona.Person2.png">
    </div>
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
    <div class="ms-Persona-secondaryText">Accountant</div>
  </div>
</div>
</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona--square ms-Persona--available">
  <div class="ms-Persona-imageArea">
    <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
    <img class="ms-Persona-image" src="Persona.Person2.png">
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
    <div class="ms-Persona-secondaryText">Accountant</div>
  </div>
</div>
</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona--square ms-Persona--away">
  <div class="ms-Persona-imageArea">
    <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
    <img class="ms-Persona-image" src="Persona.Person2.png">
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
    <div class="ms-Persona-secondaryText">Accountant</div>
  </div>
</div>
</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona--square ms-Persona--blocked">
  <div class="ms-Persona-imageArea">
    <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
    <img class="ms-Persona-image" src="Persona.Person2.png">
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
    <div class="ms-Persona-secondaryText">Accountant</div>
  </div>
</div>
</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona--square ms-Persona--busy">
  <div class="ms-Persona-imageArea">
    <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
    <img class="ms-Persona-image" src="Persona.Person2.png">
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
    <div class="ms-Persona-secondaryText">Accountant</div>
  </div>
</div>
</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona--square ms-Persona--dnd">
  <div class="ms-Persona-imageArea">
    <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
    <img class="ms-Persona-image" src="Persona.Person2.png">
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
    <div class="ms-Persona-secondaryText">Accountant</div>
  </div>
</div>
</div>
<div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Persona ms-Persona--square ms-Persona--offline">
  <div class="ms-Persona-imageArea">
    <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
    <img class="ms-Persona-image" src="Persona.Person2.png">
    <div class="ms-Persona-presence"></div>
  </div>
  <div class="ms-Persona-details">
    <div class="ms-Persona-primaryText">Alton Lafferty</div>
    <div class="ms-Persona-secondaryText">Accountant</div>
  </div>
</div>
</div>
  </div>
  
  <!-- 信息卡 -->
  <h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">PersonaCard</h1>
  <div id="componentWrapper">
    <div class="sample-wrapper"><!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-PersonaCard">
  <div class="ms-PersonaCard-persona">
    <div class="ms-Persona">
      <div class="ms-Persona-imageArea">
        <div class="ms-Persona-imageCircle">
          <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
          <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
        </div>
        <div class="ms-Persona-presence"></div>
      </div>
      <div class="ms-Persona-details">
        <!-- Use title attribute to display full text in the event of primaryText truncation -->
        <div class="ms-Persona-primaryText" title="Alton Lafferty">Alton Lafferty</div>
        <div class="ms-Persona-secondaryText">Interior Designer, Contoso</div>
        <div class="ms-Persona-tertiaryText">Office: 7/1234</div>
        <div class="ms-Persona-optionalText">Available - Video capable</div>
      </div>
    </div>
  </div>
  <ul class="ms-PersonaCard-actions">
    <li id="chat" class="ms-PersonaCard-action is-active"><i class="ms-Icon ms-Icon--chat"></i></li>
    <li id="phone" class="ms-PersonaCard-action"><i class="ms-Icon ms-Icon--phone"></i></li>
    <li id="video" class="ms-PersonaCard-action"><i class="ms-Icon ms-Icon--video"></i></li>
    <li id="mail" class="ms-PersonaCard-action"><i class="ms-Icon ms-Icon--mail"></i></li>
    <li class="ms-PersonaCard-overflow" alt="View profile in Delve" title="View profile in Delve">View profile</li>
    <li id="org" class="ms-PersonaCard-action ms-PersonaCard-orgChart"><i class="ms-Icon ms-Icon--org"></i></li>
  </ul>
  <div class="ms-PersonaCard-actionDetailBox">
    <ul id="detailList" class="ms-PersonaCard-detailChat">
      <li id="chat" class="ms-PersonaCard-actionDetails detail-1">
        <div class="ms-PersonaCard-detailLine"><span class="ms-PersonaCard-detailLabel">Lync:</span> <a class="ms-Link" href="#">Start Lync call</a></div>
      </li>
      <li id="phone" class="ms-PersonaCard-actionDetails detail-2">
        <div class="ms-PersonaCard-detailLine"><span class="ms-PersonaCard-detailLabel">Personal:</span> 555.206.2443</div>
        <div class="ms-PersonaCard-detailLine"><span class="ms-PersonaCard-detailLabel">Work:</span> 555.929.8240</div>
      </li>
      <li id="video" class="ms-PersonaCard-actionDetails detail-3">
        <div class="ms-PersonaCard-detailLine"><span class="ms-PersonaCard-detailLabel">Skype:</span> <a class="ms-Link" href="#">Start Skype call</a></div>
      </li>
      <li id="mail" class="ms-PersonaCard-actionDetails detail-4">
        <div class="ms-PersonaCard-detailLine"><span class="ms-PersonaCard-detailLabel">Personal:</span> <a class="ms-Link" href="mailto:alton.lafferty@outlook.com">alton.lafferty@outlook.com</a></div>
        <div class="ms-PersonaCard-detailLine"><span class="ms-PersonaCard-detailLabel">Work:</span> <a class="ms-Link" href="mailto:alton.lafferty@outlook.com">altonlafferty@contoso.com</a>
      </li>
      <!-- org chart -->
      <li id="org" class="ms-PersonaCard-actionDetails detail-5">
        <div class="ms-OrgChart">
          <div class="ms-OrgChart-group">
              <ul class="ms-OrgChart-list">
                  <li class="ms-OrgChart-listItem">
                    <button class="ms-OrgChart-listItemBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <div class="ms-Persona-imageCircle">
                            <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                            <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                          </div>
                          <div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Russel Miller</div>
                          <div class="ms-Persona-secondaryText">Sales</div>
                        </div>
                      </div>
                    </button>
                  </li>
                  <li class="ms-OrgChart-listItem">
                    <button class="ms-OrgChart-listItemBtn">
                      <div class="ms-Persona">
                        <div class="ms-Persona-imageArea">
                          <div class="ms-Persona-imageCircle">
                            <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                            <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                          </div>
                          <div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Douglas Fielder</div>
                          <div class="ms-Persona-secondaryText">Public Relations</div>
                        </div>
                      </div>
                    </button>
                  </li>
              </ul>
          </div>
          <div class="ms-OrgChart-group">
            <div class="ms-OrgChart-groupTitle">Manager</div>
            <ul class="ms-OrgChart-list">
                <li class="ms-OrgChart-listItem">
                  <button class="ms-OrgChart-listItemBtn">
                    <div class="ms-Persona">
                      <div class="ms-Persona-imageArea">
                        <div class="ms-Persona-imageCircle">
                          <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                          <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                        </div>
                        <div class="ms-Persona-presence"></div>
                      </div>
                      <div class="ms-Persona-details">
                        <div class="ms-Persona-primaryText">Grant Steel</div>
                        <div class="ms-Persona-secondaryText">Sales</div>
                      </div>
                    </div>
                  </button>
                </li>
              </ul>
          </div>
          <div class="ms-OrgChart-group">
            <div class="ms-OrgChart-groupTitle">Staff</div>
            <ul class="ms-OrgChart-list">
              <li class="ms-OrgChart-listItem">
                <button class="ms-OrgChart-listItemBtn">
                  <div class="ms-Persona">
                    <div class="ms-Persona-imageArea">
                      <div class="ms-Persona-imageCircle">
                        <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                        <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                      </div>
                      <div class="ms-Persona-presence"></div>
                    </div>
                    <div class="ms-Persona-details">
                      <div class="ms-Persona-primaryText">Harvey Wallin</div>
                      <div class="ms-Persona-secondaryText">Public Relations</div>
                    </div>
                  </div>
                </button>
              </li>
              <li class="ms-OrgChart-listItem">
                <button class="ms-OrgChart-listItemBtn">
                  <div class="ms-Persona">
                    <div class="ms-Persona-imageArea">
                      <div class="ms-Persona-imageCircle">
                        <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                        <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                      </div>
                      <div class="ms-Persona-presence"></div>
                    </div>
                    <div class="ms-Persona-details">
                      <div class="ms-Persona-primaryText">Marcus Lauer</div>
                      <div class="ms-Persona-secondaryText">Technical Support</div>
                    </div>
                  </div>
                </button>
              </li>
              <li class="ms-OrgChart-listItem">
                <button class="ms-OrgChart-listItemBtn">
                  <div class="ms-Persona">
                    <div class="ms-Persona-imageArea">
                      <div class="ms-Persona-imageCircle">
                        <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                        <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                      </div>
                      <div class="ms-Persona-presence"></div>
                    </div>
                    <div class="ms-Persona-details">
                      <div class="ms-Persona-primaryText">Marcel Groce</div>
                      <div class="ms-Persona-secondaryText">Delivery</div>
                    </div>
                  </div>
                </button>
              </li>
              <li class="ms-OrgChart-listItem">
                <button class="ms-OrgChart-listItemBtn">
                  <div class="ms-Persona">
                    <div class="ms-Persona-imageArea">
                      <div class="ms-Persona-imageCircle">
                        <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                        <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                      </div>
                      <div class="ms-Persona-presence"></div>
                    </div>
                    <div class="ms-Persona-details">
                      <div class="ms-Persona-primaryText">Jessica Fischer</div>
                      <div class="ms-Persona-secondaryText">Marketing</div>
                    </div>
                  </div>
                </button>
              </li>
            </ul>
          </div>
        </div>
      </li>
      <!-- /org chart -->
    </ul>
  </div>
  </div>
</div>
</div>
<div class="sample-wrapper"><div class="ms-PersonaCard ms-PersonaCard--square">
  <div class="ms-PersonaCard-persona">
    <div class="ms-Persona ms-Persona--square ms-Persona--xl">
      <div class="ms-Persona-imageArea">
        <div class="ms-Persona-imageCircle">
          <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
          <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
        </div>
        <div class="ms-Persona-presence"></div>          
      </div>
      <div class="ms-Persona-details">
        <div class="ms-Persona-primaryText">Alton Lafferty</div>
        <div class="ms-Persona-secondaryText">Interior Designer, Contoso</div>
        <div class="ms-Persona-tertiaryText">Office: 7/1234</div>
        <div class="ms-Persona-optionalText">Available - Video capable</div>
      </div>
    </div>
  </div>
  <ul class="ms-PersonaCard-actions">
    <li id="chat" class="ms-PersonaCard-action is-active"><i class="ms-Icon ms-Icon--chat"></i></li>
    <li id="phone" class="ms-PersonaCard-action"><i class="ms-Icon ms-Icon--phone"></i></li>
    <li id="video" class="ms-PersonaCard-action"><i class="ms-Icon ms-Icon--video"></i></li>
    <li id="mail" class="ms-PersonaCard-action"><i class="ms-Icon ms-Icon--mail"></i></li>
    <li class="ms-PersonaCard-overflow" alt="View profile in Delve" title="View profile in Delve">View profile</li>
    <li id="org" class="ms-PersonaCard-action ms-PersonaCard-orgChart"><i class="ms-Icon ms-Icon--org"></i></li>
  </ul>
  <div class="ms-PersonaCard-actionDetailBox">
    <ul id="detailList" class="ms-PersonaCard-detailChat">
      <li id="chat" class="ms-PersonaCard-actionDetails detail-1">
        <div class="ms-PersonaCard-detailLine"><span class="ms-PersonaCard-detailLabel">Lync:</span> <a class="ms-Link" href="#">Start Lync call</a></div>
      </li>
      <li id="phone" class="ms-PersonaCard-actionDetails detail-2">
        <div class="ms-PersonaCard-detailLine"><span class="ms-PersonaCard-detailLabel">Personal:</span> 555.206.2443</div>
        <div class="ms-PersonaCard-detailLine"><span class="ms-PersonaCard-detailLabel">Work:</span> 555.929.8240</div>
      </li>
      <li id="video" class="ms-PersonaCard-actionDetails detail-3">
        <div class="ms-PersonaCard-detailLine"><span class="ms-PersonaCard-detailLabel">Skype:</span> <a class="ms-Link" href="#">Start Skype call</a></div>
      </li>
      <li id="mail" class="ms-PersonaCard-actionDetails detail-4">
        <div class="ms-PersonaCard-detailLine"><span class="ms-PersonaCard-detailLabel">Personal:</span> <a class="ms-Link" href="mailto:alton.lafferty@outlook.com">alton.lafferty@outlook.com</a></div>
        <div class="ms-PersonaCard-detailLine"><span class="ms-PersonaCard-detailLabel">Work:</span> <a class="ms-Link" href="mailto:alton.lafferty@outlook.com">altonlafferty@contoso.com</a>
      </li>
      <!-- org chart -->
      <li id="org" class="ms-PersonaCard-actionDetails detail-5">
        <div class="ms-OrgChart">
          <div class="ms-OrgChart-group">
              <ul class="ms-OrgChart-list">
                  <li class="ms-OrgChart-listItem">
                    <button class="ms-OrgChart-listItemBtn">
                      <div class="ms-Persona ms-Persona--square">
                        <div class="ms-Persona-imageArea">
                          <div class="ms-Persona-imageCircle">
                            <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                            <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                          </div>
                          <div class="ms-Persona-presence"></div>
                        </div>
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Russel Miller</div>
                          <div class="ms-Persona-secondaryText">Sales</div>
                        </div>
                      </div>
                    </button>
                  </li>
                  <li class="ms-OrgChart-listItem">
                    <button class="ms-OrgChart-listItemBtn">
                      <div class="ms-Persona ms-Persona--square">
                        <div class="ms-Persona-imageArea">
                          <div class="ms-Persona-imageCircle">
                            <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                            <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                          </div>
                          <div class="ms-Persona-presence"></div>
                        </div>                            
                        <div class="ms-Persona-details">
                          <div class="ms-Persona-primaryText">Douglas Fielder</div>
                          <div class="ms-Persona-secondaryText">Public Relations</div>
                        </div>
                      </div>
                    </button>
                  </li>
              </ul>
          </div>
          <div class="ms-OrgChart-group">
            <div class="ms-OrgChart-groupTitle">Manager</div>
            <ul class="ms-OrgChart-list">
                <li class="ms-OrgChart-listItem">
                  <button class="ms-OrgChart-listItemBtn">
                    <div class="ms-Persona ms-Persona--square">
                      <div class="ms-Persona-imageArea">
                        <div class="ms-Persona-imageCircle">
                          <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                          <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                        </div>
                        <div class="ms-Persona-presence"></div>
                      </div>                            
                      <div class="ms-Persona-details">
                        <div class="ms-Persona-primaryText">Grant Steel</div>
                        <div class="ms-Persona-secondaryText">Sales</div>
                      </div>
                    </div>
                  </button>
                </li>
              </ul>
          </div>
          <div class="ms-OrgChart-group">
            <div class="ms-OrgChart-groupTitle">Staff</div>
            <ul class="ms-OrgChart-list">
              <li class="ms-OrgChart-listItem">
                <button class="ms-OrgChart-listItemBtn">
                  <div class="ms-Persona ms-Persona--square">
                    <div class="ms-Persona-imageArea">
                      <div class="ms-Persona-imageCircle">
                        <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                        <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                      </div>
                      <div class="ms-Persona-presence"></div>
                    </div>                            
                    <div class="ms-Persona-details">
                      <div class="ms-Persona-primaryText">Harvey Wallin</div>
                      <div class="ms-Persona-secondaryText">Public Relations</div>
                    </div>
                  </div>
                </button>
              </li>
              <li class="ms-OrgChart-listItem">
                <button class="ms-OrgChart-listItemBtn">
                  <div class="ms-Persona ms-Persona--square">
                    <div class="ms-Persona-imageArea">
                      <div class="ms-Persona-imageCircle">
                        <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                        <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                      </div>
                      <div class="ms-Persona-presence"></div>
                    </div>                            
                    <div class="ms-Persona-details">
                      <div class="ms-Persona-primaryText">Marcus Lauer</div>
                      <div class="ms-Persona-secondaryText">Technical Support</div>
                    </div>
                  </div>
                </button>
              </li>
              <li class="ms-OrgChart-listItem">
                <button class="ms-OrgChart-listItemBtn">
                  <div class="ms-Persona ms-Persona--square">
                    <div class="ms-Persona-imageArea">
                      <div class="ms-Persona-imageCircle">
                        <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                        <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                      </div>
                      <div class="ms-Persona-presence"></div>
                    </div>                            
                    <div class="ms-Persona-details">
                      <div class="ms-Persona-primaryText">Marcel Groce</div>
                      <div class="ms-Persona-secondaryText">Delivery</div>
                    </div>
                  </div>
                </button>
              </li>
              <li class="ms-OrgChart-listItem">
                <button class="ms-OrgChart-listItemBtn">
                  <div class="ms-Persona ms-Persona--square">
                    <div class="ms-Persona-imageArea">
                      <div class="ms-Persona-imageCircle">
                        <i class="ms-Persona-placeholder ms-Icon ms-Icon--person"></i>
                        <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                      </div>
                      <div class="ms-Persona-presence"></div>
                    </div>                            
                    <div class="ms-Persona-details">
                      <div class="ms-Persona-primaryText">Jessica Fischer</div>
                      <div class="ms-Persona-secondaryText">Marketing</div>
                    </div>
                  </div>
                </button>
              </li>
            </ul>
          </div>
        </div>
      </li>
      <!-- /org chart -->
    </ul>
  </div>
  </div>
</div></div>
  </div>
  
  <!-- 选项卡 -->
   <h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">Pivot</h1>
  <div id="componentWrapper">
    <!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<ul class="ms-Pivot">
  <li class="ms-Pivot-link is-selected">My files</li>
  <li class="ms-Pivot-link">Recent</li>
  <li class="ms-Pivot-link">Shared with me</li>
  <li class="ms-Pivot-link ms-Pivot-link--overflow">
    <i class="ms-Pivot-ellipsis ms-Icon ms-Icon--ellipsis"></i>
  </li>
</ul>

  </div>
  
 <!-- 搜索框 -->
   <h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">SearchBox</h1>
  <div id="componentWrapper">
    <!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-SearchBox">
  <input class="ms-SearchBox-field">
  <label class="ms-SearchBox-label"><i class="ms-SearchBox-icon ms-Icon ms-Icon--search"></i>Search</label>
  <button class="ms-SearchBox-closeButton"><i class="ms-Icon ms-Icon--x"></i></button>
</div>

  </div>
  
  <!--  -->
    <h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">Spinner</h1>
  <div id="componentWrapper">
    <!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="component-holder"></div>
</div>

  <!-- 表格 -->
   <h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">Table</h1>
  <div id="componentWrapper">
    <!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Table">
  <div class="ms-Table-row">
    <span class="ms-Table-rowCheck"></span>
    <span class="ms-Table-cell">File name</span>
    <span class="ms-Table-cell">Location</span>
    <span class="ms-Table-cell">Modified</span>
    <span class="ms-Table-cell">Type</span>
  </div>
  <div class="ms-Table-row">
    <span class="ms-Table-rowCheck"></span>
    <span class="ms-Table-cell">File name</span>
    <span class="ms-Table-cell">Location</span>
    <span class="ms-Table-cell">Modified</span>
    <span class="ms-Table-cell">Type</span>
  </div>
  <div class="ms-Table-row">
    <span class="ms-Table-rowCheck"></span>
    <span class="ms-Table-cell">File name</span>
    <span class="ms-Table-cell">Location</span>
    <span class="ms-Table-cell">Modified</span>
    <span class="ms-Table-cell">Type</span>
  </div>
  <div class="ms-Table-row is-selected">
    <span class="ms-Table-rowCheck"></span>
    <span class="ms-Table-cell">File name</span>
    <span class="ms-Table-cell">Location</span>
    <span class="ms-Table-cell">Modified</span>
    <span class="ms-Table-cell">Type</span>
  </div>
  <div class="ms-Table-row">
    <span class="ms-Table-rowCheck"></span>
    <span class="ms-Table-cell">File name</span>
    <span class="ms-Table-cell">Location</span>
    <span class="ms-Table-cell">Modified</span>
    <span class="ms-Table-cell">Type</span>
  </div>
  <div class="ms-Table-row">
    <span class="ms-Table-rowCheck"></span>
    <span class="ms-Table-cell">File name</span>
    <span class="ms-Table-cell">Location</span>
    <span class="ms-Table-cell">Modified</span>
    <span class="ms-Table-cell">Type</span>
  </div>
</div>

  </div>
  
  <!-- 输入框 -->
  <h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">TextField</h1>
  <div id="componentWrapper">
    <!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-TextField">
  <label class="ms-Label">Name</label>
  <input class="ms-TextField-field">
  <span class="ms-TextField-description">This should be your first and last name.</span>
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-TextField ms-TextField--multiline">
  <label class="ms-Label">Enter street</label>
  <textarea class="ms-TextField-field"></textarea>
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-TextField ms-TextField--placeholder">
  <label class="ms-Label">Given name</label>
  <input class="ms-TextField-field">
</div>
<div class="ms-TextField">
  <label class="ms-Label">Family name</label>
  <input class="ms-TextField-field">
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-TextField ms-TextField--underlined">
  <label class="ms-Label">Name:</label>
  <input class="ms-TextField-field">
</div>

  </div>
  
  <!--  -->
  <h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">Toggle</h1>
  <div id="componentWrapper">
    <!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Toggle">
  <span class="ms-Toggle-description">Let apps use my location</span>
  <input type="checkbox" id="demo-toggle-3" class="ms-Toggle-input" />
  <label for="demo-toggle-3" class="ms-Toggle-field">
    <span class="ms-Label ms-Label--off">Off</span>
    <span class="ms-Label ms-Label--on">On</span>
  </label>
</div>

<!-- Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license. See LICENSE in the project root for license information. -->

<div class="ms-Toggle ms-Toggle--textLeft">
  <span class="ms-Toggle-description">Let apps use my location</span>
  <input type="checkbox" id="demo-toggle-1" class="ms-Toggle-input" />
  <label for="demo-toggle-1" class="ms-Toggle-field">
    <span class="ms-Label ms-Label--off">Off</span>
    <span class="ms-Label ms-Label--on">On</span>
  </label>
</div>

  </div>

<?php
	Main::extend(
		'~layout.foot',
		array(
			'before' => array(
				'jquery',
				'jquery.fabric'
				),
        'after' => function(){
?>
  <script type="text/javascript">
    $(document).ready(function() {
      // File Picker demo fixes
      if ($('.ms-FilePicker').length > 0) {
        $('.ms-FilePicker').css({
          "position": "absolute !important"
        });

        $('.ms-Panel').FilePicker();
      } else {
        if ($.fn.NavBar) {
          $('.ms-NavBar').NavBar();
        }
      }

      if(fabric && fabric['NavBar']) {
        var component, componentHolder;
        componentHolder = document.querySelector('.component-holder');

        if (componentHolder) {
          component = new fabric.Spinner(componentHolder);
        }
      }
    });
  </script>
<?php
        }
			)
		);
	?>