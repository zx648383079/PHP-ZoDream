<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '时间戳在线转换工具';
?>

<div class="converter-box">
    <div class="input-box">
        <textarea name="" placeholder="请输入内容，默认为当前时间"></textarea>
    </div>
    <div class="actions">
        <button data-type="strtotime">编码</button>
        <button data-type="date">解码</button>
        <button data-type="clear">清空</button>
    </div>
    <div class="output-box">
        <textarea name="" placeholder="输出结果"></textarea>
    </div>
</div>

<div class="converter-tip">
    <div class="tip-header">
    获取当前时间戳
    </div>
    <table>
            <tbody>
            <tr>
                <td>Swift</td>
                <td>
                    <pre><code>NSDate().timeIntervalSince1970</code></pre>
                </td>
            </tr>
            <tr>
                <td>Go</td>
                <td><pre><code>import (
  "time"
)
int32(time.Now().Unix())</code></pre>
                </td>
            </tr>
            <tr>
                <td>Java</td>
                <td>
                    <pre><code>// pure java
(int) (System.currentTimeMillis() / 1000)</code></pre>
                    <pre><code>// joda
(int) (DateTime.now().getMillis() / 1000)</code></pre>
                </td>
            </tr>
            <tr>
                <td>JavaScript</td>
                <td>
                    <pre><code>Math.round(new Date() / 1000)</code></pre>
                </td>
            </tr>
            <tr>
                <td>Objective-C</td>
                <td>
                    <pre><code>[[NSDate date] timeIntervalSince1970]</code></pre>
                </td>
            </tr>
            <tr>
                <td>MySQL</td>
                <td>
                    <pre><code>SELECT unix_timestamp(now())</code></pre>
                </td>
            </tr>
            <tr>
                <td>SQLite</td>
                <td>
                    <pre><code>SELECT strftime('%s', 'now')</code></pre>
                </td>
            </tr>
            <tr>
                <td>Erlang</td>
                <td>
                    <pre><code>calendar:datetime_to_gregorian_seconds(calendar:universal_time())-719528*24*3600.</code></pre>
                </td>
            </tr>
            <tr>
                <td>PHP</td>
                <td>
                    <pre><code>// pure php
time()</code></pre>
                    <pre><code>// Carbon\Carbon
Carbon::now()-&gt;timestamp</code></pre>
                </td>
            </tr>
            <tr>
                <td>Python</td>
                <td><pre><code>import time
time.time()</code></pre>
                </td>
            </tr>
            <tr>
                <td>Ruby</td>
                <td>
                    <pre><code>Time.now.to_i</code></pre>
                </td>
            </tr>
            <tr>
                <td>Shell</td>
                <td>
                    <pre><code>date +%s</code></pre>
                </td>
            </tr>
            <tr>
                <td>Groovy</td>
                <td>
                    <pre><code>(new Date().time / 1000).intValue()</code></pre>
                </td>
            </tr>
            <tr>
                <td>Lua</td>
                <td>
                    <pre><code>os.time()</code></pre>
                </td>
            </tr>
            <tr>
                <td>.NET/C#</td>
                <td>
                    <pre><code>(DateTime.Now.ToUniversalTime().Ticks - 621355968000000000) / 10000000</code></pre>
                </td>
            </tr>
            </tbody>
        </table>
</div>