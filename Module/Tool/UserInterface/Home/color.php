<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '颜色值在线转换工具';
$this->keywords = '颜色在线工具,rgb转换,hex转换,rgba转换,hsl转换';
$this->description = 'ZoDream在线工具提供颜色在线工具,rgb转换,hex转换,rgba转换,hsl转换等在线工具';
?>

<div class="converter-box">
    <div class="input-box">
        <textarea name="" placeholder="请输入内容，例如：#fff"></textarea>
    </div>
    <div class="actions">
        <button data-type="color_converter">转换</button>
        <button data-type="clear">清空</button>
    </div>
    <div class="output-box">
        <textarea name="" placeholder="输出结果"></textarea>
    </div>
</div>

<div class="converter-tip">
    <h3 class="tip-header">gray</h3>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>颜色名</th>
                <th>预览</th>
                <th>HEX</th>
                <th>RGB</th>
                <th>HSL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>gainsboro</td>
                <td style="background-color: gainsboro"></td>
                <td>#dcdcdc</td>
                <td>220, 220, 220</td>
                <td>0, 0%, 86%</td>
            </tr>
            <tr>
                <td>lightgray</td>
                <td style="background-color: lightgray"></td>
                <td>#d3d3d3</td>
                <td>211, 211, 211</td>
                <td>0, 0%, 83%</td>
            </tr>
            <tr>
                <td>silver</td>
                <td style="background-color: silver"></td>
                <td>#c0c0c0</td>
                <td>192, 192, 192</td>
                <td>0, 0%, 75%</td>
            </tr>
            <tr>
                <td>darkgray</td>
                <td style="background-color: darkgray"></td>
                <td>#a9a9a9</td>
                <td>169, 169, 169</td>
                <td>0, 0%, 66%</td>
            </tr>
            <tr>
                <td>gray</td>
                <td style="background-color: gray"></td>
                <td>#808080</td>
                <td>128, 128, 128</td>
                <td>0, 0%, 50%</td>
            </tr>
            <tr>
                <td>dimgray</td>
                <td style="background-color: dimgray"></td>
                <td>#696969</td>
                <td>105, 105, 105</td>
                <td>0, 0%, 41%</td>
            </tr>
            <tr>
                <td>lightslategray</td>
                <td style="background-color: lightslategray"></td>
                <td>#778899</td>
                <td>119, 136, 153</td>
                <td>210, 14%, 53%</td>
            </tr>
            <tr>
                <td>slategray</td>
                <td style="background-color: slategray"></td>
                <td>#708090</td>
                <td>112, 128, 144</td>
                <td>210, 13%, 50%</td>
            </tr>
            <tr>
                <td>darkslategray</td>
                <td style="background-color: darkslategray"></td>
                <td>#2f4f4f</td>
                <td>47, 79, 79</td>
                <td>180, 25%, 25%</td>
            </tr>
            <tr>
                <td>black</td>
                <td style="background-color: black"></td>
                <td>#000000</td>
                <td>0, 0, 0</td>
                <td>0, 0%, 0%</td>
            </tr>
        </tbody>
    </table>
    <h3 class="tip-header">red</h3>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>颜色名</th>
                <th>预览</th>
                <th>HEX</th>
                <th>RGB</th>
                <th>HSL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>indianred</td>
                <td style="background-color: indianred"></td>
                <td>#cd5c5c</td>
                <td>205, 92, 92</td>
                <td>0, 53%, 58%</td>
            </tr>
            <tr>
                <td>lightcoral</td>
                <td style="background-color: lightcoral"></td>
                <td>#f08080</td>
                <td>240, 128, 128</td>
                <td>0, 79%, 72%</td>
            </tr>
            <tr>
                <td>salmon</td>
                <td style="background-color: salmon"></td>
                <td>#fa8072</td>
                <td>250, 128, 114</td>
                <td>6, 93%, 71%</td>
            </tr>
            <tr>
                <td>darksalmon</td>
                <td style="background-color: darksalmon"></td>
                <td>#e9967a</td>
                <td>233, 150, 122</td>
                <td>15, 72%, 70%</td>
            </tr>
            <tr>
                <td>lightsalmon</td>
                <td style="background-color: lightsalmon"></td>
                <td>#ffa07a</td>
                <td>255, 160, 122</td>
                <td>17, 100%, 74%</td>
            </tr>
            <tr>
                <td>crimson</td>
                <td style="background-color: crimson"></td>
                <td>#dc143c</td>
                <td>220, 20, 60</td>
                <td>348, 83%, 47%</td>
            </tr>
            <tr>
                <td>red</td>
                <td style="background-color: red"></td>
                <td>#ff0000</td>
                <td>255, 0, 0</td>
                <td>0, 100%, 50%</td>
            </tr>
            <tr>
                <td>firebrick</td>
                <td style="background-color: firebrick"></td>
                <td>#b22222</td>
                <td>178, 34, 34</td>
                <td>0, 68%, 42%</td>
            </tr>
            <tr>
                <td>darkred</td>
                <td style="background-color: darkred"></td>
                <td>#8b0000</td>
                <td>139, 0, 0</td>
                <td>0, 100%, 27%</td>
            </tr>
        </tbody>
    </table>
    <h3 class="tip-header">orange</h3>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>颜色名</th>
                <th>预览</th>
                <th>HEX</th>
                <th>RGB</th>
                <th>HSL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>lightsalmon</td>
                <td style="background-color: lightsalmon"></td>
                <td>#ffa07a</td>
                <td>255, 160, 122</td>
                <td>17, 100%, 74%</td>
            </tr>
            <tr>
                <td>coral</td>
                <td style="background-color: coral"></td>
                <td>#ff7f50</td>
                <td>255, 127, 80</td>
                <td>16, 100%, 66%</td>
            </tr>
            <tr>
                <td>tomato</td>
                <td style="background-color: tomato"></td>
                <td>#ff6347</td>
                <td>255, 99, 71</td>
                <td>9, 100%, 64%</td>
            </tr>
            <tr>
                <td>orangered</td>
                <td style="background-color: orangered"></td>
                <td>#ff4500</td>
                <td>255, 69, 0</td>
                <td>16, 100%, 50%</td>
            </tr>
            <tr>
                <td>darkorange</td>
                <td style="background-color: darkorange"></td>
                <td>#ff8c00</td>
                <td>255, 140, 0</td>
                <td>33, 100%, 50%</td>
            </tr>
            <tr>
                <td>orange</td>
                <td style="background-color: orange"></td>
                <td>#ffa500</td>
                <td>255, 165, 0</td>
                <td>39, 100%, 50%</td>
            </tr>
        </tbody>
    </table>
    <h3 class="tip-header">brown</h3>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>颜色名</th>
                <th>预览</th>
                <th>HEX</th>
                <th>RGB</th>
                <th>HSL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>cornsilk</td>
                <td style="background-color: cornsilk"></td>
                <td>#fff8dc</td>
                <td>255, 248, 220</td>
                <td>48, 100%, 93%</td>
            </tr>
            <tr>
                <td>blanchedalmond</td>
                <td style="background-color: blanchedalmond"></td>
                <td>#ffebcd</td>
                <td>255, 235, 205</td>
                <td>36, 100%, 90%</td>
            </tr>
            <tr>
                <td>bisque</td>
                <td style="background-color: bisque"></td>
                <td>#ffe4c4</td>
                <td>255, 228, 196</td>
                <td>33, 100%, 88%</td>
            </tr>
            <tr>
                <td>navajowhite</td>
                <td style="background-color: navajowhite"></td>
                <td>#ffdead</td>
                <td>255, 222, 173</td>
                <td>36, 100%, 84%</td>
            </tr>
            <tr>
                <td>wheat</td>
                <td style="background-color: wheat"></td>
                <td>#f5deb3</td>
                <td>245, 222, 179</td>
                <td>39, 77%, 83%</td>
            </tr>
            <tr>
                <td>burlywood</td>
                <td style="background-color: burlywood"></td>
                <td>#deb887</td>
                <td>222, 184, 135</td>
                <td>34, 57%, 70%</td>
            </tr>
            <tr>
                <td>tan</td>
                <td style="background-color: tan"></td>
                <td>#d2b48c</td>
                <td>210, 180, 140</td>
                <td>34, 44%, 69%</td>
            </tr>
            <tr>
                <td>rosybrown</td>
                <td style="background-color: rosybrown"></td>
                <td>#bc8f8f</td>
                <td>188, 143, 143</td>
                <td>0, 25%, 65%</td>
            </tr>
            <tr>
                <td>sandybrown</td>
                <td style="background-color: sandybrown"></td>
                <td>#f4a460</td>
                <td>244, 164, 96</td>
                <td>28, 87%, 67%</td>
            </tr>
            <tr>
                <td>goldenrod</td>
                <td style="background-color: goldenrod"></td>
                <td>#daa520</td>
                <td>218, 165, 32</td>
                <td>43, 74%, 49%</td>
            </tr>
            <tr>
                <td>darkgoldenrod</td>
                <td style="background-color: darkgoldenrod"></td>
                <td>#b8860b</td>
                <td>184, 134, 11</td>
                <td>43, 89%, 38%</td>
            </tr>
            <tr>
                <td>peru</td>
                <td style="background-color: peru"></td>
                <td>#cd853f</td>
                <td>205, 133, 63</td>
                <td>30, 59%, 53%</td>
            </tr>
            <tr>
                <td>chocolate</td>
                <td style="background-color: chocolate"></td>
                <td>#d2691e</td>
                <td>210, 105, 30</td>
                <td>25, 75%, 47%</td>
            </tr>
            <tr>
                <td>saddlebrown</td>
                <td style="background-color: saddlebrown"></td>
                <td>#8b4513</td>
                <td>139, 69, 19</td>
                <td>25, 76%, 31%</td>
            </tr>
            <tr>
                <td>sienna</td>
                <td style="background-color: sienna"></td>
                <td>#a0522d</td>
                <td>160, 82, 45</td>
                <td>19, 56%, 40%</td>
            </tr>
            <tr>
                <td>brown</td>
                <td style="background-color: brown"></td>
                <td>#a52a2a</td>
                <td>165, 42, 42</td>
                <td>0, 59%, 41%</td>
            </tr>
            <tr>
                <td>maroon</td>
                <td style="background-color: maroon"></td>
                <td>#800000</td>
                <td>128, 0, 0</td>
                <td>0, 100%, 25%</td>
            </tr>
        </tbody>
    </table>
    <h3 class="tip-header">blue</h3>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>颜色名</th>
                <th>预览</th>
                <th>HEX</th>
                <th>RGB</th>
                <th>HSL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>aqua</td>
                <td style="background-color: aqua"></td>
                <td>#00ffff</td>
                <td>0, 255, 255</td>
                <td>180, 100%, 50%</td>
            </tr>
            <tr>
                <td>cyan</td>
                <td style="background-color: cyan"></td>
                <td>#00ffff</td>
                <td>0, 255, 255</td>
                <td>180, 100%, 50%</td>
            </tr>
            <tr>
                <td>lightcyan</td>
                <td style="background-color: lightcyan"></td>
                <td>#e0ffff</td>
                <td>224, 255, 255</td>
                <td>180, 100%, 94%</td>
            </tr>
            <tr>
                <td>paleturquoise</td>
                <td style="background-color: paleturquoise"></td>
                <td>#afeeee</td>
                <td>175, 238, 238</td>
                <td>180, 65%, 81%</td>
            </tr>
            <tr>
                <td>aquamarine</td>
                <td style="background-color: aquamarine"></td>
                <td>#7fffd4</td>
                <td>127, 255, 212</td>
                <td>160, 100%, 75%</td>
            </tr>
            <tr>
                <td>turquoise</td>
                <td style="background-color: turquoise"></td>
                <td>#40e0d0</td>
                <td>64, 224, 208</td>
                <td>174, 72%, 56%</td>
            </tr>
            <tr>
                <td>mediumturquoise</td>
                <td style="background-color: mediumturquoise"></td>
                <td>#48d1cc</td>
                <td>72, 209, 204</td>
                <td>178, 60%, 55%</td>
            </tr>
            <tr>
                <td>darkturquoise</td>
                <td style="background-color: darkturquoise"></td>
                <td>#00ced1</td>
                <td>0, 206, 209</td>
                <td>181, 100%, 41%</td>
            </tr>
            <tr>
                <td>cadetblue</td>
                <td style="background-color: cadetblue"></td>
                <td>#5f9ea0</td>
                <td>95, 158, 160</td>
                <td>182, 25%, 50%</td>
            </tr>
            <tr>
                <td>steelblue</td>
                <td style="background-color: steelblue"></td>
                <td>#4682b4</td>
                <td>70, 130, 180</td>
                <td>207, 44%, 49%</td>
            </tr>
            <tr>
                <td>lightsteelblue</td>
                <td style="background-color: lightsteelblue"></td>
                <td>#b0c4de</td>
                <td>176, 196, 222</td>
                <td>214, 41%, 78%</td>
            </tr>
            <tr>
                <td>powderblue</td>
                <td style="background-color: powderblue"></td>
                <td>#b0e0e6</td>
                <td>176, 224, 230</td>
                <td>187, 52%, 80%</td>
            </tr>
            <tr>
                <td>lightblue</td>
                <td style="background-color: lightblue"></td>
                <td>#add8e6</td>
                <td>173, 216, 230</td>
                <td>195, 53%, 79%</td>
            </tr>
            <tr>
                <td>skyblue</td>
                <td style="background-color: skyblue"></td>
                <td>#87ceeb</td>
                <td>135, 206, 235</td>
                <td>197, 71%, 73%</td>
            </tr>
            <tr>
                <td>lightskyblue</td>
                <td style="background-color: lightskyblue"></td>
                <td>#87cefa</td>
                <td>135, 206, 250</td>
                <td>203, 92%, 75%</td>
            </tr>
            <tr>
                <td>deepskyblue</td>
                <td style="background-color: deepskyblue"></td>
                <td>#00bfff</td>
                <td>0, 191, 255</td>
                <td>195, 100%, 50%</td>
            </tr>
            <tr>
                <td>dodgerblue</td>
                <td style="background-color: dodgerblue"></td>
                <td>#1e90ff</td>
                <td>30, 144, 255</td>
                <td>210, 100%, 56%</td>
            </tr>
            <tr>
                <td>cornflowerblue</td>
                <td style="background-color: cornflowerblue"></td>
                <td>#6495ed</td>
                <td>100, 149, 237</td>
                <td>219, 79%, 66%</td>
            </tr>
            <tr>
                <td>mediumslateblue</td>
                <td style="background-color: mediumslateblue"></td>
                <td>#7b68ee</td>
                <td>123, 104, 238</td>
                <td>249, 80%, 67%</td>
            </tr>
            <tr>
                <td>royalblue</td>
                <td style="background-color: royalblue"></td>
                <td>#4169e1</td>
                <td>65, 105, 225</td>
                <td>225, 73%, 57%</td>
            </tr>
            <tr>
                <td>blue</td>
                <td style="background-color: blue"></td>
                <td>#0000ff</td>
                <td>0, 0, 255</td>
                <td>240, 100%, 50%</td>
            </tr>
            <tr>
                <td>mediumblue</td>
                <td style="background-color: mediumblue"></td>
                <td>#0000cd</td>
                <td>0, 0, 205</td>
                <td>240, 100%, 40%</td>
            </tr>
            <tr>
                <td>darkblue</td>
                <td style="background-color: darkblue"></td>
                <td>#00008b</td>
                <td>0, 0, 139</td>
                <td>240, 100%, 27%</td>
            </tr>
            <tr>
                <td>navy</td>
                <td style="background-color: navy"></td>
                <td>#000080</td>
                <td>0, 0, 128</td>
                <td>240, 100%, 25%</td>
            </tr>
            <tr>
                <td>midnightblue</td>
                <td style="background-color: midnightblue"></td>
                <td>#191970</td>
                <td>25, 25, 112</td>
                <td>240, 64%, 27%</td>
            </tr>
        </tbody>
    </table>
    <h3 class="tip-header">green</h3>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>颜色名</th>
                <th>预览</th>
                <th>HEX</th>
                <th>RGB</th>
                <th>HSL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>greenyellow</td>
                <td style="background-color: greenyellow"></td>
                <td>#adff2f</td>
                <td>173, 255, 47</td>
                <td>84, 100%, 59%</td>
            </tr>
            <tr>
                <td>chartreuse</td>
                <td style="background-color: chartreuse"></td>
                <td>#7fff00</td>
                <td>127, 255, 0</td>
                <td>90, 100%, 50%</td>
            </tr>
            <tr>
                <td>lawngreen</td>
                <td style="background-color: lawngreen"></td>
                <td>#7cfc00</td>
                <td>124, 252, 0</td>
                <td>90, 100%, 49%</td>
            </tr>
            <tr>
                <td>lime</td>
                <td style="background-color: lime"></td>
                <td>#00ff00</td>
                <td>0, 255, 0</td>
                <td>120, 100%, 50%</td>
            </tr>
            <tr>
                <td>limegreen</td>
                <td style="background-color: limegreen"></td>
                <td>#32cd32</td>
                <td>50, 205, 50</td>
                <td>120, 61%, 50%</td>
            </tr>
            <tr>
                <td>palegreen</td>
                <td style="background-color: palegreen"></td>
                <td>#98fb98</td>
                <td>152, 251, 152</td>
                <td>120, 93%, 79%</td>
            </tr>
            <tr>
                <td>lightgreen</td>
                <td style="background-color: lightgreen"></td>
                <td>#90ee90</td>
                <td>144, 238, 144</td>
                <td>120, 73%, 75%</td>
            </tr>
            <tr>
                <td>mediumspringgreen</td>
                <td style="background-color: mediumspringgreen"></td>
                <td>#00fa9a</td>
                <td>0, 250, 154</td>
                <td>157, 100%, 49%</td>
            </tr>
            <tr>
                <td>springgreen</td>
                <td style="background-color: springgreen"></td>
                <td>#00ff7f</td>
                <td>0, 255, 127</td>
                <td>150, 100%, 50%</td>
            </tr>
            <tr>
                <td>mediumseagreen</td>
                <td style="background-color: mediumseagreen"></td>
                <td>#3cb371</td>
                <td>60, 179, 113</td>
                <td>147, 50%, 47%</td>
            </tr>
            <tr>
                <td>seagreen</td>
                <td style="background-color: seagreen"></td>
                <td>#2e8b57</td>
                <td>46, 139, 87</td>
                <td>146, 50%, 36%</td>
            </tr>
            <tr>
                <td>forestgreen</td>
                <td style="background-color: forestgreen"></td>
                <td>#228b22</td>
                <td>34, 139, 34</td>
                <td>120, 61%, 34%</td>
            </tr>
            <tr>
                <td>green</td>
                <td style="background-color: green"></td>
                <td>#008000</td>
                <td>0, 128, 0</td>
                <td>120, 100%, 25%</td>
            </tr>
            <tr>
                <td>darkgreen</td>
                <td style="background-color: darkgreen"></td>
                <td>#006400</td>
                <td>0, 100, 0</td>
                <td>120, 100%, 20%</td>
            </tr>
            <tr>
                <td>yellowgreen</td>
                <td style="background-color: yellowgreen"></td>
                <td>#9acd32</td>
                <td>154, 205, 50</td>
                <td>80, 61%, 50%</td>
            </tr>
            <tr>
                <td>olivedrab</td>
                <td style="background-color: olivedrab"></td>
                <td>#6b8e23</td>
                <td>107, 142, 35</td>
                <td>80, 60%, 35%</td>
            </tr>
            <tr>
                <td>olive</td>
                <td style="background-color: olive"></td>
                <td>#808000</td>
                <td>128, 128, 0</td>
                <td>60, 100%, 25%</td>
            </tr>
            <tr>
                <td>darkolivegreen</td>
                <td style="background-color: darkolivegreen"></td>
                <td>#556b2f</td>
                <td>85, 107, 47</td>
                <td>82, 39%, 30%</td>
            </tr>
            <tr>
                <td>mediumaquamarine</td>
                <td style="background-color: mediumaquamarine"></td>
                <td>#66cdaa</td>
                <td>102, 205, 170</td>
                <td>160, 51%, 60%</td>
            </tr>
            <tr>
                <td>darkseagreen</td>
                <td style="background-color: darkseagreen"></td>
                <td>#8fbc8b</td>
                <td>143, 188, 139</td>
                <td>115, 27%, 64%</td>
            </tr>
            <tr>
                <td>lightseagreen</td>
                <td style="background-color: lightseagreen"></td>
                <td>#20b2aa</td>
                <td>32, 178, 170</td>
                <td>177, 70%, 41%</td>
            </tr>
            <tr>
                <td>darkcyan</td>
                <td style="background-color: darkcyan"></td>
                <td>#008b8b</td>
                <td>0, 139, 139</td>
                <td>180, 100%, 27%</td>
            </tr>
            <tr>
                <td>teal</td>
                <td style="background-color: teal"></td>
                <td>#008080</td>
                <td>0, 128, 128</td>
                <td>180, 100%, 25%</td>
            </tr>
        </tbody>
    </table>
    <h3 class="tip-header">pink</h3>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>颜色名</th>
                <th>预览</th>
                <th>HEX</th>
                <th>RGB</th>
                <th>HSL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>pink</td>
                <td style="background-color: pink"></td>
                <td>#ffc0cb</td>
                <td>255, 192, 203</td>
                <td>350, 100%, 88%</td>
            </tr>
            <tr>
                <td>lightpink</td>
                <td style="background-color: lightpink"></td>
                <td>#ffb6c1</td>
                <td>255, 182, 193</td>
                <td>351, 100%, 86%</td>
            </tr>
            <tr>
                <td>hotpink</td>
                <td style="background-color: hotpink"></td>
                <td>#ff69b4</td>
                <td>255, 105, 180</td>
                <td>330, 100%, 71%</td>
            </tr>
            <tr>
                <td>deeppink</td>
                <td style="background-color: deeppink"></td>
                <td>#ff1493</td>
                <td>255, 20, 147</td>
                <td>328, 100%, 54%</td>
            </tr>
            <tr>
                <td>mediumvioletred</td>
                <td style="background-color: mediumvioletred"></td>
                <td>#c71585</td>
                <td>199, 21, 133</td>
                <td>322, 81%, 43%</td>
            </tr>
            <tr>
                <td>palevioletred</td>
                <td style="background-color: palevioletred"></td>
                <td>#db7093</td>
                <td>219, 112, 147</td>
                <td>340, 60%, 65%</td>
            </tr>
        </tbody>
    </table>
    <h3 class="tip-header">purple</h3>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>颜色名</th>
                <th>预览</th>
                <th>HEX</th>
                <th>RGB</th>
                <th>HSL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>lavender</td>
                <td style="background-color: lavender"></td>
                <td>#e6e6fa</td>
                <td>230, 230, 250</td>
                <td>240, 67%, 94%</td>
            </tr>
            <tr>
                <td>thistle</td>
                <td style="background-color: thistle"></td>
                <td>#d8bfd8</td>
                <td>216, 191, 216</td>
                <td>300, 24%, 80%</td>
            </tr>
            <tr>
                <td>plum</td>
                <td style="background-color: plum"></td>
                <td>#dda0dd</td>
                <td>221, 160, 221</td>
                <td>300, 47%, 75%</td>
            </tr>
            <tr>
                <td>violet</td>
                <td style="background-color: violet"></td>
                <td>#ee82ee</td>
                <td>238, 130, 238</td>
                <td>300, 76%, 72%</td>
            </tr>
            <tr>
                <td>orchid</td>
                <td style="background-color: orchid"></td>
                <td>#da70d6</td>
                <td>218, 112, 214</td>
                <td>302, 59%, 65%</td>
            </tr>
            <tr>
                <td>fuchsia</td>
                <td style="background-color: fuchsia"></td>
                <td>#ff00ff</td>
                <td>255, 0, 255</td>
                <td>300, 100%, 50%</td>
            </tr>
            <tr>
                <td>magenta</td>
                <td style="background-color: magenta"></td>
                <td>#ff00ff</td>
                <td>255, 0, 255</td>
                <td>300, 100%, 50%</td>
            </tr>
            <tr>
                <td>mediumorchid</td>
                <td style="background-color: mediumorchid"></td>
                <td>#ba55d3</td>
                <td>186, 85, 211</td>
                <td>288, 59%, 58%</td>
            </tr>
            <tr>
                <td>mediumpurple</td>
                <td style="background-color: mediumpurple"></td>
                <td>#9370db</td>
                <td>147, 112, 219</td>
                <td>260, 60%, 65%</td>
            </tr>
            <tr>
                <td>rebeccapurple</td>
                <td style="background-color: rebeccapurple"></td>
                <td>#663399</td>
                <td>102, 51, 153</td>
                <td>270, 50%, 40%</td>
            </tr>
            <tr>
                <td>blueviolet</td>
                <td style="background-color: blueviolet"></td>
                <td>#8a2be2</td>
                <td>138, 43, 226</td>
                <td>271, 76%, 53%</td>
            </tr>
            <tr>
                <td>darkviolet</td>
                <td style="background-color: darkviolet"></td>
                <td>#9400d3</td>
                <td>148, 0, 211</td>
                <td>282, 100%, 41%</td>
            </tr>
            <tr>
                <td>darkorchid</td>
                <td style="background-color: darkorchid"></td>
                <td>#9932cc</td>
                <td>153, 50, 204</td>
                <td>280, 61%, 50%</td>
            </tr>
            <tr>
                <td>darkmagenta</td>
                <td style="background-color: darkmagenta"></td>
                <td>#8b008b</td>
                <td>139, 0, 139</td>
                <td>300, 100%, 27%</td>
            </tr>
            <tr>
                <td>purple</td>
                <td style="background-color: purple"></td>
                <td>#800080</td>
                <td>128, 0, 128</td>
                <td>300, 100%, 25%</td>
            </tr>
            <tr>
                <td>indigo</td>
                <td style="background-color: indigo"></td>
                <td>#4b0082</td>
                <td>75, 0, 130</td>
                <td>275, 100%, 25%</td>
            </tr>
            <tr>
                <td>slateblue</td>
                <td style="background-color: slateblue"></td>
                <td>#6a5acd</td>
                <td>106, 90, 205</td>
                <td>248, 53%, 58%</td>
            </tr>
            <tr>
                <td>darkslateblue</td>
                <td style="background-color: darkslateblue"></td>
                <td>#483d8b</td>
                <td>72, 61, 139</td>
                <td>248, 39%, 39%</td>
            </tr>
            <tr>
                <td>mediumslateblue</td>
                <td style="background-color: mediumslateblue"></td>
                <td>#7b68ee</td>
                <td>123, 104, 238</td>
                <td>249, 80%, 67%</td>
            </tr>
        </tbody>
    </table>
    <h3 class="tip-header">white</h3>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>颜色名</th>
                <th>预览</th>
                <th>HEX</th>
                <th>RGB</th>
                <th>HSL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>white</td>
                <td style="background-color: white"></td>
                <td>#ffffff</td>
                <td>255, 255, 255</td>
                <td>0, 0%, 100%</td>
            </tr>
            <tr>
                <td>snow</td>
                <td style="background-color: snow"></td>
                <td>#fffafa</td>
                <td>255, 250, 250</td>
                <td>0, 100%, 99%</td>
            </tr>
            <tr>
                <td>honeydew</td>
                <td style="background-color: honeydew"></td>
                <td>#f0fff0</td>
                <td>240, 255, 240</td>
                <td>120, 100%, 97%</td>
            </tr>
            <tr>
                <td>mintcream</td>
                <td style="background-color: mintcream"></td>
                <td>#f5fffa</td>
                <td>245, 255, 250</td>
                <td>150, 100%, 98%</td>
            </tr>
            <tr>
                <td>azure</td>
                <td style="background-color: azure"></td>
                <td>#f0ffff</td>
                <td>240, 255, 255</td>
                <td>180, 100%, 97%</td>
            </tr>
            <tr>
                <td>aliceblue</td>
                <td style="background-color: aliceblue"></td>
                <td>#f0f8ff</td>
                <td>240, 248, 255</td>
                <td>208, 100%, 97%</td>
            </tr>
            <tr>
                <td>ghostwhite</td>
                <td style="background-color: ghostwhite"></td>
                <td>#f8f8ff</td>
                <td>248, 248, 255</td>
                <td>240, 100%, 99%</td>
            </tr>
            <tr>
                <td>whitesmoke</td>
                <td style="background-color: whitesmoke"></td>
                <td>#f5f5f5</td>
                <td>245, 245, 245</td>
                <td>0, 0%, 96%</td>
            </tr>
            <tr>
                <td>seashell</td>
                <td style="background-color: seashell"></td>
                <td>#fff5ee</td>
                <td>255, 245, 238</td>
                <td>25, 100%, 97%</td>
            </tr>
            <tr>
                <td>beige</td>
                <td style="background-color: beige"></td>
                <td>#f5f5dc</td>
                <td>245, 245, 220</td>
                <td>60, 56%, 91%</td>
            </tr>
            <tr>
                <td>oldlace</td>
                <td style="background-color: oldlace"></td>
                <td>#fdf5e6</td>
                <td>253, 245, 230</td>
                <td>39, 85%, 95%</td>
            </tr>
            <tr>
                <td>floralwhite</td>
                <td style="background-color: floralwhite"></td>
                <td>#fffaf0</td>
                <td>255, 250, 240</td>
                <td>40, 100%, 97%</td>
            </tr>
            <tr>
                <td>ivory</td>
                <td style="background-color: ivory"></td>
                <td>#fffff0</td>
                <td>255, 255, 240</td>
                <td>60, 100%, 97%</td>
            </tr>
            <tr>
                <td>antiquewhite</td>
                <td style="background-color: antiquewhite"></td>
                <td>#faebd7</td>
                <td>250, 235, 215</td>
                <td>34, 78%, 91%</td>
            </tr>
            <tr>
                <td>linen</td>
                <td style="background-color: linen"></td>
                <td>#faf0e6</td>
                <td>250, 240, 230</td>
                <td>30, 67%, 94%</td>
            </tr>
            <tr>
                <td>lavenderblush</td>
                <td style="background-color: lavenderblush"></td>
                <td>#fff0f5</td>
                <td>255, 240, 245</td>
                <td>340, 100%, 97%</td>
            </tr>
            <tr>
                <td>mistyrose</td>
                <td style="background-color: mistyrose"></td>
                <td>#ffe4e1</td>
                <td>255, 228, 225</td>
                <td>6, 100%, 94%</td>
            </tr>
        </tbody>
    </table>
    <h3 class="tip-header">yellow</h3>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>颜色名</th>
                <th>预览</th>
                <th>HEX</th>
                <th>RGB</th>
                <th>HSL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>gold</td>
                <td style="background-color: gold"></td>
                <td>#ffd700</td>
                <td>255, 215, 0</td>
                <td>51, 100%, 50%</td>
            </tr>
            <tr>
                <td>yellow</td>
                <td style="background-color: yellow"></td>
                <td>#ffff00</td>
                <td>255, 255, 0</td>
                <td>60, 100%, 50%</td>
            </tr>
            <tr>
                <td>lightyellow</td>
                <td style="background-color: lightyellow"></td>
                <td>#ffffe0</td>
                <td>255, 255, 224</td>
                <td>60, 100%, 94%</td>
            </tr>
            <tr>
                <td>lemonchiffon</td>
                <td style="background-color: lemonchiffon"></td>
                <td>#fffacd</td>
                <td>255, 250, 205</td>
                <td>54, 100%, 90%</td>
            </tr>
            <tr>
                <td>lightgoldenrodyellow</td>
                <td style="background-color: lightgoldenrodyellow"></td>
                <td>#fafad2</td>
                <td>250, 250, 210</td>
                <td>60, 80%, 90%</td>
            </tr>
            <tr>
                <td>papayawhip</td>
                <td style="background-color: papayawhip"></td>
                <td>#ffefd5</td>
                <td>255, 239, 213</td>
                <td>37, 100%, 92%</td>
            </tr>
            <tr>
                <td>moccasin</td>
                <td style="background-color: moccasin"></td>
                <td>#ffe4b5</td>
                <td>255, 228, 181</td>
                <td>38, 100%, 85%</td>
            </tr>
            <tr>
                <td>peachpuff</td>
                <td style="background-color: peachpuff"></td>
                <td>#ffdab9</td>
                <td>255, 218, 185</td>
                <td>28, 100%, 86%</td>
            </tr>
            <tr>
                <td>palegoldenrod</td>
                <td style="background-color: palegoldenrod"></td>
                <td>#eee8aa</td>
                <td>238, 232, 170</td>
                <td>55, 67%, 80%</td>
            </tr>
            <tr>
                <td>khaki</td>
                <td style="background-color: khaki"></td>
                <td>#f0e68c</td>
                <td>240, 230, 140</td>
                <td>54, 77%, 75%</td>
            </tr>
            <tr>
                <td>darkkhaki</td>
                <td style="background-color: darkkhaki"></td>
                <td>#bdb76b</td>
                <td>189, 183, 107</td>
                <td>56, 38%, 58%</td>
            </tr>
        </tbody>
    </table>
</div>