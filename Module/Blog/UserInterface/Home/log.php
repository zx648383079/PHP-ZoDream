<?php foreach ($log_list as $log): ?>
    <dl>
        <dt><a><?=$log['name']?></a> <?=$log['action']?>了 《<a href="<?=$log->blog->url?>"><?=$log->blog->title?></a>》</dt>
        <dd>
            <p><?=$log['content']?></p>
            <span class="book-time"><?=$this->ago($log['created_at'])?></span>
        </dd>
    </dl>
<?php endforeach;?>