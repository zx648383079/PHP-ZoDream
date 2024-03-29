<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'Base64在线编码解码工具';
$this->keywords = 'Base64在线工具,Base64编码解码,Base64转换';
$this->description = 'ZoDream在线工具提供Base64在线工具,Base64编码解码,Base64转换,Unicode Base64 UTF-8编码等在线工具';
?>

<div class="converter-box">
    <div class="input-box">
        <textarea name="" placeholder="请输入内容"></textarea>
    </div>
    <div class="actions">
        <button data-type="base64_encode">编码</button>
        <button data-type="base64_decode">解码</button>
        <button data-type="clear">清空</button>
    </div>
    <div class="output-box">
        <textarea name="" placeholder="输出结果"></textarea>
    </div>
</div>

<div class="converter-tip">
    <p>Base64编码要求把3个8位字节（3*8=24）转化为4个6位的字节（4*6=24），之后在6位的前面补两个0，形成8位一个字节的形式。
        如果剩下的字符不足3个字节，则用0填充，输出字符使用‘=’，因此编码后输出的文本末尾可能会出现1或2个‘=’。</p>
    <p>为了保证所输出的编码位可读字符，Base64制定了一个编码表，以便进行统一转换。编码表的大小为2^6=64，这也是Base64名称的由来。</p>
    <h3 class="tip-header">Base64编码表</h3>
    <table class="table">
        <tbody>
            <tr>
                <th>码值</th>
                <th>字符</th>
                <th>码值</th>
                <th>字符</th>
                <th>码值</th>
                <th>字符</th>
                <th>码值</th>
                <th>字符</th>
                <th>码值</th>
                <th>字符</th>
                <th>码值</th>
                <th>字符</th>
                <th>码值</th>
                <th>字符</th>
                <th>码值</th>
                <th>字符</th>
            </tr>
            <tr>
                <td>0</td>
                <td>A</td>
                <td>8</td>
                <td>I</td>
                <td>16</td>
                <td>Q</td>
                <td>24</td>
                <td>Y</td>
                <td>32</td>
                <td>g</td>
                <td>40</td>
                <td>o</td>
                <td>48</td>
                <td>w</td>
                <td>56</td>
                <td>4</td>
            </tr>
            <tr>
                <td>1</td>
                <td>B</td>
                <td>9</td>
                <td>J</td>
                <td>17</td>
                <td>R</td>
                <td>25</td>
                <td>Z</td>
                <td>33</td>
                <td>h</td>
                <td>41</td>
                <td>p</td>
                <td>49</td>
                <td>x</td>
                <td>57</td>
                <td>5</td>
            </tr>
            <tr>
                <td>2</td>
                <td>C</td>
                <td>10</td>
                <td>K</td>
                <td>18</td>
                <td>S</td>
                <td>26</td>
                <td>a</td>
                <td>34</td>
                <td>i</td>
                <td>42</td>
                <td>q</td>
                <td>50</td>
                <td>y</td>
                <td>58</td>
                <td>6</td>
            </tr>
            <tr>
                <td>3</td>
                <td>D</td>
                <td>11</td>
                <td>L</td>
                <td>19</td>
                <td>T</td>
                <td>27</td>
                <td>b</td>
                <td>35</td>
                <td>j</td>
                <td>43</td>
                <td>r</td>
                <td>51</td>
                <td>z</td>
                <td>59</td>
                <td>7</td>
            </tr>
            <tr>
                <td>4</td>
                <td>E</td>
                <td>12</td>
                <td>M</td>
                <td>20</td>
                <td>U</td>
                <td>28</td>
                <td>c</td>
                <td>36</td>
                <td>k</td>
                <td>44</td>
                <td>s</td>
                <td>52</td>
                <td>0</td>
                <td>60</td>
                <td>8</td>
            </tr>
            <tr>
                <td>5</td>
                <td>F</td>
                <td>13</td>
                <td>N</td>
                <td>21</td>
                <td>V</td>
                <td>29</td>
                <td>d</td>
                <td>37</td>
                <td>l</td>
                <td>45</td>
                <td>t</td>
                <td>53</td>
                <td>1</td>
                <td>61</td>
                <td>9</td>
            </tr>
            <tr>
                <td>6</td>
                <td>G</td>
                <td>14</td>
                <td>O</td>
                <td>22</td>
                <td>W</td>
                <td>30</td>
                <td>e</td>
                <td>38</td>
                <td>m</td>
                <td>46</td>
                <td>u</td>
                <td>54</td>
                <td>2</td>
                <td>62</td>
                <td>+</td>
            </tr>
            <tr>
                <td>7</td>
                <td>H</td>
                <td>15</td>
                <td>P</td>
                <td>23</td>
                <td>X</td>
                <td>31</td>
                <td>f</td>
                <td>39</td>
                <td>n</td>
                <td>47</td>
                <td>v</td>
                <td>55</td>
                <td>3</td>
                <td>63</td>
                <td>/</td>
            </tr>
        </tbody>
    </table>
</div>
